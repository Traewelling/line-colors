#!/usr/bin/env python3

import csv
import os

with open("line-colors.csv") as file:
    data = csv.reader(file, delimiter=',')
    next(data)
    dict = dict()
    for [short, lineName, operator, lineId, backgroundColor, textColor, shape] in data:
        if not (short in dict):
            dict[short] = []
        dict[short].append(
            {
              'short': short,
              'lineName': lineName,
              'operator': operator,
              'lineId': lineId,
              'backgroundColor': backgroundColor,
              'textColor': textColor,
              'shape': shape
            })
    for key in dict:
        entry = dict[key][0]
        path = f"./lines/{key}.nix"
        with open(path, "w") as nixFile:
            if entry["operator"] == "":
                nixContent = f"""{{
  operator = null;
  lines = ["""         
            else:
                nixContent = f"""{{operators,...}}: {{
  operator = operators.{entry["operator"]};
  lines = ["""         

            for entry in dict[key]:
                nixContent += f"""
    {{
      name = "{entry["lineName"]}";
      hafas-id = "{entry["lineId"]}";
      backgroundColor = "{entry["backgroundColor"]}";
      textColor = "{entry["textColor"]}";
      shape = "{entry["shape"]}";
    }}"""
            nixContent += """
  ];
}
"""
            nixFile.write(nixContent)
