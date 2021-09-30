<?php

namespace App\Controller;

use DateTime;
use App\Class\Search;
use App\Entity\Review;
use DateTimeImmutable;
use App\Entity\Product;
use App\Form\ReviewType;
use App\Form\SearchType;
use App\Entity\VariationOption;
use App\Form\CartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

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
        //Cree form Search
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //filtrer produits selon Search 
            $products = $this->em->getRepository(Product::class)->FindWithSearch($search);
        }else{
            //Tous recuperer
            $products = $this->em->getRepository(Product::class)->findAll();
        }


        return $this->render('product/index.html.twig',[
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    #[Route('/product-show/{slug}', name: 'product_show')]
    public function show($slug,Request $request): Response
    {   

        //Declaration
        $c = 0;
        $illustrations = [];
        $couleurs = [];
        $review = new Review();
        $user = $this->getUser();

        //Recuperation du produit 
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        //Creation du formulaire
        $form = $this->createForm(ReviewType::class,$review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Verification user conncter
            if ($user) {
                //recuperation reviews user
                $userReviews = $this->em->getRepository(Review::class)->findBy(['user' => $user]);

                //comptage de reviews
                foreach ($userReviews as $review) {
                    if ($review->getProduct() == $product) {
                        $c = $c + 1;
                    }
                }
                if ($c >= 3) {
                    $this->addFlash('notice','Vous avez ajouté trop de commentaire pour ce produit !');
                }else{
                    //Remplissage de review et flush()
                    $date = new DateTime();
                    $review = $form->getData();
                    $review->setUser($user);
                    $review->setProduct($product);
                    $review->setCreatedAt($date->format('d/m/Y'));
                    $this->em->persist($review);
                    $this->em->flush();
                    $this->addFlash('notice','Commentaire ajouté avec succes !');
                }
            }else{
                $this->addFlash('notice','Connecter vous pour ajouter un commentaire !');
                return $this->redirectToRoute('app_login');
            }
        }/* Gerer problemes form */ 

        //Recuperation variation du produit
        $productID = $product->getId();
        $variations = $this->em->getRepository(VariationOption::class)->findBy(['product' => $productID]);
        
        //Remplir marque et couleur et illustrations
        foreach ($variations as $var) {
            
            $varition_name = $var->getVariation()->getName();
            if ($varition_name == 'Couleur') {
                $couleurs[] = $var->getName();
                $illustrations[] = $var->getIllustration();
            }elseif ($varition_name == 'Marque'){
                $marque = $var->getName();
            }
        }

        $formVar = $this->createForm(CartType::class,null,[
            'couleurs' => $couleurs
        ]);
        
       if (isset($_POST['submit'])) {
           dd($_POST);
       }


        //Variables Extra affichage
        $allReviews = $this->em->getRepository(Review::class)->findBy(['product' => $product]);
       
        return $this->render('product/product_show.html.twig',[
            'product' => $product,
            'illustrations' => $illustrations,
            'couleurs' => $couleurs,
            'marque' => $marque,
            'form' => $form->createView(),
            'allReviews' => $allReviews,
            'formVar' => $formVar->createView()
        ]);
    }
}
