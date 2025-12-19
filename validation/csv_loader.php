<?php

require_once __DIR__ . '/common.php';

function load_csv(string $path): array
{
    $csv = array_map("str_getcsv", file($path, FILE_SKIP_EMPTY_LINES));
    $keys = array_shift($csv);

    foreach ($csv as $i => $row) {
        $row = array_pad($row, count($keys), null);
        $row = array_slice($row, 0, count($keys));
        $csv[$i] = array_combine($keys, $row);
    }

    return $csv;
}

function load_all_csvs(): array
{
    $csv = array_merge(
        load_csv("../line-colors.csv"),
        load_csv("../line-colors-CH.csv")
    );

    usort($csv, function ($a, $b) {
        return strcmp(
            $a['shortOperatorName'] ?? '',
            $b['shortOperatorName'] ?? ''
        );
    });

    return $csv;
}
