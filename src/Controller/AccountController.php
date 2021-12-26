<?php

namespace App\Controller;

use App\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    //ENTITY MANAGER
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Programme de la page account
     *
     * @return Response
     */
    #[Route('/account', name: 'account')]
    public function index(): Response
    {

        //EXTRAS
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        } else {
            $cart = null;
        }


        return $this->render('account/index.html.twig', [

            'cart' => $cart
        ]);
    }
}
