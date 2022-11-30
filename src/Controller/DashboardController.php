<?php

namespace App\Controller;

use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index()
    {
        return $this->render('backend/dashboard.html.twig',[
            'rangs' => $this->utility->classement()
        ]);
    }
}
