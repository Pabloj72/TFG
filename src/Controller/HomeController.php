<?php

namespace App\Controller;

use App\Entity\RegistroAlimento;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // Obtener el usuario actualmente autenticado
        $usuario = $this->getUser();
        $fechaActual = new \DateTime();
        
        // Obtener el repositorio de la entidad RegistroAlimento
        $registroAlimentoRepository = $this->entityManager->getRepository(RegistroAlimento::class);
        
        // Obtener los registros de alimentos para el día actual
        $registros = $registroAlimentoRepository->findBy([
            'usuario' => $usuario,
            'fecha' => $fechaActual
        ]);
        
        // Obtener las calorías consumidas por el usuario actual
        $totalCalorias = $registroAlimentoRepository->findTotalCaloriasByUsuarioAndFecha($usuario, $fechaActual);
        
        // Renderizar la página de inicio y pasar los datos a la plantilla Twig
        return $this->render('home/prueba.html.twig', [
            'registros' => $registros,
            'totalCalorias' => $totalCalorias,
        ]);
    }
}
