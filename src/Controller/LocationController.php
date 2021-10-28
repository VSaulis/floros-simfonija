<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\BanquetHall;
use App\Entity\Gallery;
use App\Entity\Hotel;
use App\Entity\Location;
use App\Entity\Room;
use App\Form\Type\MessageType;
use App\Model\Message;
use App\Repository\ArticleRepository;
use App\Repository\BanquetHallRepository;
use App\Repository\GalleryRepository;
use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\RoomRepository;
use App\Util\BreadcrumbsHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

class LocationController extends AbstractController
{
    private $productCategoryRepository;
    private $roomRepository;
    private $breadcrumbsHelper;
    private $galleryRepository;
    private $hotelRepository;
    private $reviewRepository;
    private $banquetHallRepository;
    private $articleRepository;
    private $mailer;

    public function __construct
    (
        ReviewRepository $reviewRepository,
        ProductCategoryRepository $productCategoryRepository,
        BreadcrumbsHelper $breadcrumbsHelper,
        RoomRepository $roomRepository,
        GalleryRepository $galleryRepository,
        HotelRepository $hotelRepository,
        BanquetHallRepository $banquetHallRepository,
        ArticleRepository $articleRepository,
        MailerInterface $mailer
    )
    {
        $this->roomRepository = $roomRepository;
        $this->breadcrumbsHelper = $breadcrumbsHelper;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->galleryRepository = $galleryRepository;
        $this->hotelRepository = $hotelRepository;
        $this->reviewRepository = $reviewRepository;
        $this->banquetHallRepository = $banquetHallRepository;
        $this->articleRepository = $articleRepository;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/{id}/home", name="location home", requirements={"id"="\d+"})
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function home(Location $location, Request $request): Response
    {
        $hotels = $this->hotelRepository->findLocationHotels($location);
        $articles = $this->articleRepository->findLocationArticles($location);
        $galleries = $this->galleryRepository->findLocationGalleries($location);
        $reviews = $this->reviewRepository->findLocationReviews($location);
        $banquetHalls = $this->banquetHallRepository->findLocationBanquetHalls($location);

        return $this->render('pages/home.html.twig', [
            'location' => $location,
            'hotels' => $hotels,
            'articles' => $articles,
            'galleries' => $galleries,
            'reviews' => $reviews,
            'banquetHalls' => $banquetHalls,
            'breadcrumbs' => $this->breadcrumbsHelper->getHomeBreadcrumbs($location, $request->getLocale())
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
            $email = new Email();
            $email->from("contacts@florossimfonijahotel.lt");
            $email->to($location->getEmail());
            $email->subject($message->getSubject());
            $email->html($this->renderView('emails/contacts.html.twig', ['message' => $message]));
            $this->mailer->send($email);

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
     * @Route("/{id}/news", name="location news", requirements={"id"="\d+"})
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function news(Location $location, Request $request): Response
    {
        $articles = $this->articleRepository->findLocationArticles($location);

        return $this->render('pages/news.html.twig', [
            'location' => $location,
            'articles' => $articles,
            'breadcrumbs' => $this->breadcrumbsHelper->getNewsBreadcrumbs($location, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/news/{articleId}", name="location news article", requirements={"id"="\d+", "articleId"="\d+"})
     * @Entity("article", expr="repository.findLocationArticleById(id, articleId)")
     * @param Location $location
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function newsArticle(Location $location, Article $article, Request $request): Response
    {
        return $this->render('pages/news-article.html.twig', [
            'location' => $location,
            'article' => $article,
            'breadcrumbs' => $this->breadcrumbsHelper->getNewsArticleBreadcrumbs($location, $article, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/banquet-halls", name="location banquet halls", requirements={"id"="\d+"})
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function banquetHalls(Location $location, Request $request): Response
    {
        $banquetHalls = $this->banquetHallRepository->findLocationBanquetHalls($location);

        return $this->render('pages/banquet-halls.html.twig', [
            'location' => $location,
            'banquetHalls' => $banquetHalls,
            'breadcrumbs' => $this->breadcrumbsHelper->getBanquetHallsBreadcrumbs($location, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/banquet-halls/{banquetHallId}", name="location banquet hall", requirements={"id"="\d+", "banquetHallId"="\d+"})
     * @Entity("banquetHall", expr="repository.findLocationBanquetHallById(id, banquetHallId)")
     * @param Location $location
     * @param BanquetHall $banquetHall
     * @param Request $request
     * @return Response
     */
    public function banquetHall(Location $location, BanquetHall $banquetHall, Request $request): Response
    {
        return $this->render('pages/banquet-hall.html.twig', [
            'location' => $location,
            'banquetHall' => $banquetHall,
            'breadcrumbs' => $this->breadcrumbsHelper->getBanquetHallBreadcrumbs($location, $banquetHall, $request->getLocale())
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
     * @Route("/{id}/hotels/{hotelId}", name="location hotel", requirements={"id"="\d+", "hotelId"="\d+"})
     * @Entity("hotel", expr="repository.find(hotelId)")
     * @param Location $location
     * @param Hotel $hotel
     * @param Request $request
     * @return Response
     */
    public function hotel(Location $location, Hotel $hotel, Request $request): Response
    {
        return $this->render('pages/hotel.html.twig', [
            'location' => $location,
            'hotel' => $hotel,
            'breadcrumbs' => $this->breadcrumbsHelper->getHotelBreadcrumbs($location, $hotel, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/hotels/{hotelId}/rooms", name="location hotel rooms", requirements={"id"="\d+", "hotelId"="\d+"})
     * @Entity("hotel", expr="repository.find(hotelId)")
     * @param Location $location
     * @param Hotel $hotel
     * @param Request $request
     * @return Response
     */
    public function hotelRooms(Location $location, Hotel $hotel, Request $request): Response
    {
        return $this->render('pages/rooms.html.twig', [
            'location' => $location,
            'hotel' => $hotel,
            'rooms' => $hotel->getRooms(),
            'breadcrumbs' => $this->breadcrumbsHelper->getHotelRoomsBreadcrumbs($location, $hotel, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/hotels/{hotelId}/terms-and-conditions", name="location hotel terms and conditions", requirements={"id"="\d+", "hotelId"="\d+"})
     * @Entity("hotel", expr="repository.find(hotelId)")
     * @param Location $location
     * @param Hotel $hotel
     * @param Request $request
     * @return Response
     */
    public function hotelTermsAndConditions(Location $location, Hotel $hotel, Request $request): Response
    {
        return $this->render('pages/terms-and-conditions.html.twig', [
            'location' => $location,
            'hotel' => $hotel,
            'breadcrumbs' => $this->breadcrumbsHelper->getHotelTermsAndConditionsBreadcrumbs($location, $hotel, $request->getLocale())
        ]);
    }

    /**
     * @Route("/{id}/hotels/{hotelId}/{roomId}", name="location hotel room", requirements={"id"="\d+", "hotelId"="\d+", "roomId"="\d+"})
     * @Entity("room", expr="repository.find(roomId)")
     * @Entity("hotel", expr="repository.find(hotelId)")
     * @param Location $location
     * @param Room $room
     * @param Hotel $hotel
     * @param Request $request
     * @return Response
     */
    public function room(Location $location, Room $room, Hotel $hotel, Request $request): Response
    {
        $similarRooms = $this->roomRepository->findSimilarRooms($hotel);

        return $this->render('pages/room.html.twig', [
            'location' => $location,
            'room' => $room,
            'hotel' => $hotel,
            'similarRooms' => $similarRooms,
            'breadcrumbs' => $this->breadcrumbsHelper->getRoomBreadcrumbs($location, $room, $request->getLocale())
        ]);
    }
}