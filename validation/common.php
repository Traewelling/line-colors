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
