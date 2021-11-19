<?php

namespace App\Controller;

use App\Class\MailJet;
use App\Entity\Contact;
use App\Entity\CartItem;
use App\Form\ContactType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    //ENTITY MANAGER
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Programmde ContactPage
    #[Route('/account/contact', name: 'contact')]
    public function index(Request $request): Response
    {
        //Declarations et initialisations
        $contact = new Contact();
        $user = $this->getUser();
        $form = $this->createForm(ContactType::class,$contact);
        

        //Formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Initialisation des Données du mail
            $date = new DateTime();
            $datetime = $date->format('d/m/Y');
            $time = $date->format('H:i');
            $userEmail = $user->getEmail();
            $userName = $user->getFirstname();

            //Remplissage de l'entite CONTACT
            $contact = $form->getData();
            $contact->setUser($user);

            $this->em->persist($contact);
            $this->em->flush();
            //Mail
            $mail = new MailJet();
            $mail->Contact($userEmail,$userName,$datetime,$time);

            //Notification
            $this->addFlash('success',"Votre réclamation est envoyer avec success, une reponse vous sera transmis dans votre email dans les 48h à venir !");

            return $this->redirectToRoute('home');

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
