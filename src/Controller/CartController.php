<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\CartItem;
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

    //Programme
    #[Route('/check/cart', name: 'cart')]
    public function index(Cart $cart): Response
    {
        //Initialisatio,
        $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
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


            $cart[] = [
                'id' => $id,
                'product' => $product,
                'quantity' => $quantity,
                'variation' => $variationName
            ];
        }



        // if (isset($_POST['submitPay'])) {
        //     dd($_POST);
        // }
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/check/add-cart/{id}', name: 'add_cart')]
    public function add($id): Response
    {
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);
        

        $quantity = $cartItem->getQuantity();
        $quantity = $_POST['quantity'];
        $cartItem->setQuantity($quantity);
        $this->em->flush();
        $this->addFlash('notice',$cartItem->getProduct()->getName().' a été ajouté dans votre panier !');
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    #[Route('/check/plus-cart/{id}', name: 'plus_cart')]
    public function plus($id): Response
    {
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);

        $quantity = $cartItem->getQuantity();
        if ($quantity >= 10) {
            $quantity = 10;
            $this->addFlash('notice',"Le maximum de pièce pour ce produit est 10 !");
        }else if ($quantity >=1 && $quantity < 10) {
            $quantity++;
        }
        $cartItem->setQuantity($quantity);
        $this->em->flush();
        return $this->redirectToRoute('cart');
    }

    #[Route('/check/minus-cart/{id}', name: 'minus_cart')]
    public function minus($id): Response
    {
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);

        $quantity = $cartItem->getQuantity();
        if ($quantity <= 1) {
           $this->delete($id);
        }else if ($quantity >1 && $quantity <= 10) {
            $quantity--;
        }
        $cartItem->setQuantity($quantity);
        $this->em->flush();
        return $this->redirectToRoute('cart');
    }
    

    #[Route('/check/delete-cart/{id}', name: 'delete_cart')]
    public function delete($id): Response
    {
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);
        $this->em->remove($cartItem);
        $this->em->flush();
        
        $this->addFlash('notice',$cartItem->getProduct()->getName().' a été supprimer de votre panier !');
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

}
