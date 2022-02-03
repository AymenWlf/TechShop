<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Class\MailJet;
use App\Entity\Header;
use App\Entity\Product;
use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\PromoCode;
use App\Entity\Subscriber;
use App\Entity\Temoignage;
use App\Repository\UserRepository;
use App\Repository\HeaderRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\TemoignageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    //ENTITY MANAGER 
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * HomePage
     *
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(HeaderRepository $headerRepository,CategoryRepository $categoryRepository,ProductRepository $productRepository,TemoignageRepository $temoignageRepository): Response
    {
        //Initialisation
        $headers = $headerRepository->findThreeLastHeaders();
        $categories = $categoryRepository->findTwoLastCategories();
        $best_products = $productRepository->findBy(['isBest' => 1]);
        $temoignages = $temoignageRepository->findThreeLastTemoignages();
        $this->Newsletter();

        //Extras
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        
        return $this->render('home/index.html.twig',[
            'headers' => $headers,
            'categories' => $categories,
            'bestProducts' => $best_products,
            'temoignages' => $temoignages,
            'cart' => $cart
        ]);
    }

    //NewsLetter
    public function Newsletter()
    {
        if(isset($_POST['submitEmail']))
        {
            //La valeur de discount doit etre un multiple de 10
            $discount = 20;
            $email = $_POST['email'];
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            $subscribe = $this->em->getRepository(Subscriber::class)->findOneBy(['email' => $email]);
            $code = uniqid("PROMO20NEW");
            $mail = new MailJet();
            
            if($user)
            {
                if($subscribe)
                {
                    $this->addFlash("warning","Cet email est dejà inscrit et abonnée à notre site !");
                    return;
                }

                $subscriber = new Subscriber();
                $promoCode = new PromoCode();

                $subscriber->setUser($user);
                $subscriber->setEmail($email);
                $promoCode->setUser($user);
                $promoCode->setCode($code);
                $promoCode->setDiscount($discount);
                $promoCode->setUsed(false);
                $promoCode->setCreatedAt(new DateTime());
                $promoCode->setSubscriber($subscriber);

                $this->em->persist($subscriber);
                $this->em->persist($promoCode);

                $this->em->flush();

                $mail->PromoCode($email,$code);
                $this->addFlash("success","Vous vous etes abonnée avec succes, verifiez votre adressse email !");
                return;

            }  

            if($subscribe)
            {
                $this->addFlash("warning","Cet email est dejà abonnée à notre site !");
                return;
            }
                    

            $subscriber = new Subscriber();
            $promoCode = new PromoCode();

            $subscriber->setEmail($email);
            $promoCode->setCode($code);
            $promoCode->setDiscount($discount);
            $promoCode->setUsed(false);
            $promoCode->setCreatedAt(new DateTime());
            $promoCode->setSubscriber($subscriber);

            $this->em->persist($subscriber);
            $this->em->persist($promoCode);

            $this->em->flush();

            $mail->PromoCode($email,$code);
            $this->addFlash("success","Vous vous etes abonnée avec succés, verifiez votre adressse email !");
        }
    }
}
