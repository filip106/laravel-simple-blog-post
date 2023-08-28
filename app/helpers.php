<?php

if (!function_exists('trim_characters')) {
    function trim_characters(string $expression): string
    {
        return strlen($expression) > 50 ? substr($expression, 0, 40) . "..." : $expression;
    }
}
