<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/election')]
class ElectionController extends AbstractController
{
    #[Route('/', name: 'app_election_index', methods: ['GET','POST'])]
    public function index(Request $request): Response
    {
        // Affection de la valeur false au bouton clear
        $btnClear = false;
        // Si le formulaire est soumis et contient un token
        $coupleRequest = $request->request->get('_couple');
        if ($request->getMethod() === 'POST' && $coupleRequest && $request->request->get('_csrf_token')) {
            // Verification dans la base de données de non-existence de la session
            // Message d'erreur et de return false
            // enregistrement du vote dans la base de données

            // message de félicitation et du nombre de voix
            // Affichage du bouton clear pour réinitialisation
            return $this->redirectToRoute('app_election_success',[], Response::HTTP_SEE_OTHER);
        }

        $data=[];
        for ($i=1; $i<=5; $i++){
            $datas[]=[
                'nom' => "Couple ".$i,
                'media' => "01-domaine-1660752030-1668870841.png",
                'id' => $i,
            ];
        }

        // Creation de session de vote s'il n'en existe pas
        if (!$request->getSession('scrutin')) {
            $code = time() . '' . substr(uniqid("", true), -9, 5);
            $request->setSession('scrutin', $code);
        }

        return $this->render('home/election.html.twig',[
            'datas' => $datas,
            'btnClear' => $btnClear
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
}
