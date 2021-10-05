<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Carrier;
use App\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/address', name: 'address')]
    public function index(): Response
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

        $carriers = $this->em->getRepository(Carrier::class)->findAll();

        return $this->render('address/index.html.twig',[
            'cart' => $cart,
            'carriers' => $carriers
        ]);
    }
}
