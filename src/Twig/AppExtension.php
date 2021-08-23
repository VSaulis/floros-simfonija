<?php

namespace App\Twig;

use App\Repository\LocationRepository;
use App\Repository\RoomCategoryRepository;
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
    private $locationRepository;
    private $roomCategoryRepository;

    public function __construct(UrlGeneratorInterface $router, LocationRepository $locationRepository, RoomCategoryRepository $roomCategoryRepository)
    {
        $this->roomCategoryRepository = $roomCategoryRepository;
        $this->locationRepository = $locationRepository;
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getPagingRoute', [$this, 'getPagingRoute']),
            new TwigFunction('getAllLocations', [$this, 'getAllLocations']),
            new TwigFunction('getAllRoomCategories', [$this, 'getAllRoomCategories'])
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

    public function getAllLocations(): array
    {
        return $this->locationRepository->findAll();
    }

    public function getAllRoomCategories(): array
    {
        return $this->roomCategoryRepository->findAll();
    }
}