<?php

namespace App\Controller;

use App\Entity\Cursos;
use App\Form\CursosType;
use App\Repository\CursosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cursos')]
class CursosController extends AbstractController
{
    #[Route('/', name: 'app_cursos_index', methods: ['GET'])]
    public function index(CursosRepository $cursosRepository): Response
    {
        return $this->render('cursos/index.html.twig', [
            'cursos' => $cursosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cursos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $curso = new Cursos();
        $form = $this->createForm(CursosType::class, $curso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($curso);
            $entityManager->flush();

            return $this->redirectToRoute('app_cursos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cursos/new.html.twig', [
            'curso' => $curso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cursos_show', methods: ['GET'])]
    public function show(Cursos $curso): Response
    {
        return $this->render('cursos/show.html.twig', [
            'curso' => $curso,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cursos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cursos $curso, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CursosType::class, $curso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cursos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cursos/edit.html.twig', [
            'curso' => $curso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cursos_delete', methods: ['POST'])]
    public function delete(Request $request, Cursos $curso, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$curso->getId(), $request->request->get('_token'))) {
            $entityManager->remove($curso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cursos_index', [], Response::HTTP_SEE_OTHER);
    }
}
