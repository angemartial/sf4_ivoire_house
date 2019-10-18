<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegisterType;
use App\Entity\UpdatePassword;
use App\Form\UpdatePasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /** permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * @return Response
     */


    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();        
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }


    /**
     * permet de se deconnecter
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */

    public function logout(){}
    

    /**
     * permet de créer un formulaire d'inscription
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request,ObjectManager $manager, UserPasswordEncoderInterface $encoder){

        $user = new User();

        
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success', "Votre inscription a bien été enregistré ! vous pouvez vous connectez !");
                return $this->redirectToRoute('account_login');
        }


        return $this->render('account/register.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher et de modifier le formualaire des utilisateurs
     * 
     * @IsGranted("ROLE_USER") 
     * @Route("/account/profile", name="account_profile")
     *
     * @return Response
     */
    public function profile(Request $request,ObjectManager $manager){

        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'les modifications ont bien été enregistrées'
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * permet de modifier le mot de passe
     * 
     * @IsGranted("ROLE_USER")
     * @Route("/account/update-password", name="account_password")
     *
     * @return Response
     */
    public function updatePassword(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder){

        $updatePassword = new UpdatePassword();

        // l'utilisateur actuel qui fait la mise à jour de son mot de passe

        $user = $this->getUser();

        $form = $this->createForm(UpdatePasswordType::class, $updatePassword);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //1. vérifier que l'ancien mot de passe correspond
            if(!password_verify($updatePassword->getOldPassword(), $user->getHash())){
                // gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous 
                avez tapé n'est pas votre mot de passe actuel"));
            } else{
                $newPassword = $updatePassword->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié !'
                );

                return $this->redirectToRoute('homepage');
            }

           
        }

        return $this->render('account/update-password.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * permet d'affiche le profil d'un utilisateur
     * 
     * @IsGranted("ROLE_USER")
     * @Route("/account", name="account_index")
     *
     * @return Response
     */
    public function myAccount() {
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);

    }


    /**
     * permet d'affichier la liste des reservations
     *
     * @Route("/account/bookings", name="account_bookings")
     * 
     * @return Response
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }
}
