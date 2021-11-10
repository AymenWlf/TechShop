<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index($reference): Response
    {
        //Declarations
        $product_for_stripe = [];
        $MY_DOMAIN = "http://localhost:8000/";

        //Appel de la commande
        $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        //Cas de commande == null
        if (!$order) {
            new JsonResponse(['error' => 'recap']);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $name = $product->getProduct()->getName();
            $product_object = $this->em->getRepository(Product::class)->findOneBy(['name' => $name]);
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'mad',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $name,
                        'images' => [$MY_DOMAIN."/uploads/".$product_object->getIllustration()]
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        //Ajout de la livraison
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'mad',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$MY_DOMAIN]
                ],
            ],
            'quantity' => 1,
        ];
        //Remplissage des produits dans stripe


        // Integration de Stripe
        Stripe::setApiKey('sk_test_51JtdUMDIQ4cDVAEB5FLVUgRLeMjqyhVReILxzOEjJ3XoElhDbmGVHLMqCmIeI7ZdhaKJyPHZ11DHHIZvEOCBFD2400ch4S0jEo');
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [
                $product_for_stripe
            ],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            'success_url' => $MY_DOMAIN.'commande/success/'.$reference,
            'cancel_url' => $MY_DOMAIN.'commande/cancel/'.$reference,
        ]);


        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
