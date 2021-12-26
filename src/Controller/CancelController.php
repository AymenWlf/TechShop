<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CancelController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/commande/cancel/{reference}', name: 'cancel')]
    public function index($reference): Response
    {
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        } else {
            $cart = null;
        }

        $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        $orderDetails = $order->getOrderDetails()->getValues();


        return $this->render('cancel/index.html.twig', [
            'cart' => $cart,
            'order' => $order,
            'orderDetails' => $orderDetails
        ]);
    }
}
