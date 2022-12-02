<?php

namespace App\Controller;

use App\Entity\Fainaliste;
use App\Form\FainalisteType;
use App\Repository\FainalisteRepository;
use App\Service\GestionMedia;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/finaliste')]
class BackendFinalisteController extends AbstractController
{
    private Utility $utility;
    private GestionMedia $gestionMedia;

    public function __construct(Utility $utility, GestionMedia $gestionMedia)
    {
        $this->utility = $utility;
        $this->gestionMedia = $gestionMedia;
    }

    #[Route('/', name: 'app_backend_finaliste_index', methods: ['GET', 'POST'])]
    public function index(Request $request, FainalisteRepository $fainalisteRepository): Response
    {
        $fainaliste = new Fainaliste();
        $form = $this->createForm(FainalisteType::class, $fainaliste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generation slug
            $fainaliste->setSlug($this->utility->slugify($fainaliste->getNom()));
            // Gestion des media
            if ($form->get('media')->getData()){
                $media = $this->gestionMedia->upload($form->get('media')->getData(), "finaliste");
                $fainaliste->setMedia($media);
            }

            $fainalisteRepository->save($fainaliste, true);

            $this->addFlash('success', "Le finaliste a bien été enregistré");

            return $this->redirectToRoute('app_backend_finaliste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_finaliste/index.html.twig', [
            'fainalistes' => $fainalisteRepository->findAll(),
            'fainaliste' => $fainaliste,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_backend_finaliste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FainalisteRepository $fainalisteRepository): Response
    {
        $fainaliste = new Fainaliste();
        $form = $this->createForm(FainalisteType::class, $fainaliste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fainalisteRepository->save($fainaliste, true);

            return $this->redirectToRoute('app_backend_finaliste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_finaliste/new.html.twig', [
            'fainaliste' => $fainaliste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_finaliste_show', methods: ['GET'])]
    public function show(Fainaliste $fainaliste): Response
    {
        return $this->render('backend_finaliste/show.html.twig', [
            'fainaliste' => $fainaliste,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_finaliste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fainaliste $fainaliste, FainalisteRepository $fainalisteRepository): Response
    {
        $form = $this->createForm(FainalisteType::class, $fainaliste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generation slug
            $fainaliste->setSlug($this->utility->slugify($fainaliste->getNom()));
            // Gestion des media
            if ($form->get('media')->getData()){
                $media = $this->gestionMedia->upload($form->get('media')->getData(), "finaliste");

                if ($fainaliste->getMedia())
                    $this->gestionMedia->removeUpload($fainaliste->getMedia(), 'finaliste');

                $fainaliste->setMedia($media);
            }

            $fainalisteRepository->save($fainaliste, true);

            $this->addFlash('success', "Le finaliste ".$fainaliste->getNom()." a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_finaliste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_finaliste/edit.html.twig', [
            'fainaliste' => $fainaliste,
            'form' => $form,
            'fainalistes' => $fainalisteRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_backend_finaliste_delete', methods: ['POST'])]
    public function delete(Request $request, Fainaliste $fainaliste, FainalisteRepository $fainalisteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fainaliste->getId(), $request->request->get('_token'))) {
            $fainalisteRepository->remove($fainaliste, true);

            if ($fainaliste->getMedia())
                $this->gestionMedia->removeUpload($fainaliste->getMedia(), "finaliste");

            $this->addFlash('success', "Le finaliste ".$fainaliste->getNom()." a été supprimé avec succès!");
        }

        return $this->redirectToRoute('app_backend_finaliste_index', [], Response::HTTP_SEE_OTHER);
    }
}
