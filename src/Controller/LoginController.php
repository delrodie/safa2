<?php

namespace App\Controller;

use App\Service\GestionUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    private GestionUser $gestionUser;

    public function __construct(GestionUser $gestionUser)
    {
        $this->gestionUser = $gestionUser;
    }

    #[Route('/security', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        //Initialisation du compte User
        if ($this->gestionUser->initialisation())
            $this->addFlash('success', "L'utilisateur FAMILLE a été ajouté avec succès!");

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    #[Route('/logout', name:'app_logout', methods:['GET'])]
    public function logout()
    {
        throw new \Exception('Deconnexion');
    }
}
