<?php

/**
 * This file tests whether the utility functions in common.php work as
 * intended. For this, positive and negative test cases for all the checks are
 * given. Instead of blowing up a large unit testing framework, two functions
 * `test_ok` and `test_throws` are given which will check whether a given
 * Callable will blow up or not.
 */

$positive_count = 0;
function test_ok(string $name, callable $test_case) {
    global $positive_count;
    $positive_count++;

    try {
        $test_case();
    } catch (Error $e) {
        throw new Error("$name should not throw, but did.", previous: $e);
    }
}


$negative_count = 0;
/**
 * This can only catch one error! Don't call `test_throws` with multiple
 * validation calls as it might taint the test results!
 */
function test_throws(string $name, callable $test_case) {
    global $negative_count;
    $negative_count++;

    $has_thrown = false;
    try {
        $test_case();
    } catch (Error $e) {
        $has_thrown = true;
    } finally {
        if (!$has_thrown) {
            throw new Error("$name should have thrown, but did not.");
        }
    }
}

/**
 * Loading testable functions from common.php.
 */
include_once "common.php";
echo "Testing validation functions with synthetic data" . PHP_EOL;

/**
 * valid_shape
 */
test_ok("valid_shape for okay data", function () {
    valid_shape(["shape" => "pill"], 1);
    valid_shape(["shape" => "rectangle"], 2);
    valid_shape(["shape" => "rectangle-rounded-corner"], 3);
});
test_throws("valid_shape for unknown data", function () {
    valid_shape(["shape" => "bestagon"], 4);
});
test_throws("valid_shape for empty data", function () {
    valid_shape(["shape" => ""], 5);
});

/**
 * valid_hex_color
 */
test_ok("valid_hex_color for okay data", function () {
    valid_hex_color(["test_key" => "#fff000"], 1, "test_key");
    valid_hex_color(["test_key" => "#fff000"], 2, "test_key");
});
test_throws("valid_hex_color for non-hex data", function () {
    valid_hex_color(["test_key" => "#ghijkl"], 3, "test_key");
});
test_throws("valid_hex_color for too short data", function () {
    valid_hex_color(["test_key" => "#abc"], 4, "test_key");
});
test_throws("valid_hex_color for too long data", function () {
    valid_hex_color(["test_key" => "#1234567"], 5, "test_key");
});
test_throws("valid_hex_color for empty data", function () {
    valid_hex_color(["test_key" => ""], 6, "test_key");
});
test_throws("valid_hex_color when doesn't start with #", function () {
    valid_hex_color(["test_key" => "abcdefg"], 5, "test_key");
});

/**
 * text_color_differs_background
 */
test_ok("text_color_differs_background for different colors", function() {
    text_color_differs_background(["textColor" => "#ffffff", "backgroundColor" => "#000000"], 1);
});
test_throws("text_color_differs_background with the same data", function() {
    $black = "#000000";
    text_color_differs_background(["textColor" => $black, "backgroundColor" => $black], 2);
});

/**
 * End of file
 */
echo "...Tested $positive_count positive and $negative_count negative validation functions" . PHP_EOL;
