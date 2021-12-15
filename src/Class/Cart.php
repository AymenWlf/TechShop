<?php

namespace App\Class;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart {

    private $session;
    private $em;
    private $test;
    private $test2;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }
    public function add($id,$quantity)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id] = $cart[$id] + $quantity;
        }else{
            $cart[$id] = $quantity;
        }
        $this->session->set('cart',$cart);

    }   


    public function get()
    {
        return $this->session->get('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart',[]);

        unset($cart[$id]);

        return $this->session->set('cart',$cart);
    }
    
    public function getFull()
    {
        $cartComplete = [];
        if ($this->get() == null) {
            return null;
        }else{
            foreach ($this->get() as $id => $quantity) {
                $cartProduct = $this->em->getRepository(Product::class)->findOneBy(['id' => $id]);
                if (!$cartProduct) {
                    $this->delete($id);
                    continue;
                }


                $cartComplete[] = [
                    'product' => $cartProduct,
                    'quantity' => $quantity
                ];
                
            }
        }

        return $cartComplete;
    }
}