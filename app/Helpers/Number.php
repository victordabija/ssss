<?php

namespace App\Helpers;

class Number
{
    public static function romanicToArabic(string $roman): int
    {
        return match ($roman) {
            'I' => 1,
            'II' => 2,
            'III' => 3,
            'IV' => 4,
        };
    }
}
