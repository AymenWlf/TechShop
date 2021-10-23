<?php

namespace App\Controller;

use DateTime;
use App\Class\Search;
use App\Entity\CartItem;
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
        

        //Variable extra 

        
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }

        return $this->render('product/index.html.twig',[
            'products' => $products,
            'form' => $form->createView(),
            'cart' => $cart
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
        $minStock = 1000;

        //Recuperation du produit 
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        //Creation du formulaire
        $form = $this->createForm(ReviewType::class,$review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
            // $url = $this->generateUrl('add_cart',['id' => $productId]);
            $color = $_POST['colors'];
            $quantity = $_POST['quantity'];

            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }elseif ($minStock == 0) {
                return $this->redirectToRoute('cart'); 
            }else{
                $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $user]);
                foreach ($cartItems as $item) {
                    if ($item->getProduct() == $product) {
                        $variation = $item->getVariation()->getValues();
                        foreach ($variation as $var) {
                            $VarName = $var->getVariation()->getName();
                            if ($VarName == 'Couleur') {
                                $variationName = $var->getName();
                            }
                        }
                        if ($variationName == $color) {
                            $itemQuantity = $item->getQuantity();
                            $totalQuantity = $quantity + $itemQuantity;
                            if ($totalQuantity >= 10) {
                                $item->setQuantity(10);
                                $this->em->flush();
                                $this->addFlash('notice',"le maximum de piece pour ce produit est de 10 piece,pour en commandez plus veuillez nous contacter en privé !");
                                return $this->redirectToRoute('cart');
                            }else{
                                $item->setQuantity($totalQuantity);
                                $this->em->flush();
                                return $this->redirectToRoute('cart');
                            }
                        }else{
                            $cartItem = new CartItem();
                            $cartItem->setUser($this->getUser());
                            $cartItem->setProduct($product);
                            $cartItem->setQuantity($quantity);
                            $cartItemColor = $this->em->getRepository(VariationOption::class)->findOneBy(['name' => $color]);
                            $cartItem->addVariation($cartItemColor);
                            $this->em->persist($cartItem);
                            $this->em->flush();
                            return $this->redirectToRoute('cart');
                        }
                    }
                }
                
                $cartItem = new CartItem();
                $cartItem->setUser($this->getUser());
                $cartItem->setProduct($product);
                $cartItem->setQuantity($quantity);
                $cartItemColor = $this->em->getRepository(VariationOption::class)->findOneBy(['name' => $color]);
                $cartItem->addVariation($cartItemColor);
                $this->em->persist($cartItem);
                $this->em->flush();
                return $this->redirectToRoute('cart');
                
            }
            return $this->redirectToRoute('cart');
       }


        //Variables Extra affichage
        $allReviews = $this->em->getRepository(Review::class)->findBy(['product' => $product]);
        $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
       
        return $this->render('product/product_show.html.twig',[
            'product' => $product,
            'illustrations' => $illustrations,
            'couleurs' => $couleurs,
            'marque' => $marque,
            'form' => $form->createView(),
            'allReviews' => $allReviews,
            'formVar' => $formVar->createView(),
            'cart' => $cart
        ]);
    }
}
 