<?php

namespace App\Controller;

use App\Repository\FamilleRepository;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private FamilleRepository $familleRepository;
    private Utility $utility;

    public function __construct(FamilleRepository $familleRepository, Utility $utility)
    {
        $this->familleRepository = $familleRepository;
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'familles' => $this->utility->listFamilleByConcoursActif(),
        ]);
    }

    #[Route('/recherche', name: 'app_recherche', methods:['GET','POST'])]
    public function recherche(Request $request, FamilleRepository $familleRepository): Response
    {
        $couple = $request->request->get('_recherche');
        if ($couple and $request->request->get('_csrf_token')) { //dd($couple);
            $famille = $familleRepository->findOneBy(['nom' => $couple]); //dd($famille);
            return $this->redirectToRoute('app_vote_show',['slug' => $famille->getSlug()]);
        }

        return $this->render('home/recherche.html.twig',[
            'familles' => $familleRepository->findByConcoursActif()
        ]);
    }
}
