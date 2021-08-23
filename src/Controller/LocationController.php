<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Location;
use App\Entity\Room;
use App\Entity\RoomCategory;
use App\Form\Type\MessageType;
use App\Model\Message;
use App\Repository\GalleryRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\RoomRepository;
use App\Util\BreadcrumbsHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    private $productCategoryRepository;
    private $roomRepository;
    private $breadcrumbsHelper;
    private $galleryRepository;

    public function __construct
    (
        ProductCategoryRepository $productCategoryRepository,
        BreadcrumbsHelper $breadcrumbsHelper,
        RoomRepository $roomRepository,
        GalleryRepository $galleryRepository
    )
    {
        $this->roomRepository = $roomRepository;
        $this->breadcrumbsHelper = $breadcrumbsHelper;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @Route("/{id}/about-us", name="location about us", requirements={"id"="\d+"})
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
     * @Route("/{id}/contacts", name="location contacts", requirements={"id"="\d+"})
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
     * @Route("/{id}/menu", name="location menu", requirements={"id"="\d+"})
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function menu(Location $location, Request $request): Response
    {
        $categories = $this->productCategoryRepository->findLocationCategories($location);

        return $this->render('pages/menu.html.twig', [
            'categories' => $categories,
            'location' => $location,
            'breadcrumbs' => $this->breadcrumbsHelper->getMenuBreadcrumbs($location, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/galleries", name="location galleries", requirements={"id"="\d+"})
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function galleries(Location $location, Request $request): Response
    {
        $galleries = $this->galleryRepository->findLocationGalleries($location);

        return $this->render('pages/galleries.html.twig', [
            'location' => $location,
            'galleries' => $galleries,
            'breadcrumbs' => $this->breadcrumbsHelper->getGalleriesBreadcrumbs($location, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/galleries/{galleryId}", name="location gallery", requirements={"id"="\d+", "galleryId"="\d+"})
     * @Entity("gallery", expr="repository.findLocationGalleryById(id, galleryId)")
     * @param Location $location
     * @param Gallery $gallery
     * @param Request $request
     * @return Response
     */
    public function gallery(Location $location, Gallery $gallery, Request $request): Response
    {
        return $this->render('pages/gallery.html.twig', [
            'location' => $location,
            'gallery' => $gallery,
            'breadcrumbs' => $this->breadcrumbsHelper->getGalleryBreadcrumbs($location, $gallery, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/rooms/{categoryId}", name="location room category", requirements={"id"="\d+", "categoryId"="\d+"})
     * @Entity("category", expr="repository.find(categoryId)")
     * @param Location $location
     * @param RoomCategory $category
     * @param Request $request
     * @return Response
     */
    public function rooms(Location $location, RoomCategory $category, Request $request): Response
    {
        $rooms = $this->roomRepository->findRoomsByLocationAndCategory($location, $category);

        return $this->render('pages/rooms.html.twig', [
            'location' => $location,
            'rooms' => $rooms,
            'category' => $category,
            'breadcrumbs' => $this->breadcrumbsHelper->getRoomsBreadcrumbs($location, $category, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/rooms/{categoryId}/{roomId}", name="location room", requirements={"id"="\d+", "categoryId"="\d+", "roomId"="\d+"})
     * @Entity("room", expr="repository.findLocationRoomById(id, roomId)")
     * @Entity("category", expr="repository.find(categoryId)")
     * @param Location $location
     * @param Room $room
     * @param RoomCategory $category
     * @param Request $request
     * @return Response
     */
    public function room(Location $location, Room $room, RoomCategory $category, Request $request): Response
    {
        $similarRooms = $this->roomRepository->findSimilarRooms($location, $category);

        return $this->render('pages/room.html.twig', [
            'location' => $location,
            'room' => $room,
            'category' => $category,
            'similarRooms' => $similarRooms,
            'breadcrumbs' => $this->breadcrumbsHelper->getRoomBreadcrumbs($location, $room, $request->getLocale())
        ]);
    }
}