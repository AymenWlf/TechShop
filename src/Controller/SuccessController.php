<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SuccessController extends AbstractController
{
    //ENTITY MANAGER
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Programme SuccessPage
    #[Route('/commande/success/{reference}', name: 'success')]
    public function index($reference): Response
    {
        //EXTRAS
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }

        //Recuperation des donnees de la commande
        $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        $orderDetails = $order->getOrderDetails()->getValues();
        $promoCode = $order->getPromoCode();

        return $this->render('success/index.html.twig',[
            'cart' => $cart,
            'order' => $order,
            'orderDetails' => $orderDetails,
            'promoCode' => $promoCode
        ]);
    }

    
}
