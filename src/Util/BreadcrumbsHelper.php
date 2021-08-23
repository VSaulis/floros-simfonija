<?php

namespace App\Util;

use App\Entity\Gallery;
use App\Entity\Location;
use App\Entity\Room;
use App\Entity\RoomCategory;
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
                'link' => $this->router->generate('home'),
                'title' => $this->translator->trans('titles.home')
            ],
            [
                'link' => $this->router->generate('location about us', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'link' => $this->router->generate('location room category', ['id' => $location->getId(), 'categoryId' => $room->getCategory()->getId()]),
                'title' => $room->getCategory()->getTitle($locale)
            ],
            [
                'title' => $room->getTitle($locale)
            ]
        ];
    }

    public function getRoomsBreadcrumbs(Location $location, RoomCategory $category, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('home'),
                'title' => $this->translator->trans('titles.home')
            ],
            [
                'link' => $this->router->generate('location about us', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $category->getTitle($locale)
            ]
        ];
    }

    public function getGalleriesBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('home'),
                'title' => $this->translator->trans('titles.home')
            ],
            [
                'link' => $this->router->generate('location about us', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.galleries')
            ]
        ];
    }

    public function getGalleryBreadcrumbs(Location $location, Gallery $gallery, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('home'),
                'title' => $this->translator->trans('titles.home')
            ],
            [
                'link' => $this->router->generate('location about us', ['id' => $location->getId()]),
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

    public function getAboutUsBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('home'),
                'title' => $this->translator->trans('titles.home')
            ],
            [
                'link' => $this->router->generate('location about us', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.about_us')
            ]
        ];
    }

    public function getContactsBreadcrumbs(Location $location, string $locale): array
    {
        return [
            [
                'link' => $this->router->generate('home'),
                'title' => $this->translator->trans('titles.home')
            ],
            [
                'link' => $this->router->generate('location about us', ['id' => $location->getId()]),
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
                'link' => $this->router->generate('home'),
                'title' => $this->translator->trans('titles.home')
            ],
            [
                'link' => $this->router->generate('location about us', ['id' => $location->getId()]),
                'title' => $location->getTitle($locale)
            ],
            [
                'title' => $this->translator->trans('titles.menu')
            ]
        ];
    }
}