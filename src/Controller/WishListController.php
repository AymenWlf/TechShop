<?php

namespace App\Controller;

use App\Class\Search;
use App\Entity\Product;
use App\Entity\WishList;
use App\Form\SearchType;
use App\Repository\WishListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WishListController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/account/wishlist', name: 'wish_list')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $wishList = $this->em->getRepository(WishList::class)->findByWish($user);
        }else{
            $this->addFlash('notice','Connecter vous pour accéder à votre WishList !');
            return $this->redirectToRoute('app_login');
        }

        
        // $wishProducts = $wishList->getProducts();
        // dd($wishProducts);
        // dd($wishProducts);
        // dd($wishList);
        return $this->render('wish_list/index.html.twig',[
            'wishList' => $wishList
        ]);
    }

    #[Route('/account/wishlist/add/{slug}', name: 'wish_list_add')]
    public function add($slug,Session $session): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        // dd($product);
        $user = $this->getUser();
        // dd($user);
        if (!$user) {
            $this->addFlash('notice','Connecter vous pour ajouter un produits à votre WishList !');
        }
        $wishList = $this->em->getRepository(WishList::class)->findOneBy(['user' => $user]);
        if (!$wishList) {
            // dd($user);
            $wishList = new WishList();
            $wishList->setUser($user);
            // dd($wishList);

        }

        $products = $this->getUser()->getWishList()->getProducts()->getValues();
        $c = null;
        foreach ($products as $wish_product) {
            if ($product === $wish_product) {
                $c = 1;
            }
        }
        if ($c === 1) {
            $this->addFlash('notice','Ce produit se trouve dejà dans votre wishList !');
        }else{
            $wishList->addProduct($product);
            // dd($wishList);
            $this->em->persist($wishList);
            $this->em->flush();
            $this->addFlash('notice','Produit ajoutée à la wishList avec succes !');
        }
        

        return $this->redirect($_SERVER['HTTP_REFERER']);
       

    }

    #[Route('/account/wishlist/remove/{slug}', name: 'wish_list_remove')]
    public function remove($slug): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        // dd($product);
        $user = $this->getUser();
        $wishList = $this->em->getRepository(WishList::class)->findOneBy(['user' => $user]);
        // dd($user);
        if (!$user || !$wishList) {
            $this->addFlash('notice',"Votre wishList es vide !");
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        $products = $this->getUser()->getWishList()->getProducts()->getValues();
        $c = null;
        foreach ($products as $wish_product) {
            if ($product === $wish_product) {
                $c = 1;
            }
        }
        if ($c === 1) {
            $wishList->removeProduct($product);
            $this->em->flush();
            $this->addFlash('notice','Vous avez retiré ce produit de votre wishList avec succes !');
        }else{
            $this->addFlash('notice','Ce produit ne se trouve pas dans votre wishList !');
        }

       

        return $this->redirect($_SERVER['HTTP_REFERER']);
       

    }
}
