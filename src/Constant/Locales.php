<?php

namespace App\Constant;

use ReflectionClass;

abstract class Locales
{
    public const LT = 'lt';
    public const EN = 'en';
    public const RU = 'ru';

    public static function contains($locale)
    {
        $locales = (new ReflectionClass(Locales::class))->getConstants();
        foreach ($locales as $name => $value) if ($value == $locale) return $name;
        return null;
    }

    public static function list(): array
    {
        return [self::LT, self::EN, self::RU];
    }
}