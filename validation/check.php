<?php

include_once "./csv_loader.php";

function run_checks(array $csv) {

    echo "Checking for lower-case content" . PHP_EOL;
    $i = 2;
    foreach ($csv as $line) {
        $lowercase_keys = ["shortOperatorName", "hafasOperatorCode", "hafasLineId", "backgroundColor", "textColor", "shape"];
        foreach ($lowercase_keys as $key) {
            if (isset($line[$key]) && $line[$key] !== strtolower($line[$key])) {
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
        valid_shape($line, $i);
        $i++;
    }

    echo "Checking that all colors are valid" . PHP_EOL;
    $i = 2;
    foreach ($csv as $line) {
        if ($line["borderColor"] != "") {
            valid_hex_color($line, $i, "borderColor");
        }

        valid_hex_color($line, $i, "textColor");
        valid_hex_color($line, $i, "backgroundColor");

        $i++;
    }

    echo "Checking that the background color isn't the text color" . PHP_EOL;
    $i = 2;
    foreach ($csv as $line) {
        text_color_differs_background($line, $i);
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
}

echo "Checking line-colors.csv" . PHP_EOL;
run_checks(load_csv("../line-colors.csv"));

echo "Checking line-colors-CH.csv" . PHP_EOL;
run_checks(load_csv("../line-colors-CH.csv"));

echo "Checking merged CSVs" . PHP_EOL;
run_checks(load_all_csvs());
