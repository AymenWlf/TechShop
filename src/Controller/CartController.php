<?php

namespace App\Controller;

use App\Class\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(Cart $cart): Response
    {
        // dd($cart->getFull());
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/add-cart/{id}-{color}-{quantity}', name: 'add_cart')]
    public function add(Cart $cart,$id,$color,$quantity): Response
    {

        $cart->add($id,$quantity);
        return $this->redirectToRoute('cart');
    }
}
