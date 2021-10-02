<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/cart', name: 'cart')]
    public function index(Cart $cart): Response
    {
        $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
       
        $cart = [];
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

        return $this->render('cart/index.html.twig', [
            'cart' => $cart
        ]);
    }

    #[Route('/add-cart/{id}', name: 'add_cart')]
    public function add($id): Response
    {
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);

        $quantity = $cartItem->getQuantity();
        $quantity = $_POST['quantity'];
        $cartItem->setQuantity($quantity);
        $this->em->flush();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    #[Route('/delete-cart/{id}', name: 'delete_cart')]
    public function delete($id): Response
    {
        $cartItem = $this->em->getRepository(CartItem::class)->findOneBy(['id' => $id]);

        $this->em->remove($cartItem);
        $this->em->flush();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

}
