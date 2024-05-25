# Public transport line colors
This repository is made for collecting line colors in public transport lines,
so they can be displayed on systems using DB HAFAS.

## Structure

The `line-colors.csv` contains several columns:
- `shortOperatorName`: Short operator name (i.e. vehicle keeper marking/"Halterkürzel" or another identifier for EVU) and a local transport network abbreviation
- `lineName`: Displayed line name
- `hafasOperatorCode`: used to identify the correct line, if line id is not distinct. Can be empty!
- `hafasLineId`: identifies the HAFAS line - you can get this by requesting a departure for the line from HAFAS (i.e. using [db-rest playground](https://petstore.swagger.io/?url=https%3A%2F%2Fv6.db.transport.rest%2F.well-known%2Fservice-desc%0A))
- `backgroundColor`: Color-Hexcode for the display background color
- `textColor`: Color-Hexcode for the text color
- `shape`: Specifies the shape of the icon --> see examples below
  - `rectangle`: Just a rectangle
  - `pill`: Rectangle with completely rounded corners
  - `rectangle-rounded-corner`: Rectangle with rounded corners
  - `trapezoid`
  - `hexagon` (not yet supported)

## Contributing

If a line operates in a local transport network/"Verkehrsverbund", the network's line color shall be preferred.<br>
Local transport networks usually have line colors for:

- suburban lines / "S-Bahn"
- subway lines / "U-Bahn"
- tramway lines / "Straßenbahn, Stadtbahn"
- bus lines / "Bus"

If a single line operates in **multiple** transport networks, the color communicated by the operator shall be preferred.

I'd be very happy if this file could expand very fast, so please feel free to add more lines by opening a PR. <br>
Please keep the PR's small. If possible, create a small PR for each operator. <br>

**BUT FIRST**

- Check that entries are sorted first by `shortOperatorName` and then `lineName`
- Reference a source in your PR where data can be proved (somewhere from the internet, like a timetable PDF...)
    - Also add it to `sources.json`.
- All entries in the CSV (except `lineName`) shall be in lower case

**THEN**
- Please checkout at the latest commit of the `main` branch and create a new branch from there
- Ensure that the validation tests (after pr is created) pass

## Examples

### Go-Ahead Bayern GmbH, RE72

<img src="examples/gaby-re72.png" alt="RE72" width="100">
<br>

- `shortOperatorName`: gaby
- `lineName`: RE72
- `hafasOperatorCode`: go-ahead-bayern-gmbh
- `hafasLineId`: re-72
- `backgroundColor`: #ef7c00
- `textColor`: #ffffff
- `shape`: rectangle

Entry: `gaby,RE72,go-ahead-bayern-gmbh,re-72,#ef7c00,#ffffff,rectangle`

### DB Regio AG S-Bahn München, S7
<img src="examples/sbm-s7.png" alt="S7" width="100">
<br>

- `shortOperatorName`: mvv-db-sbm
- `lineName`: S7
- `hafasOperatorCode`: db-regio-ag-s-bahn-munchen
- `hafasLineId`: 4-800725-7
- `backgroundColor`: #8a372f
- `textColor`: #ffffff
- `shape`: pill

Entry: `mvv-db-sbm,S7,db-regio-ag-s-bahn-munchen,4-800725-7,#8a372f,#ffffff,pill`

### KVV: Albtal-Verkehrs-Gesellschaft mbH

<img src="examples/kvv-s1.png" alt="S1" width="100">
<br>

- `shortOperatorName`: kvv-avg
- `lineName`: S1
- `hafasOperatorCode`: albtal-verkehrs-gesellschaft-mbh
- `hafasLineId`: 4-a6s8-8
- `backgroundColor`: #6e692a
- `textColor`: #ffffff
- `shape`: rectangle-rounded-corner (a pill with less rounded corners)

### Other shapes:

#### Trapezoid

<img src="examples/hvv-64.png" alt="64" width="100">

#### Hexagon

<img src="examples/hvv-112.png" alt="112" width="100">
