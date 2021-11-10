<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\CartItem;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/account/contact', name: 'contact')]
    public function index(Request $request): Response
    {
        //Declarations et initialisations
        $contact = new Contact();
        $user = $this->getUser();
        $form = $this->createForm(ContactType::class,$contact);
        
        //PROGRAMME

        //Formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contact->setUser($user);
            $this->em->persist($contact);
            $this->em->flush();

            return $this->redirectToRoute('home');
            //Notification et email
        }

        //Extras 
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        return $this->render('contact/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }
}
