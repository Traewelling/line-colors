const FILES = [
    {id: "de", name: "Germany", file: "line-colors.csv"},
    {id: "at", name: "Austria", file: "line-colors-AT.csv"},
    {id: "fi", name: "Finland", file: "line-colors-FI.csv"},
    {id: "lu", name: "Luxembourg", file: "line-colors-LU.csv"},
    {id: "se", name: "Sweden", file: "line-colors-SE.csv"},
    {id: "ch", name: "Switzerland", file: "line-colors-CH.csv"},
];
const COLOR_KEYS = ["backgroundColor", "textColor", "borderColor"];
const MONOSPACE_KEYS = [...COLOR_KEYS, "shape"];
const MAX_RESULTS = 200;

const sidebar = document.getElementById("sidebar");
const sidebarToggler = document.getElementById("sidebarCollapse");
const navToggler = document.getElementById("navCollapse");
const home = document.getElementById("home");
const view = document.getElementById("view");
const cache = new Map();
let sidebarFile;
let sidebarData;

function toggleDetails() {
    const shown = document.getElementById("main-wrapper").classList.toggle("details-shown");
    document.getElementById("details-toggler").textContent = shown ? "Hide details" : "Show details";
}

function setSidebarOpen(open) {
    sidebar.classList.toggle("active", open);
    sidebarToggler.classList.toggle("show", open);
    sidebarToggler.lastElementChild.classList.toggle("w-50", !open);
}

function h(tag, attributes = {}, ...children) {
    const element = document.createElement(tag);
    for (const [key, value] of Object.entries(attributes)) {
        if (key === "style") Object.assign(element.style, value);
        else if (key.startsWith("on")) element.addEventListener(key.slice(2), value);
        else element.setAttribute(key, value);
    }
    element.append(...children.flat(Infinity).filter((child) => child !== null && child !== undefined));
    return element;
}

function parseCsv(text) {
    const rows = [];
    let row = [], field = "", quoted = false;
    const endRow = () => {
        row.push(field);
        if (row.length > 1 || row[0] !== "") rows.push(row);
        row = [];
        field = "";
    };
    for (let i = 0; i < text.length; i++) {
        const c = text[i];
        if (quoted) {
            if (c !== "\"") field += c;
            else if (text[i + 1] === "\"") field += text[i++];
            else quoted = false;
        } else if (c === "\"") quoted = true;
        else if (c === ",") {
            row.push(field);
            field = "";
        } else if (c === "\n") endRow();
        else if (c !== "\r") field += c;
    }
    endRow();
    const keys = rows.shift();
    return rows.map((values) => Object.fromEntries(keys.map((key, i) => [key, values[i] ?? ""])));
}

function load(file) {
    if (!cache.has(file.id)) {
        cache.set(file.id, fetch(file.file)
            .then((response) => {
                if (!response.ok) throw new Error(response.statusText);
                return response.text();
            })
            .then((text) => {
                const lines = parseCsv(text);
                const operators = new Map();
                for (const line of lines) {
                    if (!operators.has(line.shortOperatorName)) operators.set(line.shortOperatorName, []);
                    operators.get(line.shortOperatorName).push(line);
                }
                return {lines, operators};
            })
            .catch((error) => {
                cache.delete(file.id);
                throw error;
            }));
    }
    return cache.get(file.id);
}

function link(file, operator) {
    return "#" + file.id + (operator ? "/" + encodeURIComponent(operator) : "");
}

function logo(line) {
    const style = {backgroundColor: line.backgroundColor, color: line.textColor};
    if (line.borderColor) style.border = "0.1em solid " + line.borderColor;
    return h("span", {class: "line-logo shape-" + line.shape, style}, line.lineName);
}

function details(line) {
    return h("dl", {}, Object.entries(line)
        .filter(([key, value]) => key !== "borderColor" || value !== "")
        .map(([key, value]) => [
            h("dt", {}, key),
            h("dd", {class: MONOSPACE_KEYS.includes(key) ? "monospace" : ""},
                COLOR_KEYS.includes(key) ? [h("span", {class: "color-preview", style: {backgroundColor: value}}), " "] : null,
                value),
        ]));
}

function lineGrid(file, lines, showOperator) {
    return h("div", {class: "operator-group"}, lines.map((line) => h("article", {},
        logo(line),
        showOperator ? h("a", {class: "operator-label", href: link(file, line.shortOperatorName)}, line.shortOperatorName) : null,
        details(line),
    )));
}

function renderSidebar(file, data = null, operator) {
    if (sidebarFile !== file || sidebarData !== data) {
        sidebarFile = file;
        sidebarData = data;
        sidebar.replaceChildren(h("ul", {class: "nav flex-column"}, FILES.map((f) => h("li", {class: "nav-item"},
            h("a", {class: "nav-link" + (f === file ? " active" : ""), href: link(f)}, f.name),
            f === file && data ? h("ul", {class: "nav flex-column"}, [...data.operators.keys()].map((name) =>
                h("li", {class: "nav-item"}, h("a", {class: "nav-link", href: link(f, name), "data-operator": name}, name)))) : null,
        ))));
    }
    for (const a of sidebar.querySelectorAll("[data-operator]")) {
        a.classList.toggle("active", a.dataset.operator === operator);
        if (a.dataset.operator === operator) a.scrollIntoView({block: "nearest"});
    }
}

function renderHome() {
    document.title = "line-colors | Träwelling";
    renderSidebar(null);
    document.getElementById("dataset-list").replaceChildren(...FILES.map((f) =>
        h("a", {class: "dataset", href: link(f)}, h("strong", {}, f.name), h("span", {}, f.file))));
    view.hidden = true;
    home.hidden = false;
}

function renderFile(file, data) {
    document.title = file.name + " | line-colors";
    const results = h("div", {});
    const search = (query) => {
        const terms = query.toLowerCase().split(/\s+/).filter(Boolean);
        if (terms.length === 0) {
            results.replaceChildren(h("div", {class: "operator-list"}, [...data.operators].map(([name, lines]) =>
                h("a", {href: link(file, name)}, name, h("span", {}, lines.length)))));
            return;
        }
        const matches = data.lines.filter((line) => {
            const text = [line.shortOperatorName, line.lineName, line.lineName.replace(/\s+/g, "")].join(" ").toLowerCase();
            return terms.every((term) => text.includes(term));
        });
        results.replaceChildren(
            h("p", {class: "text-muted"}, matches.length > MAX_RESULTS
                ? `Showing ${MAX_RESULTS} of ${matches.length} matches`
                : `${matches.length} matches`),
            lineGrid(file, matches.slice(0, MAX_RESULTS), true),
        );
    };
    view.replaceChildren(
        h("h1", {}, file.name),
        h("p", {class: "text-muted"},
            h("a", {href: "https://github.com/Traewelling/line-colors/blob/main/" + file.file}, file.file),
            `: ${data.lines.length} lines, ${data.operators.size} operators`),
        h("input", {
            type: "search",
            class: "form-control search",
            placeholder: "Search operator or line",
            oninput: (event) => search(event.target.value),
        }),
        results,
    );
    search("");
}

function renderOperator(file, data, operator) {
    document.title = operator + " | " + file.name + " | line-colors";
    view.replaceChildren(
        h("p", {class: "text-muted"}, h("a", {href: link(file)}, file.name)),
        h("h1", {}, operator),
        lineGrid(file, data.operators.get(operator), false),
    );
}

async function route() {
    const hash = location.hash;
    const [fileId, operator] = hash.slice(1).split("/").map(decodeURIComponent);
    const file = FILES.find((f) => f.id === fileId);
    if (fileId && !file) {
        location.replace(link(FILES[0], fileId));
        return;
    }
    setSidebarOpen(false);
    window.scrollTo(0, 0);
    if (!file) {
        renderHome();
        return;
    }
    home.hidden = true;
    view.hidden = false;
    if (sidebarFile !== file) renderSidebar(file);
    view.replaceChildren(h("p", {class: "text-muted"}, "Loading " + file.file));

    let data;
    try {
        data = await load(file);
    } catch (error) {
        if (location.hash === hash) view.replaceChildren(h("p", {}, "Could not load " + file.file));
        return;
    }
    if (location.hash !== hash) return;

    renderSidebar(file, data, operator);
    if (operator && data.operators.has(operator)) renderOperator(file, data, operator);
    else renderFile(file, data);
}

sidebarToggler.addEventListener("click", () => setSidebarOpen(!sidebar.classList.contains("active")));
navToggler.addEventListener("click", () => {
    navToggler.classList.toggle("show");
    document.getElementById("header-nav").classList.toggle("show");
});
window.addEventListener("hashchange", route);
route();
