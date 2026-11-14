import csv
import json
import os
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent

DELFI_HEADER = ["shortOperatorName", "lineName", "hafasOperatorCode", "hafasLineId", "backgroundColor", "textColor",
                "borderColor", "shape", "wikidataQid", "delfiAgencyID", "delfiAgencyName"]
GTFS_HEADER = ["shortOperatorName", "lineName", "backgroundColor", "textColor", "borderColor", "shape", "wikidataQid",
               "GTFSAgencyID", "GTFSAgencyName"]

SHAPES = {"circle", "hexagon", "pill", "rectangle", "rectangle-rounded-corner", "trapezoid"}
LOWERCASE = ["shortOperatorName", "hafasOperatorCode", "hafasLineId"]
HEX_COLOR = re.compile(r"#[0-9a-f]{6}")
QID = re.compile(r"Q[1-9][0-9]*")

errors = 0


def report(level, file, line, message):
    global errors
    if level == "error":
        errors += 1
    if os.environ.get("GITHUB_ACTIONS"):
        message = message.replace("%", "%25").replace("\r", "%0D").replace("\n", "%0A")
        location = f"file={file},line={line}" if line else f"file={file}"
        print(f"::{level} {location}::{message}")
    else:
        location = f"{file}:{line}" if line else file
        print(f"{location}: {level}: {message}")


def load_operators():
    try:
        sources = json.loads((ROOT / "sources.json").read_text(encoding="utf-8"))
    except json.JSONDecodeError as e:
        report("error", "sources.json", e.lineno, f"invalid JSON: {e.msg}")
        return None

    operators = set()
    for entry in sources:
        operator = entry.get("shortOperatorName")
        if not operator:
            report("error", "sources.json", None, "entry without shortOperatorName")
        elif operator in operators:
            report("error", "sources.json", None, f"shortOperatorName '{operator}' is listed more than once")
        operators.add(operator)
    return operators


def check_row(entry, operators):
    for key, value in entry.items():
        if value != value.strip():
            yield f"{key} has leading or trailing whitespace"

    for key in ("shortOperatorName", "lineName"):
        if not entry[key]:
            yield f"{key} is empty"

    for key in LOWERCASE:
        if key in entry and entry[key] != entry[key].lower():
            yield f"{key} '{entry[key]}' is not lowercase"

    if entry["shape"] not in SHAPES:
        yield f"shape '{entry['shape']}' is not one of {', '.join(sorted(SHAPES))}"

    for key in ("backgroundColor", "textColor", "borderColor"):
        value = entry[key]
        if (value or key != "borderColor") and not HEX_COLOR.fullmatch(value):
            yield f"{key} '{value}' is not a lowercase hex color like #1a2b3c"

    if entry["textColor"] == entry["backgroundColor"]:
        yield f"textColor and backgroundColor are both '{entry['textColor']}'"

    if entry["wikidataQid"] and not QID.fullmatch(entry["wikidataQid"]):
        yield f"wikidataQid '{entry['wikidataQid']}' is not like Q12345"

    if operators is not None and entry["shortOperatorName"] not in operators:
        yield f"shortOperatorName '{entry['shortOperatorName']}' is missing in sources.json"


def check_csv(path, header, operators):
    name = path.name
    agency_column = header[-1]
    with path.open(newline="", encoding="utf-8") as file:
        reader = csv.reader(file)
        if next(reader, []) != header:
            report("error", name, 1, f"header must be {','.join(header)}")
            return 0

        rows = 0
        previous_operator = ""
        seen = {}
        for row in reader:
            rows += 1
            line = reader.line_num
            if len(row) != len(header):
                report("error", name, line, f"expected {len(header)} fields, found {len(row)}")
                continue

            entry = dict(zip(header, row))
            for message in check_row(entry, operators):
                report("error", name, line, message)

            operator = entry["shortOperatorName"]
            if operator < previous_operator:
                report("error", name, line, f"'{operator}' must be sorted before '{previous_operator}'")
            previous_operator = operator

            if entry[agency_column]:
                key = (entry[agency_column], entry["lineName"].replace(" ", ""))
                style = (entry["backgroundColor"], entry["textColor"], entry["borderColor"], entry["shape"])
                first_line, first_style = seen.setdefault(key, (line, style))
                if style != first_style:
                    report("warning", name, line,
                           f"same {agency_column} and lineName as line {first_line}, but different colors or shape")
    return rows


def main():
    operators = load_operators()
    for path in sorted(ROOT.glob("line-colors*.csv")):
        header = DELFI_HEADER if path.name == "line-colors.csv" else GTFS_HEADER
        rows = check_csv(path, header, operators)
        print(f"{path.name}: checked {rows} rows")
    if errors:
        print(f"{errors} errors")
        sys.exit(1)


main()
