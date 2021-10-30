<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\CartItem;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/account/mes-adresses', name: 'address')]
    public function index(): Response
    {
        // Recuperer les adresses :
        $addresses = $this->em->getRepository(Address::class)->findBy(['user' => $this->getUser()]);
        // Extras :
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }

        return $this->render('address/index.html.twig',[
            'cart' => $cart,
            'addresses' => $addresses
        ]);
    }
    
    #[Route('/account/nouvel-adresse', name: 'new_address')]
    public function new(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class,$address);
        $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->em->persist($address);
            $this->em->flush();

            if ($cartItems) {
                return $this->redirectToRoute('cart');
            }else{
                return  $this->redirectToRoute('address');
            }
            
            
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }

    
        return $this->render('address/address-form.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart
        ]);
    }

    #[Route('/account/supprimer-adresse/{id}', name: 'del_address')]
    public function delete($id): Response
    {
        $address = $this->em->getRepository(Address::class)->findOneBy(['id' => $id]);

        if ($address && $this->getUser() == $address->getUser()) {
            $this->em->remove($address);
            $this->em->flush();

            $this->addFlash('notice',"Adresse supprime avec success");
            return $this->redirectToRoute('address');
        }else{
            return $this->redirectToRoute('account');
        }
        
    }

    #[Route('/account/modifier-adresse/{id}', name: 'modif_address')]
    public function modify($id,Request $request): Response
    {
        $address = $this->em->getRepository(Address::class)->findOneBy(['id' => $id]);

        if ($address && $this->getUser() == $address->getUser()) {
            $form = $this->createForm(AddressType::class,$address);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $address = $form->getData();
                 $this->em->flush();
                 $this->addFlash('notice',"Adresse modifier avec success");
                 return $this->redirectToRoute('address');
            }

            // Extras :
            if ($this->getUser()) {
                $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
            }else{
                $cart = null;
            }

            return $this->render('address/address-form.html.twig', [
                'form' => $form->createView(),
                'cart' => $cart
            ]);

        }else{
            return $this->redirectToRoute('account');
        }

        
        
    }
}
