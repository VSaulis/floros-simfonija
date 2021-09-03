<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\BanquetHall;
use App\Entity\Gallery;
use App\Entity\Hotel;
use App\Entity\Review;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Location;
use App\Entity\Room;
use App\Entity\RoomPerk;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractDashboardController
{
    private $translator;
    private $crudUrlGenerator;

    public function __construct(TranslatorInterface  $translator, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->translator = $translator;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $url = $this->crudUrlGenerator
            ->build()
            ->setController(LocationController::class)
            ->setAction(Action::INDEX)
            ->set('menuIndex', 0)
            ->generateUrl();

        return new RedirectResponse($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTranslationDomain('admin')
            ->setTitle('Floros simfonija');
    }

    public function configureMenuItems(): Iterable
    {
        yield MenuItem::linkToCrud('titles.locations', 'fas fa-map-marker', Location::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.galleries', 'fas fa-images', Gallery::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.reviews', 'fas fa-comments', Review::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.news', 'fas fa-calendar', Article::class)->setDefaultSort(['created' => 'DESC']);

        yield MenuItem::section('titles.meals');
        yield MenuItem::linkToCrud('titles.banquet_halls', 'fas fa-building', BanquetHall::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.products_categories', 'fas fa-book', ProductCategory::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.products', 'fas fa-utensils', Product::class)->setDefaultSort(['created' => 'DESC']);

        yield MenuItem::section('titles.accommodation');
        yield MenuItem::linkToCrud('titles.hotels', 'fas fa-hotel', Hotel::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.rooms', 'fas fa-bed', Room::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.perks', 'fas fa-plus-square', RoomPerk::class)->setDefaultSort(['created' => 'DESC']);
    }
}