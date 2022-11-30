<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Repository\FamilleRepository;
use App\Repository\VoteRepository;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vote')]
class VoteController extends AbstractController
{
    private FamilleRepository $familleRepository;
    private Utility $utility;
    private VoteRepository $voteRepository;

    public function __construct(FamilleRepository $familleRepository, Utility $utility, VoteRepository $voteRepository)
    {
        $this->familleRepository = $familleRepository;
        $this->utility = $utility;
        $this->voteRepository = $voteRepository;
    }

    #[Route('/', name: 'app_vote', methods: ['GET','POST'])]
    public function index(Request $request): Response
    {

        $session = $request->getSession();
        if (!$session->get('famille'))
            return $this->redirectToRoute('app_home');
        else
            $famille = $this->familleRepository->findOneBy(['slug' => $session->get('famille')]);

        $telephone = $request->request->get('_telephone') ;
        if ($telephone and $request->request->get('_csrf_token')){
                $session->set('telephone', $telephone);

            return $this->redirectToRoute('app_vote_show', ['slug' => $famille->getSlug()], Response::HTTP_SEE_OTHER);

        }

        return $this->render('vote/index.html.twig', [
            'famille' => $famille,
        ]);
    }

    #[Route('/{slug}', name:'app_vote_show', methods:['GET','POST'])]
    public function vote(Request $request, Famille $famille): Response
    {

        $session = $request->getSession();
        // Affectation de la famille à la session
        $session->set('famille', $famille->getSlug());

        if (!$session->get('telephone'))
            return $this->redirectToRoute('app_vote', [], Response::HTTP_SEE_OTHER);

        //Affichage du bouton de vote
        if ($this->utility->verificationVote($famille, $session->get('telephone'))) {
            $this->addFlash('danger', "Désolé vous avez déjà voté à ce concours!");
            $affichageBtn = false;
            $session->clear();
        }else $affichageBtn = true;

        // Traitement du formulaire
        if ($request->request->get('_couple') and $request->request->get('_csrf_token')){

            // Gestion de la fréquence de vote par adresse IP
            if (!$this->utility->adresseIp($famille)){
                $this->addFlash('danger', "Désolé vous avez déjà voté à ce concours. Merci de reprendre dans 2H du temps!");
                $affichageBtn = false;

                return $this->redirectToRoute('app_vote_show', ['slug' => $famille->getSlug()], Response::HTTP_SEE_OTHER);
            }

            $vote = $this->utility->vote($famille, $session->get('telephone'));
            if ($vote) $this->addFlash('success', "Votre vote a été effectué avec succès!");
            else $this->addFlash('danger', 'Désolé vous avez déjà voté');

            $affichageBtn = false;

            $session->clear();

            return $this->redirect('https://www.facebook.com/safa.edition3');
        }

        return $this->render('vote/couple.html.twig',[
            'famille' => $famille,
            'affichage_bouton' => $affichageBtn,
            'rangs' => $this->utility->classement()
        ]);
    }
}
