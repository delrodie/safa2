<?php

namespace App\Controller;

use App\Repository\VotantRepository;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/votant')]
class VotantController extends AbstractController
{
    #[Route('/', name: 'app_backend_votant_ajax')]
    public function ajax(Utility $utility): JsonResponse
    {
        return $this->json($utility->listeVotant());
    }

    #[Route('/list', name: 'app_backend_votant_list')]
    public function index()
    {
        return $this->render('backend/votant.html.twig');
    }
}
