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
- `borderColor` Color-Hexcode for the border of the shape
- `shape`: Specifies the shape of the icon --> see examples below
  - `rectangle`: Just a rectangle
  - `pill`: Rectangle with completely rounded corners
  - `rectangle-rounded-corner`: Rectangle with rounded corners
  - `trapezoid` A trapezoid shape with a broad top and a narrow bottom side
  - `hexagon` A pill with pointy tips
- `wikidataQid`: Wikidata QID for the line (if available, can be empty)

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

**optionally but recommended: Wikidata ([why?](https://github.com/Traewelling/line-colors/issues/91))**

- try to find the Wikidata QID for the line and add it to the `wikidataQid` column
    - You can use [this list](https://www.wikidata.org/wiki/User:Mkkagain/Verkehrslinien_in_Deutschland) to find the QID
- if there is no Wikidata item for the line, it would be great if you could [create one](https://www.wikidata.org/wiki/Special:NewItem)

## Examples

### Die Länderbahn GmbH DLB, RE72

<img src="examples/alex-dlb-re23.png" alt="RE 23" width="100">
<br>

- `shortOperatorName`: alex-dlb
- `lineName`: RE 23
- `hafasOperatorCode`: alex-die-landerbahn-gmbh-dlb
- `hafasLineId`: re23
- `backgroundColor`: #ffffff
- `textColor`: #006666
- `borderColor` #006666
- `shape`: rectangle
- `wikidataQid` Q130542294

Entry: `alex-dlb,RE 23,alex-die-landerbahn-gmbh-dlb,re23,#ffffff,#006666,#006666,rectangle,Q130542294`

### DB Regio AG S-Bahn München, S7
<img src="examples/sbm-s7.png" alt="S7" width="100">
<br>

- `shortOperatorName`: mvv-db-sbm
- `lineName`: S7
- `hafasOperatorCode`: db-regio-ag-s-bahn-munchen
- `hafasLineId`: 4-800725-7
- `backgroundColor`: #8a372f
- `textColor`: #ffffff
- `borderColor` *does not apply*
- `shape`: pill
- `wikidataQid` *not available*

Entry: `mvv-db-sbm,S7,db-regio-ag-s-bahn-munchen,4-800725-7,#8a372f,#ffffff,,pill,`

### KVV: Albtal-Verkehrs-Gesellschaft mbH, S1

<img src="examples/kvv-s1.png" alt="S1" width="100">
<br>

- `shortOperatorName`: kvv-avg
- `lineName`: S1
- `hafasOperatorCode`: albtal-verkehrs-gesellschaft-mbh
- `hafasLineId`: 4-a6s8-8
- `backgroundColor`: #6e692a
- `textColor`: #ffffff
- `borderColor` *does not apply*
- `shape`: rectangle-rounded-corner (a pill with less rounded corners)
- `wikidataQid` *not available*

Entry: `kvv-avg,S8,albtal-verkehrs-gesellschaft-mbh,4-a6s8-8,#6e692a,#ffffff,,rectangle-rounded-corner,`

### HVV: Hadag, 62

<img src="examples/hvv-62.png" alt="62" width="100">
<br>

- `shortOperatorName`: hvv-had
- `lineName`: 62
- `hafasOperatorCode`: *not available*
- `hafasLineId`: 6-hvvhad-62
- `backgroundColor`: #009bb6
- `textColor`: #ffffff
- `borderColor` *does not apply*
- `shape`: trapezoid (a shape with a broad top and a narrow bottom side)
- `wikidataQid` *not available*

Entry: `hvv-had,62,,6-hvvhad-62,#009bb6,#ffffff,,trapezoid,`

#### HVV: Hamburger Hochbahn AG, X35

<img src="examples/hvv-x35.png" alt="62" width="100">
<br>

- `shortOperatorName`: hvv-hha
- `lineName`: X35
- `hafasOperatorCode`: *not available*
- `hafasLineId`: 5-hvvhha-x35
- `backgroundColor`: #eb452e
- `textColor`: #ffffff
- `borderColor` *does not apply*
- `shape`: hexagon (a pill with pointy tips)
- `wikidataQid` *not available*

Entry: `hvv-hha,X35,,5-hvvhha-x35,#eb452e,#ffffff,,hexagon,`
