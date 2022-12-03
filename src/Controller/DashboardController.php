<?php

namespace App\Controller;

use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    private Utility $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        $rangs = $this->utility->classement(); //dd($rangs);
        if (!$rangs) $rangs = $this->utility->classementDernierConcours();

        return $this->render('backend/dashboard.html.twig',[
            'rangs' => $rangs
        ]);
    }

    #[Route('/finale', name: 'app_dashboard_finale')]
    public function finale()
    {
        //dd($this->utility->classementFinale());
        return $this->render('backend/dashboard_finale.html.twig',[
            'rangs' => $this->utility->classementFinale(),
        ]);
    }
}
