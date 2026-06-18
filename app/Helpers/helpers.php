<?php

if (!function_exists('convertRupiahToString')) {
    function convertRupiahToString(int $number): string
    {
        if ($number >= 1_000_000_000) {
            $result = $number / 1_000_000_000;
            return number_format($result, $result == floor($result) ? 0 : 1, ',', '.') . 'M';
        }
        if ($number >= 1_000_000) {
            $result = $number / 1_000_000;
            return number_format($result, $result == floor($result) ? 0 : 1, ',', '.') . 'jt';
        }
        if ($number >= 1_000) {
            $result = $number / 1_000;
            return number_format($result, $result == floor($result) ? 0 : 1, ',', '.') . 'rb';
        }
        return number_format($number, 0, ',', '.');
    }
}
