<?php

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $productCategoryRepository;
    private $locationRepository;

    public function __construct(ProductCategoryRepository $productCategoryRepository, LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $locations = $this->locationRepository->findAll();

        return $this->render('pages/home.html.twig', [
            'locations' => $locations
        ]);
    }

    /**
     * @Route("/about-us", name="about us")
     */
    public function aboutUs(): Response
    {
        return $this->render('pages/about-us.html.twig');
    }

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contacts(): Response
    {
        return $this->render('pages/contacts.html.twig');
    }

    /**
     * @Route("/menu", name="menu")
     */
    public function menu(): Response
    {
        $categories = $this->productCategoryRepository->findAll();

        return $this->render('pages/menu.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/gallery", name="gallery")
     */
    public function gallery(): Response
    {
        return $this->render('pages/gallery.html.twig');
    }
}