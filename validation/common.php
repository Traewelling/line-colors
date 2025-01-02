<?php

$lineColors = array_map("str_getcsv", file("../line-colors.csv", FILE_SKIP_EMPTY_LINES));
$keys = array_shift($lineColors);
foreach ($lineColors as $i => $row) {
    $lineColors[$i] = array_combine($keys, $row);
}

$productSources = array_map("str_getcsv", file("../product-categories.csv", FILE_SKIP_EMPTY_LINES));
$productKeys = array_shift($productSources);
foreach ($productSources as $i => $row) {
    $productSources[$i] = array_combine($productKeys, $row);
}

$linesByOperatorCode = array_reduce($lineColors, function ($result, $line) {
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
