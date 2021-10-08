<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\CartItem;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        $addresses = $this->em->getRepository(Address::class)->findBy(['user' => $user]);

        if (isset($_POST['submitAd'])) {
            dd($_POST);
        }
        return $this->render('recapitulatif/index.html.twig',[
            'cart' => $cart,
            'carriers' => $carriers,
            'addresses' => $addresses
        ]);
    }

    // Cree page adresse dans account et ajouter ceci !!!!!!
    #[Route('/nouvel-adresse', name: 'new_address')]
    public function new(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class,$address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->em->persist($address);
            $this->em->flush();

            
            return $this->redirectToRoute('recap');
        }

        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }

        

        return $this->render('recapitulatif/address-form.html.twig',[
            'form' => $form->createView(),
            'cart' => $cart
        ]);
    }
}
