<?php

namespace App\Controller;

use App\Entity\Fainaliste;
use App\Repository\FainalisteRepository;
use App\Service\Utility;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/finale-2022222')]
class FinaleController extends AbstractController
{
    private Utility $utility;
    private FainalisteRepository $finalisteRepository;

    public function __construct(Utility $utility, FainalisteRepository $finalisteRepository)
    {
        $this->utility = $utility;
        $this->finalisteRepository = $finalisteRepository;
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/', name: 'app_finale_index', methods: ['GET','POST'])]
    public function index(): Response
    {
        return $this->render('finale/listes.html.twig',[
            'finalistes' => $this->utility->listeFinaliste(),
        ]);
    }

    #[Route('/{slug}', name:'app_finale_vote', methods:['GET','POST'])]
    public function vote(Request $request, Fainaliste $finaliste)
    {
        $telephone = $request->request->get('_telephone_finale');
        if ($telephone && $request->request->get('_csrf_token')){
            if($this->utility->voteFinale($finaliste, $telephone)){
                $this->addFlash('success', "Votre vote a été effectué avec succès!");
                return $this->redirectToRoute('app_finale_index',[], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', "Vous avez déjà effectué un vote avec le numéro de telephone");
        }
        return $this->render('finale/show.html.twig',[
            'finaliste' => $finaliste,
            'affichage_bouton' => true,
            'rangs' => []
        ]);
    }

}
