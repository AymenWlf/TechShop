<?php

namespace App\Class;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    private $session;
    private $em;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * Ajouter au panier avec session (Non Utilisable)
     *
     * @param integer $id
     * @param integer $quantity
     * @return void
     */
    public function add(int $id, int $quantity)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id] = $cart[$id] + $quantity;
        } else {
            $cart[$id] = $quantity;
        }
        $this->session->set('cart', $cart);
    }

    /**
     * Recuperer le panier de la session //
     *
     * @return array
     */
    public function get()
    {
        return $this->session->get('cart');
    }

    /**
     * Supprimer tous le panier
     *
     * @param integer $id
     * @return array
     */
    public function delete(int $id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    /**
     * Recuperer le panier complete sous forme de tableau personnaliser
     *
     * @return array
     */
    public function getFull()
    {
        $cartComplete = [];
        if ($this->get() == null) {
            return null;
        } else {
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
