<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/register', name: 'register')]
    public function index(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $search_email = $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if (!$search_email) {
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $this->em->persist($user);
                $this->em->flush();
                return $this->redirectToRoute('app_login');
            }
        }else{
            
        }
        
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
