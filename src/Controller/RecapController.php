<?php

namespace App\Controller;

use App\Class\Cart;
use App\Class\MailJet;
use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\CartItem;
use App\Entity\Confirmation;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\PaiementMethod;
use App\Entity\PromoCode;
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
    //Entity Manager
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Programme RecapPage
    #[Route('/recapitulatif/{paymentValue}', name: 'recap')]
    public function index($paymentValue): Response
    {
        
        //Declarations 
        $user = $this->getUser(); 
        $userEmail = $user->getEmail();
        $userPseudoName = $user->getPseudoName();     
        $paymentMethod = null;
        $paymentName = null;
        $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        $date = new DateTime();
        $datetime = $date->format('d/m/Y');
        $dateChar = $date->format('dmY');
        $time = $date->format('H:i');
        $reference = $dateChar.'-'.uniqid();
        $delivery = [];
        $cart = [];
        $addresses = $user->getAddresses()->getValues();
        //Programme : 
        //Cas des adresses 
        if (!$addresses) {
            //Notif
            $this->addFlash('warning',"Veuiller ajouter une adresses !");

            return $this->redirectToRoute('new_address');
        }
        

        //Remplissage tableau Cart[] Par les infos a afficher
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

        //Mode de paiement
        $paymentMethod = $this->em->getRepository(PaiementMethod::class)->findOneBy(['value' => $paymentValue]);
        $paymentName = $paymentMethod->getName();

        if (!$cartItems) {
            return $this->redirectToRoute('cart');
        }
        
        
        

        //Confirmation
        if (isset($_POST['submitAd'])) {
            $promoCode = null;
            if($_POST['promoCode'])
            {
                $promoCode = $_POST['promoCode'];
            }else if($_POST['promoCodeResp'])
            {
                $promoCode = $_POST['promoCodeResp'];
                
            }
            
            //   dd($_POST);
            $now = new DateTime();

            $promoEntity = $this->em->getRepository(PromoCode::class)->findOneBy(['code' => $promoCode]);
            //  dd($promoCode);
            $promoCodeUser = $this->em->getRepository(PromoCode::class)->findOneBy(['user' => $user]);
            if($promoCode)
            {
                
                if(!$promoEntity)
                {
                    $this->addFlash("erreur","Ce code de promos n'existe pas !");
                    return $this->redirect($_SERVER['HTTP_REFERER']);

                }elseif($promoCodeUser != $promoEntity)
                {
                    $this->addFlash("erreur","Ce code de prmos ne vous appartient pas !");
                    return $this->redirect($_SERVER['HTTP_REFERER']);

                }elseif ($now > $promoEntity->getCreatedAt()->modify("+ 30 days")) {
                    $this->addFlash("erreur","Votre code promos a expirer");
                    return $this->redirect($_SERVER['HTTP_REFERER']);

                }elseif ($promoEntity->getUsed() == true)
                {
                    $this->addFlash("erreur","Votre code est dejà utilisé");
                    return $this->redirect($_SERVER['HTTP_REFERER']);
                }
            }

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

            $strDelivery = "Addresse : ".$addressPost->getAddress()." ".$addressPost->getCountry()." ".$addressPost->getCity()." Num : 0".$addressPost->getPhone()."//".$addressCompany;


            $carrierName = $carrierPost->getName();
            $Carrierprice = $carrierPost->getPrice();
            $total = 0;

            //Remplissage de Order
            $order = new Order();
            $order->setUser($user);
            $order->setCreatedAt($datetime);
            $order->setCarrierName($carrierName);
            $order->setCarrierPrice($Carrierprice);
            $order->setState(1);
            $order->setIsPaid(0);
            $order->setLivraison($delivery);
            $order->setStrDelivery($strDelivery);
            $order->setSessionCheckoutId(1111);
            $order->setReference($reference);
            $order->setPaiementMethod($paymentMethod);

            //Remplissage des OrderDetails
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

            if($promoEntity)
            {
                $discount = $promoEntity->getDiscount()/100;
                $total = $total - ($total) * $discount;
            }
            
            
            $total += $Carrierprice;

            //Ajout du total
            $order->setTotal($total);
            $this->em->persist($order);
            $this->em->flush();

            if($promoEntity)
            {
                $promoEntity->setUsed(True);
                $this->em->flush();
            }
            

            //Confirmation par mail
            $mail = new MailJet();
            $mail->ConfirmationOrder($userEmail,$userPseudoName,$reference,$datetime,$time);

            //Redirection
            return $this->redirectToRoute('success',[
                'reference' => $reference
            ]);

         }

        return $this->render('recapitulatif/index.html.twig',[
            'cart' => $cart,
            'carriers' => $carriers,
            'addresses' => $addresses,
            'payment' => $paymentName,
            'reference' => $reference
        ]);
    }

    
}
