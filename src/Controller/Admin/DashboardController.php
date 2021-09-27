<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\Temoignage;
use App\Entity\User;
use App\Entity\Variation;
use App\Entity\VariationOption;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TechShop');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-users',User::class);
        yield MenuItem::linkToCrud('Headers', 'fa fa-tv',Header::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-clipboard-list',Category::class);
        yield MenuItem::linkToCrud('Products', 'fa fa-tag',Product::class);
        yield MenuItem::linkToCrud('Temoignages', 'fa fa-user-tie',Temoignage::class);
        yield MenuItem::linkToCrud('Variations', 'fa fa-palette',Variation::class);
        yield MenuItem::linkToCrud('Variations-options', 'fa fa-swatchbook',VariationOption::class);
        yield MenuItem::linkToCrud('Reviews', 'fa fa-star',Review::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
