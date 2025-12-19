<?php

// read DELFI colours
$csv = array_map("str_getcsv", file("../line-colors.csv", FILE_SKIP_EMPTY_LINES));
$keys = array_shift($csv);
foreach ($csv as $i => $row) {
    $row = array_pad($row, count($keys), null);
    $row = array_slice($row, 0, count($keys));
    $csv[$i] = array_combine($keys, $row);
}

// read Swiss colours
$csv_CH = array_map("str_getcsv", file("../line-colors-CH.csv", FILE_SKIP_EMPTY_LINES));
$keys_CH = array_shift($csv_CH);
foreach ($csv_CH as $i => $row) {
    $row = array_pad($row, count($keys_CH), null);
    $row = array_slice($row, 0, count($keys_CH));
    $csv_CH[$i] = array_combine($keys_CH, $row);
}

// combine both for this PHP thing
$csv = array_merge ($csv, $csv_CH);      // This should merge both CSVs without the need of touching the code below, I hope.
$keys = array_merge ($keys, $keys_CH);


$linesByOperatorCode = array_reduce($csv, function ($result, $line) {
    $result[$line["shortOperatorName"]][] = $line;

    return $result;
}, []);


function valid_shape($line, $i) {
    if (!in_array($line["shape"], ["circle", "hexagon", "pill", "rectangle", "rectangle-rounded-corner", "trapezoid"])) {
        throw new Error("bad shape " . $line["shape"] . " in row $i");
    }
}

function valid_hex_color($line, $i, $key) {
    $color = $line[$key];

    if (!(strlen($color) == 7 && ctype_xdigit(substr($color, 1)) && $color[0] === "#")) {
        throw new Error("bad $key \"$color\" does not follow #<6 digit hex color> in row $i");
    }
}

function text_color_differs_background($line, $i) {
    $textColor = $line["textColor"];
    $backgroundColor = $line["backgroundColor"];
    if ($textColor === $backgroundColor) {
        throw new Error("bad color combination: text color \"$textColor\" may not be background color \"$backgroundColor\" in row $i");
    }
}
