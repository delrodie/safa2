<?php

namespace App\Controller;

use App\Service\Utility;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/election')]
class BackendElectionController extends AbstractController
{
    private Utility $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_backend_election')]
    public function index(): Response
    {
        return $this->render('backend/election.html.twig',[
            'datas' => $this->utility->resultatElection(),
            'totalVotant' => $this->utility->nombreVotantElection()
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_backend_election_trucate')]
    public function table()
    {
        $this->utility->videElection();

        return $this->render("backend/dashboard.html.twig");
    }
}
