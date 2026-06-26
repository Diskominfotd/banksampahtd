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
if (!function_exists('convertBeratToString')) {
    function convertBeratToString(float $kg): string
    {
        if ($kg >= 1_000) {
            $result = $kg / 1_000;
            return number_format($result, $result == floor($result) ? 0 : 1, ',', '.') . ' ton';
        }
        return number_format($kg, $kg == floor($kg) ? 0 : 1, ',', '.') . ' kg';
    }
}
