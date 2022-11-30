<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ChangePasswordController extends AbstractController
{
    #[Route('/change/password', name: 'app_change_password', methods: ['GET','POST'])]
    public function index(Request $request, AuthenticationUtils $authenticationUtils, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Verification de la connexion de l'utilisateur
        $last_username = $authenticationUtils->getLastUsername();
        if (!$last_username){
            $this->addFlash('danger', "Attention veuillez vous conecter pour changer votre mot de passe");
            return $this->redirectToRoute('app_login');
        }

        //dd($last_username);

        // Recuperation des informations du formulaires
        $username = $request->request->get('_username'); //dd($username);
        $password = $request->request->get('_password'); //dd($password);

        // Modification du mot de passe si c'est le meme utilisateur
        if ($username === $last_username and $request->request->get('_csrf_token')){
            $user = $userRepository->findOneBy(['email' => $username]); //dd($user);
            $passwordHashed = $passwordHasher->hashPassword($user, $password);

            $userRepository->upgradePassword($user, $passwordHashed);

            return $this->redirectToRoute('app_logout');
        }

        return $this->render('security/change_password.html.twig',[
            'last_username' => $last_username,
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }
}
