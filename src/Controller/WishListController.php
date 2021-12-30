<?php

namespace App\Controller;

use App\Class\Search;
use App\Entity\Product;
use App\Entity\CartItem;
use App\Entity\WishList;
use App\Form\SearchType;
use App\Repository\WishListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPSTORM_META\map;

class WishListController extends AbstractController
{
    //ENTITY MANAGER
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Programme de wishList
    #[Route('/wishlist', name: 'wish_list')]
    public function index(): Response
    {
        //Verification si l'utilisateur et connecter et recuperations des produits de la wishList
        $user = $this->getUser();
        if ($user) {
            $wishList = $this->em->getRepository(WishList::class)->findByWish($user);
            if (!$wishList) {
                $wishList = new WishList();
                $wishList->setUser($user);

                $this->em->persist($wishList);
                $this->em->flush();
            }
        } else {
            $this->addFlash('info', "Connecter vous pour accéder à votre WishList !");
            return $this->redirectToRoute('app_login');
        }

        //EXTRAS
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        } else {
            $cart = null;
        }
        return $this->render('wish_list/index.html.twig', [
            'wishList' => $wishList,
            'cart' => $cart
        ]);
    }

    //Programme d'ajout a la wishList
    #[Route('/wishlist/add/{slug}', name: 'wish_list_add')]
    public function add($slug, Session $session): Response
    {
        //Recuperation d produit
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        //Verificationn si l'utilisateur est connecter
        $user = $this->getUser();
        if (!$user) {
            // $this->addFlash('info', 'Connecter vous pour ajouter un produits à votre WishList !');
            return $this->json([
                'code' => 403,
                'message' => 'Connecter vous pour ajouter un produits à votre WishList !'
            ],403);
        }

        //Recuperation de la wishList
        $wishList = $this->em->getRepository(WishList::class)->findOneBy(['user' => $user]);

        //Creation d'une wishList
        if (!$wishList) {
            $wishList = new WishList();
            $wishList->setUser($user);
        }

        //Recuperation des produits de la wishList et verification si le prosuit en question se trouve dejà dans la WishList
        if ($product->isItOnMyWishList($user)) {
            // notif
            $ProductName = $product->getName();
            $wishList->removeProduct($product);
            $this->em->flush();
            // $this->addFlash('warning', $ProductName . " est supprimée de votre wishList !");

            return $this->json([
                'code' => 200,
                'message' => 'Produit supprimé de votre wishList !'
            ],200);

        } else {
            //Ajout du produit dans la wishList
            $wishList->addProduct($product);
            $this->em->persist($wishList);
            $this->em->flush();

            //Notif
            $this->addFlash('success', 'Produit ajoutée à la wishList avec succes !');

            return $this->json([
                'code' => 200,
                'message' => 'Produit ajouté à la wishList'
            ],200);
        }

        // Redirection vers la page precedente
        
    }

    //Programme de suppression des produits de la wishList
    #[Route('/wishlist/remove/{slug}', name: 'wish_list_remove')]
    public function remove($slug): Response
    {
        //Recuperation du produit en question
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        $user = $this->getUser();

        //Recuperation de la wishList
        $wishList = $this->em->getRepository(WishList::class)->findOneBy(['user' => $user]);

        //Gestion des cas d'exception
        if (!$user) {
            //Pas connecter
            $this->addFlash('info', "Connecter vous pour accceder à votre wishList !");
            return $this->redirect($_SERVER['HTTP_REFERER']);
        } else if (!$wishList) {
            //Pas de wishList
            $this->addFlash('warning', "Vous n'avez aucune wishList !");
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        //Recuperation de tous les produits
        $products = $this->getUser()->getWishList()->getProducts()->getValues();
        //Verifier si le produit en quesyion se trouve dans la wishList
        $c = 0;
        foreach ($products as $wish_product) {
            if ($product === $wish_product) {
                $c += 1;
            }
        }
        if ($c >= 1) {
            $wishList->removeProduct($product);
            $this->em->flush();
            $this->addFlash('success', 'Vous avez retiré ce produit de votre wishList avec succes !');
        } else {
            $this->addFlash('warning', 'Ce produit ne se trouve pas dans votre wishList !');
        }


        // Redirection vers la page précedente
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
