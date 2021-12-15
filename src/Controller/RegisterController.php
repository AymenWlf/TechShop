<?php

namespace App\Controller;

use App\Entity\User;
use App\Class\MailJet;
use App\Entity\CartItem;
use App\Entity\WishList;
use App\Form\RegisterType;
use App\Entity\Confirmation;
use Prophecy\Promise\ThrowPromise;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    //Entity Manager
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    //Programme
    #[Route('/register', name: 'register')]
    public function index(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        //S'il est connecter
        if ($this->getUser()) {
            $this->addFlash('warning',"Vous etes deja connecter !");
            return $this->redirectToRoute('home');
        }
        //Initialisation
        $mail = new MailJet();
        $user = new User();
        $conf = new Confirmation();
        $wishList = new WishList();
        $form = $this->createForm(RegisterType::class,$user);

        //Envoie du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $email = $user->getEmail();
            $userName = $user->getPseudoName();
            $search_email = $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            //Si email utiliser
            if (!$search_email) {
                //Cryptage du password
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $user->setConfirmation($conf);
                $user->setWishList($wishList);

                $this->em->persist($user);
                $this->em->flush();

                //Envoie mail de confirmation
                $mail->Register($email,$userName);

                //Notification success
                $this->addFlash('success','Inscription reussis !!');
                return $this->redirectToRoute('app_login');
            }else{
                $this->addFlash('erreur',"Adresse email dejà utiliser !");
            }
        }
        
        //Extras
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
            
        return $this->render('register/index.html.twig',[
            'form' => $form->createView(),
            'cart' => $cart
        ]);
    }
}
