#!/usr/bin/env python3

import csv
import os

with open('hafas-operators.csv') as file:
    operators = csv.reader(file, delimiter=',')
    for [id, name] in operators:
        prefix = id[0:2]
        path = f"./operators/{prefix}"
        if not os.path.exists(path):
            os.mkdir(path)

        operatorFile = open(f"./operators/{prefix}/{id}.nix", "w")
        operatorFile.write(f"""{{
  name = "{name}";
  hafas-id = "{id}";
}}
""")
        operatorFile.close()
