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
    //ENTITY MANAGER
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Programme page ADDRESS
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
    
    //Programme page Nouvel adresse
    #[Route('/account/nouvel-adresse', name: 'new_address')]
    public function new(Request $request): Response
    {
        //Initialistation d'adresse
        $address = new Address();

        //Creation du formulaire d'adresse
        $form = $this->createForm(AddressType::class,$address);

        //Recuperation de cartItem pour redirier vers le panier (A faire mieux !!!!)
        $cartItems = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);

        //Envoie du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Stockage dans adresse
            $address->setUser($this->getUser());
            $this->em->persist($address);
            $this->em->flush();

            //Notif
            $this->addFlash('success',"Votre nouvel adresse est enregistrer avec success");

            //Redirection ...
            if ($cartItems) {
                return $this->redirectToRoute('cart');
            }else{
                return  $this->redirectToRoute('address');
            }
            
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        //EXTRAS
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

    //Programme de suppression d'une adresse
    #[Route('/account/supprimer-adresse/{id}', name: 'del_address')]
    public function delete($id): Response
    {
        //Recupere l'adresse en question
        $address = $this->em->getRepository(Address::class)->findOneBy(['id' => $id]);

        //Verifier si l'adresse est celle de User
        if ($address && $this->getUser() == $address->getUser()) {
            //Supression
            $this->em->remove($address);
            $this->em->flush();

            //Notif
            $this->addFlash('success',"Votre adresse a été supprimer avec success");

            return $this->redirectToRoute('address');
        }else{
            //Notif
            $this->addFlash('erreur',"Ce lien n'est pas valid !");
            
            return $this->redirectToRoute('account');
        }
        
    }

    //Programme de modification d'adresse
    #[Route('/account/modifier-adresse/{id}', name: 'modif_address')]
    public function modify($id,Request $request): Response
    {
        //Recuperation de l'adresse en question
        $address = $this->em->getRepository(Address::class)->findOneBy(['id' => $id]);

        //Verifier si l'adresse est celle de User
        if ($address && $this->getUser() == $address->getUser()) {
            //Creation du formulaire
            $form = $this->createForm(AddressType::class,$address);

            //Envoie du formulaire
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                //Stocker la data
                $address = $form->getData();
                 $this->em->flush();

                 //Notif
                 $this->addFlash('success',"Votre adresse a été modifier avec success");

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
            //Notif
            $this->addFlash('erreur',"Ce lien n'est pas valid !");

            return $this->redirectToRoute('account');
        }

        
        
    }
}
