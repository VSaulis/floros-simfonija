<?php

namespace App\Util;

use DateTime;

class DateUtils
{
    public static function formatDateTime($value)
    {
        if (!$value) return null;
        return date_format(new DateTime($value), 'Y-m-d H:i:s');
    }

    public static function formatDate($value): ?string
    {
        if (!$value) return null;

        if ($value instanceof DateTime) {
            return $value->format('Y-m-d');
        }

        return (new DateTime($value))->format('Y-m-d');
    }
}