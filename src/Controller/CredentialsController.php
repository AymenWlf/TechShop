<?php

namespace App\Controller;

use App\Class\MailJet;
use App\Entity\CartItem;
use App\Entity\Confirmation;
use App\Entity\ModifPassword;
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
            //Remplissage du ModifPass
            $token = uniqid();
            $date = new DateTime();
            $strDate = $date->format("d/m/Y");
            $modifPass = new ModifPassword();
            $modifPass->setUser($user);
            $modifPass->setToken($token);
            $modifPass->setCreatedAt($strDate);
            $modifPass->setDateTime($date);
            $modifPass->setTry(0);
            
            //FLUSH
            $this->em->persist($modifPass);
            $this->em->flush();

            //Envoie de mail de confirmation
            $mail = new MailJet();
            $content = "<a href='https://aymenwlf.me/account/credentials/modif/".$token."' style ='color: #fff;text-decoration: none;'>Confirmer ma demande</a>";
            $mail->CredentialsModifyConfirmation($userEmail,$userName,$content);

            //Notification
            $this->addFlash('success',"Un email de confirmation vient vous etre envoyer !");
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

    //Modifier les informations
    #[Route('/account/credentials/modif/{token}', name: 'credentials_modif')]
    public function modif(Request $request, UserPasswordEncoderInterface $encoder,$token): Response
    {
        //Initialisation
        $user = $this->getUser();

        // dd($user);
        //Recuperation du modifPass
        $modifPass = $this->em->getRepository(ModifPassword::class)->findOneBy(['token' => $token]);
        
        //Verification de s'il y a un modifPass
        if (!$modifPass) {
            $this->addFlash('erreur',"Veuillez refaire une demande de modification de vos informations personnel");
            return $this->redirectToRoute('credentials');
        }

        //Essais
        $try = $modifPass->getTry(); 

        //Time Now
        $now = new DateTime();

        //On ajoute 30 min aux temps de demande de recuperation
        $reset_time = $modifPass->getDateTime()->modify('+ 30 minutes');
        
        // dd($user);
        //Verification si le temps est ecoulée
        if ($now > $reset_time) {

            //notif
            $this->addFlash('warning',"Temps ecouler, veuillez refaire une demande de modifications de vos informations personnel");

            return $this->redirectToRoute('credentials');
        }

        //Changer dateTime de modifPass pour annulée l'access la quatrieme fois
        if ($try < 3) {
            $try++;
            $modifPass->setTry($try);
            $this->em->flush();

        }else{
            $newTime = $now->modify('- 60 minutes');
            $modifPass->setDateTime($newTime);
            $this->em->flush();

            //notif
            $this->addFlash('warning',"Temps ecouler, veuillez refaire une demande de modifications de vos informations personnel");

            return $this->redirectToRoute('credentials');
        }
        //Creation du formulaire
        $form = $this->createForm(ModifInfosType::class,$user);

        //Envoie du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Recuperation de la data
            $data = $form->getData();

            //Verification si l'email existe dejà
            $searchUser = $this->em->getRepository(User::class)->findOneBy(['email' => $data->getEmail()]);

            if ($searchUser && $searchUser->getEmail() != $this->getUser()->getEmail()) {
                //Notif
                $this->addFlash('erreur',"Cet email n'est pas disponible");

            }else if (!$searchUser || $searchUser->getEmail() == $this->getUser()->getEmail()) {
                //Cryptage password
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $this->em->flush();

                //Notif
                $this->addFlash('success',"Vos modifiations ont confirmer et valider avec success");

                return $this->redirectToRoute('credentials');
            }
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

        //Verification si l'utilisateur est connecter
        if ($user) {

            //Notification
            $this->addFlash('warning',"Vous etes dejà connecter !!");

            //Redirection a HomePage
            return $this->redirectToRoute('home');
        
        }
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
                $this->addFlash('erreur',"Il n y'a aucun utilisateur enregistrer avec cet email !");

                return $this->redirectToRoute('pass_reset');
            }else{                
                
                //notif 
                $this->addFlash('success',"Verifier votre boite email, et suivez les etapes indiquer");

                //Mail avec lien
                $userResetName = $user_reset->getPseudoName();
                $date = new DateTime();
                $dateTime = $date->format("d/m/Y");
                $token = uniqid();
                $resetPass = new ResetPassword;
                $resetPass->setUser($user_reset);
                $resetPass->setToken($token);
                $resetPass->setCreatedAt($dateTime);
                $resetPass->setDateTime($date);
                $resetPass->setTry(0);
                $this->em->persist($resetPass);
                $this->em->flush();

                //Email de confirmation
                $url = $this->generateUrl("pass_update",[
                    'token' => $token
                ]);
                
                $content = "<a href='https://aymenwlf.me/credentials/pass_update/".$token."'>mettre à jour mon mot de passe</a>";
                $mail = new MailJet();
                $mail->ResetPasswordConfirmation($email,$userResetName,$content);

                

                // //A changer !!!!!!!!!!!
                // return $this->redirectToRoute("pass_update",[
                //     'token' => $token
                // ]);
                
                return $this->redirectToRoute("pass_reset");


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

    // //A CHANGER !! par une redirection direct par email!!!!!!!!!
    // #[Route('/credentials/pass_update', name: 'passUpdateLink')]
    // public function passUpdateLink(Request $request,$token,UserPasswordEncoderInterface $encoder): Response
    // {
    //     $this->;
    // }  


    //Update du password // Ahouter
    #[Route('/credentials/pass_update/{token}', name: 'pass_update')]
    public function update(Request $request,$token,UserPasswordEncoderInterface $encoder): Response
    {
        //Verification si l'utilisateur et econnecter
        $user = $this->getUser();
        if ($user) {

            //Notification
            $this->addFlash('warning',"Vous etes dejà connecter !!");

            //Redirection a HomePage
            return $this->redirectToRoute('home');
        
        }
        //Recuperation du resetPass
        $resetPass = $this->em->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);
        
        //Verification de s'il y a une resetPass
        if (!$resetPass) {
            $this->addFlash('erreur',"Veuillez redemander une récupération du mot de passe");
            return $this->redirectToRoute('pass_reset');
        }
        //Essais
        $try = $resetPass->getTry(); 
        //Time Now
        $now = new DateTime();
        //On ajoute 30 min aux temps de demande de recuperation
        $reset_time = $resetPass->getDateTime()->modify('+ 30 minutes');

        //Verification si le temps est ecoulée
        if ($now > $reset_time) {

            //notif
            $this->addFlash('warning',"Temps ecouler, veuillez redemander une récupération du mot de passe");

            return $this->redirectToRoute('pass_reset');
        }

        //Changer dateTime de resetPass pour annulée l'access la deuxieme fois
        if ($try < 3) {
            $try++;
            $resetPass->setTry($try);
            $this->em->flush();
        }else{
            $newTime = $now->modify('- 60 minutes');
            $resetPass->setDateTime($newTime);
            $this->em->flush();

            //notif
            $this->addFlash('erreur',"Temps ecouler, veuillez redemander une récupération du mot de passe");

            return $this->redirectToRoute('pass_reset');
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

            //Notif
            $this->addFlash('success',"Votre mot de passe est changer avec success !");

            return $this->redirectToRoute('app_login');

        }

        // Extras
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
