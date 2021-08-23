<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Location;
use App\Entity\Room;
use App\Entity\RoomCategory;
use App\Entity\RoomPerk;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
            ->setTitle('titles.admin');
    }

    public function configureMenuItems(): Iterable
    {
        yield MenuItem::linkToCrud('titles.locations', 'fas fa-map-pin', Location::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.products_categories', 'fas fa-th-list', ProductCategory::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.products', 'fas fa-th-list', Product::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.rooms', 'fas fa-th-list', Room::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.perks', 'fas fa-th-list', RoomPerk::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.galleries', 'fas fa-th-list', Gallery::class)->setDefaultSort(['created' => 'DESC']);
        yield MenuItem::linkToCrud('titles.rooms_categories', 'fas fa-th-list', RoomCategory::class)->setDefaultSort(['created' => 'DESC']);
    }
}