<?php

namespace App\Controller;

use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/safa-tour')]
class ElectionController extends AbstractController
{
    private Utility $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_election_index', methods: ['GET','POST'])]
    public function index(Request $request): Response
    {
        // Autorisation accordée uniquement aux appareils Android
        $os = substr($request->headers->get('sec-ch-ua-platform'), 1, 7);

        if ($os !== "Android") return $this->redirectToRoute('app_election_erreur');

        // Si le formulaire est soumis et contient un token
        $coupleRequest = $request->request->get('_couple');
        if ($request->getMethod() === 'POST' && $coupleRequest && $request->request->get('_csrf_token')) {

            $resultat = $this->utility->election($coupleRequest, $request->getSession()->get('scrutin'));

            if (!$resultat){
                $this->addFlash('danger', "Attention votre vote n'a pas été pris en compte. Veuillez voir les organisateurs pour restaurer la tableatte!");

                return $this->redirectToRoute('app_election_index',[], Response::HTTP_SEE_OTHER);
            }

            $message = "Votre candidat ".$resultat->getNom()." a été voté ".count($resultat->getElections())." fois aujourd'hui dans la salle";
            $this->addFlash('warning', $message);
            // Message d'erreur et de return false
            // enregistrement du vote dans la base de données
            //dd($coupleRequest);
            // message de félicitation et du nombre de voix
            // Affichage du bouton clear pour réinitialisation
            return $this->redirectToRoute('app_election_success',[], Response::HTTP_SEE_OTHER);
        }

        // Creation de session de vote s'il n'en existe pas
        if (!$request->getSession()->get('scrutin')) {
            $code = time() . '' . substr(uniqid("", true), -9, 5);
            $request->getSession()->set('scrutin', $code);
        }

        return $this->render('home/election.html.twig',[
            'datas' => $this->utility->scrutinEnCours(),
        ]);
    }

    #[Route('/success', name: 'app_election_success', methods: ['GET','POST'])]
    public function success(Request $request)
    {
        if ($request->getMethod() === 'POST' && $request->request->get('_reinitialisation') && $request->request->get('_csrf_token')){
            $request->getSession()->clear();

            return $this->redirectToRoute('app_election_index', [], Response::HTTP_SEE_OTHER);
        }
        // Recuperation des informations du couple voté dans selon l'id de l'opération
        return $this->render('home/election_success.html.twig',[
            //'couple_vote' => resultat de la réquete
        ]);
    }

    #[Route('/erreur/appareil', name:'app_election_erreur')]
    public function erreur(): Response
    {
        return $this->render('home/election_erreur.html.twig');
    }
}
