<?php

namespace App\Controller;

use App\Class\MailJet;
use App\Entity\CartItem;
use App\Entity\Confirmation;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ModifInfosType;
use App\Form\RegisterType;
use App\Form\ResetPassType;
use App\Form\UpdatePassType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CredentialsController extends AbstractController
{
    //Entity Manager
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Page Des infos personnel
    #[Route('/account/credentials', name: 'credentials')]
    public function index(): Response
    {
        //Initialisation
        $user = $this->getUser();
        $userEmail = $user->getEmail();
        $userName = $user->getPseudoName();

        if (isset($_POST['modify'])) {
            //Envoyer mail de confirmation
            $token = uniqid("0000");
            $date = new DateTime();
            $conf = $user->getConfirmation();
            $conf->setCredConf(0);
            $conf->setCredTime($date);
            $conf->setUser($user);
            $this->em->persist($conf);
            $this->em->flush();

            $mail = new MailJet();
            $mail->CredentialsModifyConfirmation($userEmail,$userName);

            //Notification
        }

        //Extras
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

    //Page Des infos personnel
    #[Route('/account/credentials-conf', name: 'credentials_conf')]
    public function CredConf(): Response
    {
        //Initialisation
        $user = $this->getUser();
        $date = new DateTime();
        //Temps ecouler ou non
        $confUser = $this->em->getRepository(Confirmation::class)->findOneBy(['user' => $user]);
        if ($confUser->getCredTime()->modify("+ 30 minutes") > $date ) {

            //Notification temps ecoule

            return $this->redirectToRoute("credentials");
        }
        $confUser->setCredConf(1);
        $this->em->flush();

        return $this->redirectToRoute("credentials_modif");
    }

    //Modifier les informations
    #[Route('/account/credentials/modif', name: 'credentials_modif')]
    public function modif(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        //Ajouter notif tel que confirmer votre adresse email pour modifier...
        //mail dirige vers ce lien
        $user = $this->getUser();
        $form = $this->createForm(ModifInfosType::class,$user);
        //Annulee toute modification par la suite
        $date = new DateTime();
        $conf = $user->getConfirmation();
        $conf->setCredConf(0);
        $conf->setCredTime($date->modify("+ 30 minutes"));

        //Envoie du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Recuperation de la data
            $user = $form->getData();
            //Cryptage password
            $password = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);
            $this->em->flush();
            return $this->redirectToRoute('credentials');
        }

        //Extras
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

    //Recuperation du mot de passe
    #[Route('/credentials/pass_reset', name: 'pass_reset')]
    public function reset(Request $request): Response
    {
        //Initialisation
        $user = $this->getUser();
        $form = $this->createForm(ResetPassType::class,$user);
        //Envoie du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Recuperation de la data
            $data = $form->getData();
            $email = $data->getEmail();
            $user_reset = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            //Verification du user
            if (!$user_reset) {

                //notif

                return $this->redirectToRoute('pass_reset');
            }else{
                //Email de confirmation

                //notif 

                //Mail avec lien
                $date = new DateTime();
                $dateTime = $date->format("d/m/Y");
                $token = uniqid();
                $resetPass = new ResetPassword;
                $resetPass->setUser($user_reset);
                $resetPass->setToken($token);
                $resetPass->setCreatedAt($dateTime);
                $resetPass->setDateTime($date);
                $this->em->persist($resetPass);
                $this->em->flush();

                //URL
                $url = $this->generateUrl("pass_update",[
                    'token' => $token
                ]);

                //A changer !!!!!!!!!!!
                return $this->redirectToRoute("pass_update",[
                    'token' => $token
                ]);
                
                return $this->redirectToRoute("home");


            }
        }

        //Extras
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        return $this->render('credentials/reset.html.twig',[
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }  

    //Update du password
    #[Route('/credentials/pass_update/{token} ', name: 'pass_update')]
    public function update(Request $request,$token,UserPasswordEncoderInterface $encoder): Response
    {
        $resetPass = $this->em->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

        if (!$resetPass) {
            $this->redirectToRoute('pass_reset');
        }

        $now = new DateTime();
        $reset_time = $resetPass->getDateTime()->modify('+ 30 minutes');

        if ($now > $reset_time) {

            //notif

            $this->redirectToRoute('pass_reset');
        }

        //Changement de mot de passe
        $user = $resetPass->getUser();
        $form = $this->createForm(UpdatePassType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $new_pwd = $data->getPassword();
            $password = $encoder->encodePassword($user,$new_pwd);
            $user->setPassword($password);
            $this->em->flush();

            return $this->redirectToRoute('app_login');

        }
        if ($this->getUser()) {
            $cart = $this->em->getRepository(CartItem::class)->findBy(['user' => $this->getUser()]);
        }else{
            $cart = null;
        }
        return $this->render('credentials/update.html.twig',[
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }  

    
}
