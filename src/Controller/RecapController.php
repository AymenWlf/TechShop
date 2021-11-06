<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\VariationOption;
use App\Form\AddressType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FriendsOfTwig\Twigcs\Ruleset\Official;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPSTORM_META\map;

class RecapController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/recapitulatif', name: 'recap')]
    public function index(): Response
    {
        $user = $this->getUser();       
         
        $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        $date = new DateTime();
        $datetime = $date->format('d/m/Y');
        $dateChar = $date->format('dmY');
        $reference = $dateChar.'-'.uniqid();
        $delivery = [];
       
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
                'variation' => $variationName,
                'theVariation' => $variation
            ];
        }

        $carriers = $this->em->getRepository(Carrier::class)->findAll();

        $addresses = $this->em->getRepository(Address::class)->findBy(['user' => $user]);
        if (isset($_POST['submitPay'])) {
            $paymentMethod = $_POST['payment'];
         }

        
        if (isset($_POST['submitAd'])) {
            $addressCompany = null;
            $date = new DateTime();
            $carrierId = $_POST['carrier'];
            $addressId = $_POST['address'];
            $addressPost = $this->em->getRepository(Address::class)->findOneBy(['id' => $addressId]);
            $carrierPost = $this->em->getRepository(Carrier::class)->findOneBy(['id' => $carrierId]);

            if ($addressPost->getCompany()) {
                $addressCompany = $addressPost->getCompany();
            }
            $delivery[] = [
                'name' => $addressPost->getName(),
                'firstname' => $addressPost->getFirstName(),
                'lastname' => $addressPost->getLastName(),
                'address' => $addressPost->getAddress(),
                'postal' => $addressPost->getPostal(),
                'country' => $addressPost->getCountry(),
                'city' => $addressPost->getCity(),
                'phone' => $addressPost->getPhone(),
                'company' => $addressCompany
                
            ];

            $carrierName = $carrierPost->getName();
            $price = $carrierPost->getPrice();
            $total = $price;
            //Remplissage de Order
            $order = new Order();
            $order->setUser($user);
            $order->setCreatedAt($datetime);
            $order->setCarrierName($carrierName);
            $order->setCarrierPrice($price);
            $order->setState(1);
            $order->setIsPaid(0);
            $order->setLivraison($delivery);
            $order->setSessionCheckoutId(1111);
            $order->setReference($reference);
            
           
           

            //remplissage des orderDetaails

            foreach ($cart as $item) {
                $product = $item['product'];
                $price = $item['product']->getPrice();
                $quantity = $item['quantity'];
                $total += $price * $quantity;
                $variation = $item['theVariation'];
                foreach ($variation as $var) {
                    $VarName = $var->getVariation()->getName();
                    if ($VarName == 'Couleur') {
                        $oldStock = $var->getStock();
                        $newStock = $oldStock - $quantity;
                        $varId = $var->getId();
                    }
                }
                //Decrementation du stock
                $variationOption = $this->em->getRepository(VariationOption::class)->findOneBy(['id' => $varId]);
                $variationOption->setStock($newStock);

                
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product);
                $orderDetails->setQuantity($quantity);
                $orderDetails->setPrice($price);
                $orderDetails->setTotal($price * $quantity);
                // dd($product->getVariationOptions);
                $this->em->persist($orderDetails);

            }

            // Suppression du panier 
            $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $user]);
            foreach ($cartItems as $cartItem) {
                $this->em->remove($cartItem);
            }
            

            $order->setTotal($total);
            $this->em->persist($order);

            
            $this->em->flush();
            $paymentMethod = null;

            return $this->redirectToRoute('success',[
                'reference' => $reference
            ]);

         }

         
            
        

        // Appeler reference de order

        return $this->render('recapitulatif/index.html.twig',[
            'cart' => $cart,
            'carriers' => $carriers,
            'addresses' => $addresses,
            'payment' => $paymentMethod,
            'reference' => $reference
        ]);
    }

    
}
