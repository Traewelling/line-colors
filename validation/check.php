<?php

include_once "./common.php";


echo "Checking for lower-case content" . PHP_EOL;
$i = 2;
foreach ($csv as $line) {
    $lowercase_keys = ["shortOperatorName", "hafasOperatorCode", "hafasLineId", "backgroundColor", "textColor", "shape"];
    foreach ($lowercase_keys as $key) {
        if ($line[$key] !== strtolower($line[$key])) {
            throw new Error("$key is not lowercase in row $i");
        }
    }
    $i++;
}

echo "Checking for ordering by shortOperatorName" . PHP_EOL;
$last_op = "";
$i = 2;
foreach ($csv as $line) {
    if ($last_op > $line["shortOperatorName"]) {
        throw new Error($last_op . " should be after " . $line["shortOperatorName"] . " in row $i");
    }
    $i++;

    $last_op = $line["shortOperatorName"];
}

echo "Checking that all shapes are valid and can be displayed on the website" . PHP_EOL;
$i = 2;
foreach ($csv as $line) {
    if (!in_array($line["shape"], ["pill", "rectangle", "rectangle-rounded-corner"])) {
        throw new Error("bad shape " . $line["shape"] . " in row $i");
    }
    $i++;
}

echo "Checking that all colors are valid" . PHP_EOL;

function valid_hex_color($line, $i, $key) {
    $color = $line[$key];
    
    if (!(strlen($color) == 7 && ctype_xdigit(substr($color, 1)) && $color[0] === "#")) {
        throw new Error("bad $key \"$color\" does not follow #<6 digit hex color> in row $i");
    }
}

$i = 2;
foreach ($csv as $line) {
    if ($line["borderColor"] != "") {
        valid_hex_color($line, $i, "borderColor");
    }

    valid_hex_color($line, $i, "textColor");
    valid_hex_color($line, $i, "backgroundColor");

    $i++;
}

$sources = json_decode(file_get_contents("../sources.json"), true);
$opSources = array_map(fn($op) => $op["shortOperatorName"], $sources);
echo "Checking that operators are present in sources.json" . PHP_EOL;
$i = 2;
foreach ($csv as $line) {
    if (!in_array($line["shortOperatorName"], $opSources)) {
        throw new Error("unknown operator " . $line["shortOperatorName"] . " in row $i");
    }
    $i++;
}


echo "...Checked $i rows" . PHP_EOL;
