<?php

include_once "./common.php";

echo "Checking for ordering by productCategory" . PHP_EOL;
$last_brand = "";
$i = 2;
foreach ($productSources as $brand) {
    if ($last_brand > $brand["productCategory"]) {
        throw new Error($last_brand . " should be after " . $brand["productCategory"] . " in row $i");
    }
    $i++;

    $last_brand = $brand["productCategory"];
}

echo "Checking for invalid productCategory entries in line-colors.csv" . PHP_EOL;
$brandNames = array_map(fn($brand) => $brand["productCategory"], $productSources);
foreach ($lineColors as $line) {
    if ($line["productCategory"] != '' && !in_array($line["productCategory"], $brandNames)) {
        throw new Error("Invalid productCategory reference " . $line["productCategory"] . " for operator " . $line["shortOperatorName"]);
    }
}

$sources = json_decode(file_get_contents("../icon-sources.json"), true);
$brandSources = array_map(fn($op) => $op["iconName"], $sources);
echo "Checking that manual icon overrides are present in icon-sources.json" . PHP_EOL;
$i = 2;
foreach ($productSources as $brand) {
    if ($brand["iconName"] != '' && !in_array($brand["iconName"], $brandSources)) {
        throw new Error("missing icon source for " . $brand["iconName"] . " in row $i");
    }
    $i++;
}

echo "...Checked $i rows" . PHP_EOL;
