<?php

namespace App\Controller;

use App\Entity\Ejercicio;
use App\Entity\RegistroEjercicio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EjercicioController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/registro-ejercicio', name: 'registro_ejercicio')]
    public function registroEjercicio(Request $request): Response
    {
        // Obtener la lista de ejercicios disponibles
        $ejercicios = $this->entityManager->getRepository(Ejercicio::class)->findAll();

        // Manejar el formulario enviado
        if ($request->isMethod('POST')) {
            $ejerciciosSeleccionados = $request->request->get('ejercicios', []);
            $duracion = $request->request->get('duracion');
        

            // Calcular las calorías quemadas
            $caloriasQuemadas = 0;
            foreach ($ejerciciosSeleccionados as $ejercicioId) {
                $ejercicio = $this->entityManager->getRepository(Ejercicio::class)->find($ejercicioId);
                if ($ejercicio instanceof Ejercicio) {
                    $caloriasQuemadas += $ejercicio->getCaloriasPorMinuto();
                }
            }
            $caloriasQuemadas *= $duracion;

            // Guardar el registro de ejercicio
            $registroEjercicio = new RegistroEjercicio();
            $registroEjercicio->setFecha(new \DateTime());
            $registroEjercicio->setDuracionMinutos($duracion);
            foreach ($ejerciciosSeleccionados as $ejercicioId) {
                $ejercicio = $this->entityManager->getRepository(Ejercicio::class)->find($ejercicioId);
                if ($ejercicio instanceof Ejercicio) {
                    $registroEjercicio->addEjercicio($ejercicio);
                }
            }
            $registroEjercicio->setUsuario($this->getUser());

            $this->entityManager->persist($registroEjercicio);
            $this->entityManager->flush();

            // Redirigir para evitar el reenvío del formulario
            return $this->redirectToRoute('registro_ejercicio', [
                'caloriasQuemadas' => $caloriasQuemadas
            ]);
        }

        return $this->render('ejercicio/registro_ejercicio.html.twig', [
            'ejercicios' => $ejercicios,
            'caloriasQuemadas' => $request->query->getInt('caloriasQuemadas'),
        ]);
    }
}
