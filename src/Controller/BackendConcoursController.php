<?php

namespace App\Controller;

use App\Entity\Concours;
use App\Form\ConcoursType;
use App\Repository\ConcoursRepository;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/concours')]
class BackendConcoursController extends AbstractController
{
    private Utility $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_backend_concours_index', methods: ['GET','POST'])]
    public function index(Request $request, ConcoursRepository $concoursRepository): Response
    {
        $concour = new Concours();
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $concour->setSlug($this->utility->slugify($concour->getNom())); // Slug du concours
            $this->utility->codeConcours($concour); // Generation du code du concours
            $concoursRepository->save($concour, true);

            $this->addFlash('success', "Le concours ".$concour->getNom()." a été crée avec succès!");

            return $this->redirectToRoute('app_backend_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_concours/index.html.twig', [
            'concours' => $concoursRepository->findAll(),
            'concour' => $concour,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_backend_concours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConcoursRepository $concoursRepository): Response
    {
        $concour = new Concours();
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $concoursRepository->save($concour, true);

            return $this->redirectToRoute('app_backend_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_concours/new.html.twig', [
            'concour' => $concour,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_backend_concours_show', methods: ['GET'])]
    public function show(Concours $concour): Response
    {
        //dd(count($concour->getVotes()));
        return $this->render('backend_concours/show.html.twig', [
            'concour' => $concour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_concours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Concours $concour, ConcoursRepository $concoursRepository): Response
    {
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $concour->setSlug($this->utility->slugify($concour->getNom())); // Creation du slug
            $concoursRepository->save($concour, true);

            $this->addFlash('success', "Le concours ".$concour->getNom()." a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_concours/edit.html.twig', [
            'concour' => $concour,
            'form' => $form,
            'concours' => $concoursRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'app_backend_concours_delete', methods: ['POST'])]
    public function delete(Request $request, Concours $concour, ConcoursRepository $concoursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$concour->getId(), $request->request->get('_token'))) {
            $concoursRepository->remove($concour, true);
        }

        return $this->redirectToRoute('app_backend_concours_index', [], Response::HTTP_SEE_OTHER);
    }
}
