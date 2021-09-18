<?php

namespace App\Controller;

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
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $headers = $this->em->getRepository(Header::class)->findAll();
        $categories = $this->em->getRepository(Category::class)->findAll();
        $best_products = $this->em->getRepository(Product::class)->findBy(['isBest' => 1]);
        $temoignages = $this->em->getRepository(Temoignage::class)->findAll();
        // dd($best_products);
        // dd($categories);
        
        return $this->render('home/index.html.twig',[
            'headers' => $headers,
            'categories' => $categories,
            'bestProducts' => $best_products,
            'temoignages' => $temoignages
        ]);
    }
}
