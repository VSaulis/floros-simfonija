<?php

namespace App\Util;

use App\Entity\Location;
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

    public function getGalleryBreadcrumbs(Location $location, string $locale): array
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
                'title' => $this->translator->trans('titles.gallery')
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