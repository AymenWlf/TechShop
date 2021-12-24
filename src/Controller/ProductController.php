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
    //Entity Manager
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Programme de la ProductsPage
    #[Route('/products', name: 'product')]
    public function index(Request $request): Response
    {
        // Cree le formulaire du filtre
        $search = new Search();
        $Pages = 0;

        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //filtrer produits selon Search 
            $products = $this->em->getRepository(Product::class)->FindAllWithSearch($search);
            $Pages = 1;
        }else{
            //Tous recuperer
            $products = $this->em->getRepository(Product::class)->getPaginatedProducts((int) $request->query->get("page",1),8);
        }
        
       
        //Responsive Filter Form
        $form2 = $this->createForm(SearchType::class,$search);
        $form2->handleRequest($request); 

        if($form2->isSubmitted())
        {
            if ($form2->isSubmitted() && $form2->isValid()) {
                //filtrer produits selon Search 
                $products = $this->em->getRepository(Product::class)->FindAllWithSearch($search);
                $Pages = 1;
            }else{
                //Tous recuperer
                $products = $this->em->getRepository(Product::class)->getPaginatedProducts((int) $request->query->get("page",1),8);;
            }
        }
        

        //EXTRAS
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }

        if($Pages == 1)
        {
            $totalProducts = $Pages;
        }else{
            $totalProducts = $this->em->getRepository(Product::class)->countProductsById();
        }
        
        return $this->render('product/index.html.twig',[
            'products' => $products,
            'totalProducts' => $totalProducts,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'cart' => $cart,
            'pageActif' => (int) $request->query->get("page",1)
        ]);
    }
    
    //Programme de la ProductShowPage
    #[Route('/product-show/{slug}', name: 'product_show')]
    public function show($slug,Request $request): Response
    {   
        //Initialisation
        $c = 0;
        $illustrations = [];
        $couleurs = [];
        $review = new Review();
        $user = $this->getUser();
        $minStock = 1000;
        $msgRep = 0;

        //Recuperation du produit en question
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        //Creation du formulaire concernant Reviews
        $form = $this->createForm(ReviewType::class,$review);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!$user) {
                    $this->addFlash('info',"Conectez vous d'abord !");
                    return $this->redirectToRoute('app_login');
                }
                //Recuperation des avis du User
                $userReviews = $this->em->getRepository(Review::class)->findBy(['user' => $user]);

                //comptage de reviews
                foreach ($userReviews as $review) {
                    if ($review->getProduct() == $product) {
                        $c = $c + 1;
                    }
                }
                if ($c >= 3) {
                    //Notif
                    $this->addFlash('warning',"Votre avis n'est pas valide car vous avez ajouté trop de commentaire pour ce produit !");
                }else{
                    //Verifier si le commentaire est dejà envoyer 
                    foreach ($userReviews as $review) {
                        if ($review->getDescription() == $form->getData()->getDescription()) {
                            $msgRep = 1;
                        }
                    }

                    if ($msgRep == 1) {
                        $this->addFlash('warning',"Vous avez dejà envoyer le meme avis !");
                    }else{
                        //Remplissage de Review et flush()
                        $date = new DateTime();
                        $review = $form->getData();
                        $review->setUser($user);
                        $review->setProduct($product);
                        $review->setCreatedAt($date->format('d/m/Y'));
                        $this->em->persist($review);
                        $this->em->flush();

                        //Notif
                        $this->addFlash('success','Commentaire ajouté avec succes !');
                    }
                }
            
            }
        
        /* Gerer problemes form */ 

        //Recuperation variation du produit
        $productID = $product->getId();
        $variations = $this->em->getRepository(VariationOption::class)->findBy(['product' => $productID]);
        
        //Remplir marque et couleur et illustrations
        foreach ($variations as $var) {
            // Recuperation des couleurs et des illustrations et la marque 
            $varition_name = $var->getVariation()->getName();
            if ($varition_name == 'Couleur') {
                $couleurs[] = $var->getName();
                $illustrations[] = $var->getIllustration();
            }elseif ($varition_name == 'Marque'){
                $marque = $var->getName();
            }
        }

        //Envoyer le couleurs vers le formulaire dans CartType
        $formVar = $this->createForm(CartType::class,null,[
            'couleurs' => $couleurs
        ]);

        //Soumission du formulaire
       if (isset($_POST['submit'])) {
           //Recuperation de la couleur et quantity
            $color = $_POST['colors'];
            $quantity = $_POST['quantity'];

            //Verification si l'utilisateur est connecter
            if (!$this->getUser()) {
                //Notif
                $this->addFlash('info',"Connecter vous pour ajouter un produit a votre panier !");

                //Redirection vers la loginPage
                return $this->redirectToRoute('app_login');
            }elseif ($minStock == 0) 
            {
                //A GERER 
                //GESTION DU STROCK 
                //NOTIFICATION
                $this->addFlash('erreur',"Stock non gere pour le moment !");
                // return $this->redirectToRoute('cart'); 
            }else{
                //Tout va bien , On stock les donner dans CartItem
                //Recuperer les cartItems de User
                $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $user]);
                //Gerer les Items
                foreach ($cartItems as $item) {
                        $variation = $item->getVariation()->getValues();

                        foreach ($variation as $var) {
                            $VarName = $var->getVariation()->getName();
                            if ($VarName == 'Couleur' /* AJOUTER CONDITION*/ ) {
                                $variationName = $var->getName();
                            }
                        }
                        if ($variationName == $color && $item->getProduct() == $product) {
                            // Cas ou ils ont la meme couleur
                            //Incrementer la quantiter
                            $itemQuantity = $item->getQuantity();
                            $totalQuantity = $quantity + $itemQuantity;
                            if ($totalQuantity >= 10) {
                                //Cas ou la quantite est sup a 10 
                                //On arrete l'incrementation
                                $item->setQuantity(10);
                                $this->em->flush();
                                $this->addFlash('warning',"le maximum de piece pour ce produit est de 10 piece,pour en commandez plus veuillez nous contacter en privé dans la page CONTACT !");
                                
                                //Redirection vers cart
                                return $this->redirectToRoute('cart');
                            }else{
                                //Sinon on incremente
                                $item->setQuantity($totalQuantity);
                                $this->em->flush();
                                $this->addFlash('success',"Vous avez : ".$totalQuantity." x ".$item->getProduct()->getName()." de couleur ".$color." dans votre panier");

                                //Redirection vers cart
                                return $this->redirectToRoute('cart');
                            }
                        }
                }
                //Cas de panier vide 
                //Creation d'une nouvelle CartItem
                $cartItem = new CartItem();

                //Remplissage de CartItem
                $cartItem->setUser($this->getUser());
                $cartItem->setProduct($product);
                $cartItem->setQuantity($quantity);
                $cartItemColor = $this->em->getRepository(VariationOption::class)->findOneBy(['name' => $color]);
                $cartItem->addVariation($cartItemColor);
                $this->em->persist($cartItem);
                $this->em->flush();
                
                //Notif 
                $this->addFlash('success',$quantity." x ".$product->getName()." ajouté au panier");
                
                return $this->redirectToRoute('cart');
                
            }
            $this->addFlash('erreur',"Erreur Inconnue !");
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
 