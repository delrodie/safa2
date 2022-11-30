<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Form\CommuneType;
use App\Repository\CommuneRepository;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/commune')]
class BackendCommuneController extends AbstractController
{
    private Utility $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_backend_commune_index', methods: ['GET','POST'])]
    public function index(Request $request, CommuneRepository $communeRepository): Response
    {
        $commune = new Commune();
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commune->setSlug($this->utility->slugify($commune->getNom()));
            $communeRepository->save($commune, true);

            $this->addFlash('success', "La commune ".$commune->getNom()." a bien été ajoutée!");

            return $this->redirectToRoute('app_backend_commune_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_commune/index.html.twig', [
            'communes' => $communeRepository->findBy([],['nom'=>"ASC"]),
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_backend_commune_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommuneRepository $communeRepository): Response
    {
        $commune = new Commune();
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $communeRepository->save($commune, true);

            return $this->redirectToRoute('app_backend_commune_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_commune/new.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_commune_show', methods: ['GET'])]
    public function show(Commune $commune): Response
    {
        return $this->render('backend_commune/show.html.twig', [
            'commune' => $commune,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_commune_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commune $commune, CommuneRepository $communeRepository): Response
    {
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commune->setSlug($this->utility->slugify($commune->getNom()));
            $communeRepository->save($commune, true);

            $this->addFlash('success', "La commune ".$commune->getNom()." a été modifiée avec succès!");

            return $this->redirectToRoute('app_backend_commune_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_commune/edit.html.twig', [
            'commune' => $commune,
            'form' => $form,
            'communes' => $communeRepository->findBy([],['nom'=>"ASC"])
        ]);
    }

    #[Route('/{id}', name: 'app_backend_commune_delete', methods: ['POST'])]
    public function delete(Request $request, Commune $commune, CommuneRepository $communeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commune->getId(), $request->request->get('_token'))) {
            $communeRepository->remove($commune, true);
        }

        return $this->redirectToRoute('app_backend_commune_index', [], Response::HTTP_SEE_OTHER);
    }
}
