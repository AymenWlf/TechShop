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
    #[Route('/wishlist', name: 'wish_list')]
    public function index(): Response
    {
        $user = $this->getUser();

        
        $wishList = $this->em->getRepository(WishList::class)->findByWish($user);
        // $wishProducts = $wishList->getProducts();
        // dd($wishProducts);
        // dd($wishProducts);
        return $this->render('wish_list/index.html.twig',[
            'wishList' => $wishList
        ]);
    }

    #[Route('/wishlist/add/{slug}', name: 'wish_list_add')]
    public function add($slug,Session $session): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        // dd($product);
        $user = $this->getUser();
        // dd($user);
        if (!$user) {
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        $wishList = $this->em->getRepository(WishList::class)->findOneBy(['user' => $user]);
        if (!$wishList) {
            // dd($user);
            $wishList = new WishList();
            $wishList->setUser($user);
            // dd($wishList);

        }
        $wishList->addProduct($product);
        // dd($wishList);
        $this->em->persist($wishList);
        $this->em->flush();
        $this->addFlash('notice','Produit ajoutée à la wishList avec succes !');

        return $this->redirect($_SERVER['HTTP_REFERER']);
       

    }

    #[Route('/wishlist/remove/{slug}', name: 'wish_list_remove')]
    public function remove($slug): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        // dd($product);
        $user = $this->getUser();
        $wishList = $this->em->getRepository(WishList::class)->findOneBy(['user' => $user]);
        // dd($user);
        if (!$user || !$wishList) {
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        $wishList->removeProduct($product);
        $this->em->flush();

        return $this->redirect($_SERVER['HTTP_REFERER']);
       

    }
}
