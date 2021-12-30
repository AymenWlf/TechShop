<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\CartItem;
use App\Entity\PaiementMethod;
use App\Form\PaiementMethodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    //ENTITY MANAGER
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Programme  de la CartPage
    #[Route('/check/cart', name: 'cart')]
    public function index(Cart $cart): Response
    {
        //Recuperation des cartItems
        $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        //Initialisations
        $cart = [];

        //Remplissage de cart par les parametre a recupere sur TWIG
        foreach ($cartItems as $item) {
            $id = $item->getId();
            $product = $item->getProduct();
            $quantity = $item->getQuantity();
            $variation = $item->getVariation()->getValues();
            foreach ($variation as $var) {
                $VarName = $var->getVariation()->getName();
                if ($VarName == 'Couleur') {
                    $variationName = $var->getName();
                }
            }

            //Remplissage du tableau cart
            $cart[] = [
                'id' => $id,
                'product' => $product,
                'quantity' => $quantity,
                'variation' => $variationName
            ];
        }

        //Recuperation des methodes de paiement
        $paiementMethod = $this->em->getRepository(PaiementMethod::class)->findAll();

        if (isset($_POST['submitPay'])) {
            return $this->redirectToRoute('recap',[
                'paymentValue' => $_POST['payment']
            ]);
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'payMet' => $paiementMethod
        ]);
    }

    //Programme d'ajout d'un produit dans le panier
    /*
    #[Route('/check/add-cart/{id}', name: 'add_cart')]
    public function add($id): Response
    {
        //Recuperation des cartItems
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);
        
        $quantity = $cartItem->getQuantity();
        $quantity = $_POST['quantity'];
        $cartItem->setQuantity($quantity);
        $this->em->flush();
        $this->addFlash('notice',$cartItem->getProduct()->getName().' a été ajouté dans votre panier !');
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }*/

    //Programme d'incrementation de la quanitite du produit
    #[Route('/check/plus-cart/{id}', name: 'plus_cart')]
    public function plus($id): Response
    {
        //Recupertaion du produit dans carttem
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);

        //Recuperer la couleur de l'item
        $variationsItem = $cartItem->getVariation()->getValues();
        foreach ($variationsItem as $item) {
            $color = $item->getName();
        }

        //Recuperer le nom de l'item
        $productNameItem = $cartItem->getProduct()->getName();

        //Recuperation de la quantite
        $quantity = $cartItem->getQuantity();

        //Gestion de la quantite maxiamel
        if ($quantity >= 10) {
            //Cas superieur a 10
            $quantity = 10;
            $cartItem->setQuantity($quantity);
            $this->em->flush();


            //Notif
            $this->addFlash('warning',"le maximum de piece pour ce produit est de 10 piece,pour en commandez plus veuillez nous contacter en privé dans la page CONTACT !");
            return $this->json([
                'code' => 201,
                'message' => 'maximum atteint !'
            ],201);
           
        }else if ($quantity >=1 && $quantity < 10) {
            //Incrementation
            $quantity++;
            $cartItem->setQuantity($quantity);
            $this->em->flush();


            //Notif
            // $this->addFlash('success',"Vous avez : ".$quantity." x ".$productNameItem." de couleur ".$color." dans votre panier");
            return $this->json([
            'code' => 200,
            'message' => '+1'
            ],200);
        }
        
        //  return $this->redirectToRoute("cart");

        // return $this->redirectToRoute('cart');
    }


    //Decrementation de la quantiter du produit
    #[Route('/check/minus-cart/{id}', name: 'minus_cart')]
    public function minus($id): Response
    {
        //Recuperation du cartItem
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);

        //Recuperer la couleur de l'item
        $variationsItem = $cartItem->getVariation()->getValues();
        foreach ($variationsItem as $item) {
            $color = $item->getName();
        }

        //Recuperer le nom de l'item
        $productNameItem = $cartItem->getProduct()->getName();

        //Recuperation de la quantiter 
        $quantity = $cartItem->getQuantity();

        //Gestion de la quantite minimale
        if ($quantity <= 1) {
            //cas inferier à 1 supperession
           $this->delete($id);//Function

           //Notif 
           $this->addFlash('warning',$productNameItem." de couleur ".$color." a etait supprimer de votre panier !");
        }else if ($quantity >1 && $quantity <= 10) {
            //Cas normal decrementation
            $quantity--;

            //Notif
            $this->addFlash('success',"Vous avez : ".$quantity." x ".$productNameItem." de couleur ".$color." dans votre panier");
        }

        $cartItem->setQuantity($quantity);
        $this->em->flush();

        
        return $this->redirectToRoute('cart');
    }
    

    #[Route('/check/delete-cart/{id}', name: 'delete_cart')]
    public function delete($id): Response
    {
        //Recuperation de cart items
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);

        //Recuperer la couleur de l'item
        $variationsItem = $cartItem->getVariation()->getValues();
        foreach ($variationsItem as $item) {
            $color = $item->getName();
        }

        //Recuperer le nom de l'item
        $productNameItem = $cartItem->getProduct()->getName();

        //Suppresion du cartItem
        $this->em->remove($cartItem);
        $this->em->flush();
        
        //Notif
        $this->addFlash('warning',$productNameItem.' de couleur '.$color.' a etait supprimer de votre panier !');

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

}
