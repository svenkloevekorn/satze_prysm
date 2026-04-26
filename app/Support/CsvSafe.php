<?php

namespace App\Support;

class CsvSafe
{
    /**
     * Schuetzt vor CSV-Formel-Injection (Excel/LibreOffice/Numbers).
     *
     * Wenn ein Zellwert mit =, +, -, @, TAB oder CR beginnt, wuerde das
     * Spreadsheet-Programm das als Formel werten. Beispiel:
     *   "=cmd|'/c calc'!A1" als Produktname -> Excel fuehrt aus.
     *
     * Wir prefixen solche Werte mit einem einfachen Apostroph,
     * was das Spreadsheet als Text-Marker erkennt und nicht ausfuehrt.
     */
    public static function value(mixed $value): mixed
    {
        if (! is_string($value) || $value === '') {
            return $value;
        }

        $first = $value[0];

        if (in_array($first, ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$value;
        }

        return $value;
    }
}
