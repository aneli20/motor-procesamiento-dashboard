<?php

namespace App\Support;

final class Money
{
    private function __construct() {}

    public static function decimalToCents(string|int|float $amount): int
    {
        $normalized = trim((string) $amount);
        $negative = str_starts_with($normalized, '-');
        $normalized = ltrim($normalized, '+-');
        [$whole, $decimal] = array_pad(explode('.', $normalized, 2), 2, '00');
        $decimal = str_pad(substr($decimal, 0, 2), 2, '0');
        $cents = ((int) $whole * 100) + (int) $decimal;

        return $negative ? -$cents : $cents;
    }

    public static function centsToDecimal(int $cents): string
    {
        $negative = $cents < 0;
        $absolute = abs($cents);
        $whole = intdiv($absolute, 100);
        $decimal = str_pad((string) ($absolute % 100), 2, '0', STR_PAD_LEFT);

        return ($negative ? '-' : '').$whole.'.'.$decimal;
    }

    public static function addPercent(int $cents, int $percent): int
    {
        return intdiv($cents * (100 + $percent) + 50, 100);
    }
}
