<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/account/mes-commandes', name: 'orders')]
    public function index(): Response
    {
        $orders = $this->em->getRepository(Order::class)->findBy(['user' => $this->getUser()]);

        // dd($orders);

        //Extras :
        
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        return $this->render('order/index.html.twig', [
            'orders' => $orders,
            'cart' => $cart
        ]);
    }

    #[Route('/account/mes-commandes/{{reference}} ', name: 'order')]
    public function order($reference): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        $orderDetails = $order->getOrderDetails()->getValues();
        // dd($orderDetails);
        // dd($order);
        //Extras :
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        return $this->render('order/order.html.twig', [
            'order' => $order,
            'cart' => $cart,
            'orderDetails' => $orderDetails
        ]);
    }

    #[Route('/account/mes-commandes/cancel/{{reference}} ', name: 'cancel_order')]
    public function cancel($reference): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        $order->setState(6);
        $this->em->flush();
        // dd($orderDetails);
        // dd($order);
        return $this->redirectToRoute('order',[
            'reference' => $order->getReference()
        ]);
    }
    
}
