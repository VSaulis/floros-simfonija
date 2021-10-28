<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationsController extends AbstractController
{
    private $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @Route("/", name="locations")
     */
    public function locations(): Response
    {
        $locations = $this->locationRepository->findBy([], ['position' => 'asc']);

        return $this->render('pages/locations.html.twig', [
            'locations' => $locations
        ]);
    }
}