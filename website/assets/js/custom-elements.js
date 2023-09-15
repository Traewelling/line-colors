function toggleDetails(event) {
    const classBool = "details-shown";

    const isCurrentlyActive = document.getElementById("main-wrapper").classList.contains(classBool);

    let target = document.getElementById("details-toggler");
    if (isCurrentlyActive) {
        document.getElementById("main-wrapper").classList.remove(classBool);
        target.textContent = "Show details";

    } else {
        document.getElementById("main-wrapper").classList.add(classBool);
        target.textContent = "Hide details";
    }
}

["pill", "rectangle", "rectangle-rounded-corner"].forEach((shape) =>
    customElements.define("line-logo-" + shape, class extends HTMLElement {
        constructor() {
            super();
            let template = document.getElementById("template-for-line-logo-" + shape);
            let templateContent = template.content;

            const shadowRoot = this.attachShadow({mode: "open"});

            const backgroundColor = this.getAttribute("backgroundColor");
            const textColor = this.getAttribute("textColor");

            let style = document.createElement("style");
            style.textContent = `div { background-color: ${backgroundColor}; color: ${textColor}}; }`;

            shadowRoot.appendChild(style);

            shadowRoot.appendChild(templateContent.cloneNode(true));
        }
    })
);
