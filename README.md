# Public transport line colors
This repository is made for collecting line colors in public transport lines,
so they can be displayed on systems using DB HAFAS.

## Structure

The `line-colors.csv` contains several columns:
- `shortOperatorName`: Short operator name (i.e. vehicle keeper marking/"Halterkürzel" or another identifier for EVU)
- `lineName`: Displayed line name
- `hafasOperatorCode`: used to identify the correct line, if line id is not distinct. Can be empty!
- `hafasLineId`: identifies the HAFAS line
- `backgroundColor`: Color-Hexcode for the display background color
- `textColor`: Color-Hexcode for the text color
- `rounded`: Specifies whether the line icon has rounded ends or not

## Contributing

I'd be very happy if this file could expand very fast, so please feel free to add more lines by opening a PR. <br>

**BUT FIRST**

- Check that entries are sorted first by `shortOperatorName` and then `lineName`
- Add a source where data can be proved (somewhere from the internet, like a timetable PDF...)
- All entries in the CSV (except `lineName`) shall be in lower case

## Examples

### Go-Ahead Bayern GmbH,  RE72

<img src="examples/gaby-re72.png" alt="RE72" width=100>
<br><br>

- `shortOperatorName`: gaby
- `lineName`: RE72
- `hafasOperatorCode`: go-ahead-bayern-gmbh
- `hafasLineId`: re-72
- `backgroundColor`: #ef7c00
- `textColor`: #ffffff
- `rounded`: false

Entry: `gaby;RE72;go-ahead-bayern-gmbh;re-72;#ef7c00;#ffffff;false`

### DB Regio AG S-Bahn München, S7
<img src="examples/sbm-s7.png" alt="S7" width=100>
<br><br>

- `shortOperatorName`: db-sbm
- `lineName`: S7
- `hafasOperatorCode`: db-regio-ag-s-bahn-munchen
- `hafasLineId`: 4-800725-7
- `backgroundColor`: #8a372f
- `textColor`: #ffffff
- `rounded`: true

Entry: `db-sbm;S7;db-regio-ag-s-bahn-munchen;4-800725-7;#8a372f;#ffffff;true`
