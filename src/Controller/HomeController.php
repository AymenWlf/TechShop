<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Product;
use App\Entity\Temoignage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    //ENTITY MANAGER 
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Vers HomePage
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        //Initialisation
        $headers = $this->em->getRepository(Header::class)->findThreeLastHeaders();
        $categories = $this->em->getRepository(Category::class)->findTwoLastCategories();
        $best_products = $this->em->getRepository(Product::class)->findBy(['isBest' => 1]);
        $temoignages = $this->em->getRepository(Temoignage::class)->findThreeLastTemoignages();

        //Extras
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        
        return $this->render('home/index.html.twig',[
            'headers' => $headers,
            'categories' => $categories,
            'bestProducts' => $best_products,
            'temoignages' => $temoignages,
            'cart' => $cart
        ]);
    }

}
