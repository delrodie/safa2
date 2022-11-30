<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use App\Service\GestionMedia;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/candidat')]
class BackendCandidatController extends AbstractController
{
    private Utility $utility;
    private GestionMedia $gestionMedia;

    public function __construct(Utility $utility, GestionMedia $gestionMedia)
    {
        $this->utility = $utility;
        $this->gestionMedia = $gestionMedia;
    }

    #[Route('/', name: 'app_backend_candidat_index', methods: ['GET','POST'])]
    public function index(Request $request, CandidatRepository $candidatRepository): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generation du slug
            $candidat->setSlug($this->utility->slugify($candidat->getNom()));
            // Gestion des media
            if ($form->get('media')->getData()){
                $media = $this->gestionMedia->upload($form->get('media')->getData(), "candidat");
                $candidat->setMedia($media);
            }

            $candidatRepository->save($candidat, true);

            $this->addFlash('success', "Candidat ".$candidat->getNom()." enregistré avec succès!");

            return $this->redirectToRoute('app_backend_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_candidat/index.html.twig', [
            'candidats' => $candidatRepository->findAll(),
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_backend_candidat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CandidatRepository $candidatRepository): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatRepository->save($candidat, true);

            return $this->redirectToRoute('app_backend_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_candidat_show', methods: ['GET'])]
    public function show(Candidat $candidat): Response
    {
        return $this->render('backend_candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_candidat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generation du slug
            $candidat->setSlug($this->utility->slugify($candidat->getNom()));

            // Gestion des media
            if ($form->get('media')->getData()){
                $media = $this->gestionMedia->upload($form->get('media')->getData(), 'candidat');

                if ($candidat->getMedia())
                    $this->gestionMedia->removeUpload($candidat->getMedia(), "candidat");

                $candidat->setMedia($media);
            }

            $candidatRepository->save($candidat, true);

            $this->addFlash('success', "Le candidat ".$candidat->getNom()." a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
            'candidats' => $candidatRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_backend_candidat_delete', methods: ['POST'])]
    public function delete(Request $request, Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->request->get('_token'))) {
            $candidatRepository->remove($candidat, true);
            // Suppression du media
            if ($candidat->getMedia())
                $this->gestionMedia->removeUpload($candidat->getMedia(), "candidat");

            $this->addFlash("success", "Le candidat ".$candidat->getNom()." a été supprimé avec succès!");
        }

        return $this->redirectToRoute('app_backend_candidat_index', [], Response::HTTP_SEE_OTHER);
    }
}
