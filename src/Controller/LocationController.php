<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\Type\MessageType;
use App\Model\Message;
use App\Repository\ProductCategoryRepository;
use App\Util\BreadcrumbsHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocationController extends AbstractController
{
    private $productCategoryRepository;
    private $translator;
    private $breadcrumbsHelper;

    public function __construct(ProductCategoryRepository $productCategoryRepository, BreadcrumbsHelper $breadcrumbsHelper)
    {
        $this->breadcrumbsHelper = $breadcrumbsHelper;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @Route("/{id}/about-us", name="location about us")
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function aboutUs(Location $location, Request $request): Response
    {
        return $this->render('pages/about-us.html.twig', [
            'location' => $location,
            'breadcrumbs' => $this->breadcrumbsHelper->getAboutUsBreadcrumbs($location, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/contacts", name="location contacts")
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function contacts(Location $location, Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('location contacts', ['id' => $location->getId()]);
        }

        return $this->render('pages/contacts.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
            'breadcrumbs' => $this->breadcrumbsHelper->getContactsBreadcrumbs($location, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/menu", name="location menu")
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function menu(Location $location, Request $request): Response
    {
        $categories = $this->productCategoryRepository->findAll();

        return $this->render('pages/menu.html.twig', [
            'categories' => $categories,
            'location' => $location,
            'breadcrumbs' => $this->breadcrumbsHelper->getMenuBreadcrumbs($location, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/gallery", name="location gallery")
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function gallery(Location $location, Request $request): Response
    {
        return $this->render('pages/gallery.html.twig', [
            'location' => $location,
            'breadcrumbs' => $this->breadcrumbsHelper->getGalleryBreadcrumbs($location, $request->getLocale())
        ]);
    }
}