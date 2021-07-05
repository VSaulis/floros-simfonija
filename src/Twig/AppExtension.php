<?php

namespace App\Twig;

use App\Util\PriceUtils;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getPagingRoute', [$this, 'getPagingRoute'])
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('priceFormat', [$this, 'priceFormat']),
            new TwigFilter('dateTimeFormat', [$this, 'dateTimeFormat']),
            new TwigFilter('dateFormat', [$this, 'dateFormat']),
        ];
    }

    public function priceFormat($price): string
    {
        return PriceUtils::formatPrice($price);
    }

    public function dateTimeFormat(DateTime $dateTime = null): string
    {
        if ($dateTime) return $dateTime->format('Y-m-d H:i:s');
        return "None";
    }

    public function dateFormat(DateTime $dateTime = null): string
    {
        if ($dateTime) return $dateTime->format('Y-m-d');
        return "None";
    }

    public function getPagingRoute(int $page, Request $request): string
    {
        $params = array_merge($request->attributes->get('_route_params'), $request->query->all());
        $params['page'] = $page;
        return $this->router->generate($request->get('_route'), $params);
    }
}