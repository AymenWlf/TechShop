<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\User;
use App\Form\ModifInfosType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CredentialsController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/account/credentials', name: 'credentials')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        return $this->render('credentials/index.html.twig',[
            'cart' => $cart,
            'user' => $user
        ]);
    }

    #[Route('/account/credentials/modif', name: 'credentials_modif')]
    public function modif(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        //mail dirige vers ce lien
        $user = $this->getUser();
        $form = $this->createForm(ModifInfosType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $this->em->flush();

                return $this->redirectToRoute('credentials');
        }
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        return $this->render('credentials/modif.html.twig',[
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }

    
}
