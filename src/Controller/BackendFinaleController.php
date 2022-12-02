<?php

namespace App\Controller;

use App\Entity\Finale;
use App\Form\FinaleType;
use App\Repository\FinaleRepository;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/finale')]
class BackendFinaleController extends AbstractController
{
    private Utility $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_backend_finale_index', methods: ['GET','POST'])]
    public function index(Request $request, FinaleRepository $finaleRepository): Response
    {
        $finale = new Finale();
        $form = $this->createForm(FinaleType::class, $finale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->utility->slugify($finale->getNom());
            if ($finaleRepository->findOneBy(['slug' => $slug])){
                $this->addFlash('danger', "Attention cette finale a déjà été enregistrée");
                return $this->redirectToRoute('app_backend_finale_index',[], Response::HTTP_SEE_OTHER);
            }

            $this->utility->finaleDate($finale);
            $finaleRepository->save($finale, true);

            $this->addFlash('success', "La finale a bien été enregistrée");

            return $this->redirectToRoute('app_backend_finale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_finale/index.html.twig', [
            'finales' => $finaleRepository->findAll(),
            'finale' => $finale,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_backend_finale_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FinaleRepository $finaleRepository): Response
    {
        $finale = new Finale();
        $form = $this->createForm(FinaleType::class, $finale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $finaleRepository->save($finale, true);

            return $this->redirectToRoute('app_backend_finale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_finale/new.html.twig', [
            'finale' => $finale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_finale_show', methods: ['GET'])]
    public function show(Finale $finale): Response
    {
        return $this->render('backend_finale/show.html.twig', [
            'finale' => $finale,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_finale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Finale $finale, FinaleRepository $finaleRepository): Response
    {
        $form = $this->createForm(FinaleType::class, $finale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->utility->finaleDate($finale);

            $finaleRepository->save($finale, true);

            $this->addFlash('success', "La finale a bien été modifiée");

            return $this->redirectToRoute('app_backend_finale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_finale/edit.html.twig', [
            'finale' => $finale,
            'form' => $form,
            'finales' => $finaleRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_backend_finale_delete', methods: ['POST'])]
    public function delete(Request $request, Finale $finale, FinaleRepository $finaleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$finale->getId(), $request->request->get('_token'))) {
            $finaleRepository->remove($finale, true);
        }

        return $this->redirectToRoute('app_backend_finale_index', [], Response::HTTP_SEE_OTHER);
    }
}
