<?php

namespace App\Controller;

use App\Entity\Scrutin;
use App\Form\ScrutinType;
use App\Repository\ScrutinRepository;
use App\Service\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/scrutin')]
class BackendScrutinController extends AbstractController
{
    private Utility $utility;

    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    #[Route('/', name: 'app_backend_scrutin_index', methods: ['GET','POST'])]
    public function index(Request $request, ScrutinRepository $scrutinRepository): Response
    {
        $scrutin = new Scrutin();
        $form = $this->createForm(ScrutinType::class, $scrutin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->utility->slugify($scrutin->getNom());
            if ($scrutinRepository->findOneBy(['slug' => $slug])){
                $this->addFlash('danger', "Attention ce scrutin de vote a dangé été enregistré!");
                return $this->redirectToRoute('app_backend_scrutin_index',[],Response::HTTP_SEE_OTHER);
            }

            $scrutin->setSlug($slug);
            $scrutinRepository->save($scrutin, true);

            $this->addFlash('success', "Le scrutin a bien été enregistré avec succès!");

            return $this->redirectToRoute('app_backend_scrutin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_scrutin/index.html.twig', [
            'scrutins' => $scrutinRepository->findBy([],['date' => "DESC"]),
            'scrutin' => $scrutin,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_backend_scrutin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ScrutinRepository $scrutinRepository): Response
    {
        $scrutin = new Scrutin();
        $form = $this->createForm(ScrutinType::class, $scrutin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scrutinRepository->save($scrutin, true);

            return $this->redirectToRoute('app_backend_scrutin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_scrutin/new.html.twig', [
            'scrutin' => $scrutin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_scrutin_show', methods: ['GET'])]
    public function show(Scrutin $scrutin): Response
    {
        return $this->render('backend_scrutin/show.html.twig', [
            'scrutin' => $scrutin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_scrutin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Scrutin $scrutin, ScrutinRepository $scrutinRepository): Response
    {
        $form = $this->createForm(ScrutinType::class, $scrutin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scrutin->setSlug($this->utility->slugify($scrutin->getNom()));
            $scrutinRepository->save($scrutin, true);

            $this->addFlash('success', "Le scrutin ".$scrutin->getNom()." a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_scrutin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend_scrutin/edit.html.twig', [
            'scrutin' => $scrutin,
            'form' => $form,
            'scrutins' => $scrutinRepository->findBy([],['date'=>"DESC"])
        ]);
    }

    #[Route('/{id}', name: 'app_backend_scrutin_delete', methods: ['POST'])]
    public function delete(Request $request, Scrutin $scrutin, ScrutinRepository $scrutinRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$scrutin->getId(), $request->request->get('_token'))) {
            $scrutinRepository->remove($scrutin, true);
        }

        return $this->redirectToRoute('app_backend_scrutin_index', [], Response::HTTP_SEE_OTHER);
    }
}
