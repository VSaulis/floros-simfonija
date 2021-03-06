<?php

namespace App\Util;

use App\Entity\Article;
use App\Entity\BanquetHall;
use App\Entity\Gallery;
use App\Entity\Hotel;
use App\Entity\Location;
use App\Entity\Room;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BreadcrumbsHelper
{
    private $translator;
    private $router;

    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    public function getRoomBreadcrumbs(Location $location, Room $room, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'link' => $this->router->generate('location hotel', ['id' => $location->getId(), 'hotelId' => $room->getHotel()->getId()]),
                'title' => $room->getHotel()->getTitle($locale)
            ],
            [
                'link' => $this->router->generate('location hotel rooms', ['id' => $location->getId(), 'hotelId' => $room->getHotel()->getId()]),
                'title' => $this->translator->trans('titles.rooms')
            ],
            [
                'title' => $room->getTitle($locale)
            ]
        ];
    }

    public function getHotelBreadcrumbs(Location $location, Hotel $hotel, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $hotel->getTitle($locale)
            ]
        ];
    }

    public function getHotelRoomsBreadcrumbs(Location $location, Hotel $hotel, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'link' => $this->router->generate('location hotel', ['id' => $location->getId(), 'hotelId' => $hotel->getId()]),
                'title' => $hotel->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.rooms')
            ]
        ];
    }

    public function getHotelTermsAndConditionsBreadcrumbs(Location $location, Hotel $hotel, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'link' => $this->router->generate('location hotel', ['id' => $location->getId(), 'hotelId' => $hotel->getId()]),
                'title' => $hotel->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.terms_and_conditions')
            ]
        ];
    }

    public function getGalleriesBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.galleries')
            ]
        ];
    }

    public function getNewsBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.news')
            ]
        ];
    }

    public function getBanquetHallsBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.banquet_halls')
            ]
        ];
    }

    public function getBanquetHallBreadcrumbs(Location $location, BanquetHall $banquetHall, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'link' => $this->router->generate('location banquet halls', ['id' => $location->getId()]),
                'title' => $this->translator->trans('titles.banquet_halls')
            ],
            [
                'title' => $banquetHall->getTitle($locale)
            ]
        ];
    }

    public function getNewsArticleBreadcrumbs(Location $location, Article $article, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'link' => $this->router->generate('location news', ['id' => $location->getId()]),
                'title' => $this->translator->trans('titles.news')
            ],
            [
                'title' => $article->getTitle($locale)
            ]
        ];
    }

    public function getGalleryBreadcrumbs(Location $location, Gallery $gallery, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'link' => $this->router->generate('location galleries', ['id' => $location->getId()]),
                'title' => $this->translator->trans('titles.galleries')
            ],
            [
                'title' => $gallery->getTitle($locale)
            ]
        ];
    }

    public function getHomeBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.home')
            ],
        ];
    }

    public function getContactsBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.contacts')
            ]
        ];
    }

    public function getMenuBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('locations'),
                'title' => $this->translator->trans('titles.locations')
            ],
            [
                'link' => $this->router->generate('location home', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.menu')
            ]
        ];
    }
}