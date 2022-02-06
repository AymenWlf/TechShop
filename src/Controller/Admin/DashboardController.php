<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Header;
use App\Entity\Review;
use App\Entity\Carrier;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\PromoCode;
use App\Entity\Variation;
use App\Entity\Subscriber;
use App\Entity\Temoignage;
use App\Entity\PaiementMethod;
use App\Entity\VariationOption;
use App\Controller\Admin\OrderCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TechShop');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('User');
        yield MenuItem::linkToCrud('Users', 'fa fa-users',User::class);
        yield MenuItem::linkToCrud('Subscribers', 'fa fa-user-tag',Subscriber::class);
        yield MenuItem::linkToCrud('Orders', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Reviews', 'fa fa-star',Review::class);
        yield MenuItem::section('Products');
        yield MenuItem::linkToCrud('Products', 'fa fa-tag',Product::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-clipboard-list',Category::class);
        yield MenuItem::linkToCrud('Variations', 'fa fa-palette',Variation::class);
        yield MenuItem::linkToCrud('Variations-options', 'fa fa-swatchbook',VariationOption::class);
        yield MenuItem::linkToCrud('Carriers', 'fa fa-truck',Carrier::class);
        yield MenuItem::linkToCrud('Paiement Method', 'fa fa-credit-card',PaiementMethod::class);
        yield MenuItem::section('Extras');
        yield MenuItem::linkToCrud('Temoignages', 'fa fa-user-tie',Temoignage::class);
        yield MenuItem::linkToCrud('Headers', 'fa fa-tv',Header::class);
        yield MenuItem::linkToCrud('Contacts', 'fa fa-envelope-open',Contact::class);
        yield MenuItem::linkToCrud('Discount', 'fa fa-tags',PromoCode::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
