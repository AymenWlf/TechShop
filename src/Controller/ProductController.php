<?php

namespace App\Controller;

use App\Class\Search;
use App\Entity\Product;
use App\Entity\VariationOption;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/products', name: 'product')]
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->em->getRepository(Product::class)->FindWithSearch($search);
        }else{
            $products = $this->em->getRepository(Product::class)->findAll();
        }


        return $this->render('product/index.html.twig',[
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    #[Route('/product-show/{slug}', name: 'product_show')]
    public function show($slug): Response
    {
        $illustrations = [];
        $couleurs = [];
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        $productID = $product->getId();
        // dd($productID);
        $variations = $this->em->getRepository(VariationOption::class)->findBy(['product' => $productID]);
        // dd($variations);
        // dd($variations);
        foreach ($variations as $var) {
        
            $varition_name = $var->getVariation()->getName();
            
            if ($varition_name == 'Couleur') {
                $couleurs[] = $var->getName();
            }elseif ($varition_name == 'Marque'){
                $marque = $var->getName();
            }
        }
        
        $illustration2 = $product->getIllustration2();
        $illustration3 = $product->getIllustration3();
        $illustration4 = $product->getIllustration4();
        
        if ($illustration2) {
            $illustrations[] = $illustration2;
        }
        if ($illustration3) {
            $illustrations[] = $illustration3;
        }
        if ($illustration4) {
            $illustrations[] = $illustration4;
        }
        // dd($illustrations);
        return $this->render('product/product_show.html.twig',[
            'product' => $product,
            'illustrations' => $illustrations,
            'couleurs' => $couleurs,
            'marque' => $marque
        ]);
    }
}
