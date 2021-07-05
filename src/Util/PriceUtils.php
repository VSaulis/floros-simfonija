<?php

namespace App\Util;

use Symfony\Component\Intl\Currencies;

class PriceUtils
{
    public static function formatPrice(?float $price = 0, string $currency = "EUR"): string
    {
        return sprintf('%s %s', number_format($price, 2), Currencies::getSymbol($currency));
    }
}
