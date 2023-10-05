<?php

$csv = array_map("str_getcsv", file("../line-colors.csv", FILE_SKIP_EMPTY_LINES));
$keys = array_shift($csv);
foreach ($csv as $i => $row) {
    $csv[$i] = array_combine($keys, $row);
}

$linesByOperatorCode = array_reduce($csv, function ($result, $line) {
    $result[$line["shortOperatorName"]][] = $line;

    return $result;
}, []);


function valid_shape($line, $i) {
    if (!in_array($line["shape"], ["pill", "rectangle", "rectangle-rounded-corner"])) {
        throw new Error("bad shape " . $line["shape"] . " in row $i");
    }
}

function valid_hex_color($line, $i, $key) {
    $color = $line[$key];
    
    if (!(strlen($color) == 7 && ctype_xdigit(substr($color, 1)) && $color[0] === "#")) {
        throw new Error("bad $key \"$color\" does not follow #<6 digit hex color> in row $i");
    }
}