<?php

namespace App\Controller;

use App\Repository\AnomalieRepository;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/anomalie')]
class BackendAnomalieController extends AbstractController
{
    private Utility $utility;
    private AnomalieRepository $anomalieRepository;

    public function __construct(Utility $utility, AnomalieRepository $anomalieRepository)
    {
        $this->utility = $utility;
        $this->anomalieRepository = $anomalieRepository;
    }

    #[Route('/', name: 'app_backend_anomalie')]
    public function index():Response
    {
        $action = true; $i=0;
        while ($action){
            $i++;
            $this->utility->addAnomalie();
            if ($i === 5) $action = false;
        }

        return $this->render('backend/anomalie.html.twig');
    }

}
