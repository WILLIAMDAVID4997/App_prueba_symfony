<?php

namespace App\Controller;

use App\Entity\Cursos;
use App\Repository\AlumnosRepository;
use App\Repository\CursosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Alumnos;

class MatriculacionController extends AbstractController
{
    #[Route('/matriculados', name: 'app_matriculados')]
    public function index(AlumnosRepository $alumnosRepository): Response
    {

        //Obtener todos los alumnos de la base de datos
        $alumnos = $alumnosRepository->findAll();

        // Crear un array para almacenar los datos a mostrar
        $datosMostrar = [];

        // Recorrer cada alumno para obtener sus cursos matriculados
        foreach ($alumnos as $alumno) {
            $nombreAlumno = $alumno->getNombre(); // Obtener el nombre del alumno

            // Obtener los cursos matriculados por el alumno
            $cursosMatriculados = [];
            $cursos = $alumno->getCursos();
            foreach ($cursos as $curso) {
                $cursosMatriculados[] = $curso->getNombre(); // Obtener el nombre del curso
            }

            // Convertir el array de cursos a una cadena separada por comas
            $cursoMatriculado = implode(', ', $cursosMatriculados);

            // Agregar los datos del alumno al array de datos a mostrar
            $datosMostrar[] = [
                'alumno' => $nombreAlumno,
                'curso_matriculado' => $cursoMatriculado
            ];
        }

            // Renderizar la plantilla Twig con los datos a mostrar
            return $this->render('matriculacion/index.html.twig', [
                'datosMostrar' => $datosMostrar,
            ]);
    }

    #[Route('/matricular/curso/{id}', name: 'app_cursos_matricular', methods: ['GET', 'POST'])]
    public function matricularCursos(Request $request, Cursos $curso, EntityManagerInterface $entityManager, AlumnosRepository $alumnosRepository): Response
    {
        $alumnosDisponibles = $alumnosRepository->findAlumnosNoMatriculados();

        $form = $this->createFormBuilder()
            ->add('alumnos', EntityType::class, [
                'class' => Alumnos::class,
                'choices' => $alumnosDisponibles,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione uno o más alumnos',
                'multiple' => true,
                'required' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Matricular'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $alumnos = $data['alumnos'];

            foreach ($alumnos as $alumno) {
                $curso->addAlumno($alumno);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_cursos_index');
        }

        return $this->render('matriculacion/matricular.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/matricular/alumnos/{id}', name: 'app_alumnos_matriculados', methods: ['GET', 'POST'])]
    public function matricularAlumnos(Request $request, Alumnos $alumnos, EntityManagerInterface $entityManager, CursosRepository $cursosRepository): Response
    {
        $alumnosDisponibles = $cursosRepository->findAll();

        $form = $this->createFormBuilder()
            ->add('alumnos', EntityType::class, [
                'class' => Cursos::class,
                'choices' => $alumnosDisponibles,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione uno o más alumnos',
                'multiple' => true,
                'required' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Matricular'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $cursos = $data['alumnos'];

            foreach ($cursos as $curso) {
                $alumnos->addCurso($curso);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_cursos_index');
        }

        return $this->render('matriculacion/matricular.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
