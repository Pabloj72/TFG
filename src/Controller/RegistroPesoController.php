<?php

namespace App\Controller;

use App\Entity\RegistroPeso;
use App\Form\RegistroPesoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistroPesoController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/registro-peso', name: 'registro_peso')]
    public function nuevoRegistro(Request $request): Response
    {
        $registroPeso = new RegistroPeso();
        $registroPeso->setFechaPeso(new \DateTime()); // Establecer la fecha actual

        $formulario = $this->createForm(RegistroPesoType::class, $registroPeso);
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $registroPeso->setUsuario($this->getUser());
            $this->entityManager->persist($registroPeso);
            $this->entityManager->flush();

            $this->addFlash('success', 'Se ha registrado el peso correctamente.');

            return $this->redirectToRoute('registro_peso');
        }

        // Obtener la lista de pesos para mostrar en la misma página
        $repoRegistroPeso = $this->entityManager->getRepository(RegistroPeso::class);
        $pesos = $repoRegistroPeso->findBy(['usuario' => $this->getUser()], ['fechapeso' => 'ASC']);

        // Convertir los datos de pesos en un array
        $pesosData = [];
        foreach ($pesos as $peso) {
            $pesosData[$peso->getFechaPeso()->format('Y-m-d')] = $peso->getPeso();
        }

        return $this->render('registro_peso/peso.html.twig', [
            'formulario' => $formulario->createView(),
            'pesos' => $pesos, // Aquí estás pasando la variable `pesos`
        ]);
    }
}