<?php

namespace App\Controller\Admin;

use App\Entity\Restaurant;
use App\Form\Type\RestaurantType;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantsController extends AbstractController
{
    private $entityManager;
    private $restaurantRepository;

    public function __construct(EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/restaurants", name="admin restaurants")
     */
    public function restaurants(): Response
    {
        $restaurants = $this->restaurantRepository->findAll();

        return $this->render('admin/pages/restaurant/list.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

    /**
     * @Route("/admin/restaurants/add", name="admin restaurant add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($restaurant);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin restaurants');
        }

        return $this->render('admin/pages/restaurant/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/restaurants/edit/{id}", name="admin restaurant edit")
     * @param Restaurant $restaurant
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Restaurant $restaurant, Request $request)
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
            return $this->redirectToRoute('admin restaurants');
        }

        return $this->render('admin/pages/restaurant/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/restaurants/delete/{id}", name="admin restaurant delete")
     * @param Restaurant $restaurant
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function delete(Restaurant $restaurant, Request $request)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->entityManager->remove($restaurant);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin restaurants');
        }

        return $this->render('admin/pages/restaurant/delete.html.twig', [
            'restaurant' => $restaurant
        ]);
    }
}