<?php

namespace App\Controller;

use App\Entity\Concours;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/ajax')]
class AjaxVoteController extends AbstractController
{
    private Utility $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_ajax_anomalie', methods: ['GET'])]
    public function anomalie(): JsonResponse
    {
        return $this->json($this->utility->listAnomalie());
    }

    #[Route('/{slug}', name: 'app_ajax_vote', methods: ['GET'])]
    public function index(Request $request, Concours $concours): JsonResponse
    {
        return $this->json($this->utility->listVoteParConours($concours));
    }
}
