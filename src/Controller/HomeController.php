<?php

namespace App\Controller;

use App\Entity\RegistroAlimento;
use App\Repository\RegistroAlimentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use DateInterval;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(RegistroAlimentoRepository $registroAlimentoRepository): Response
    {
        // Obtener el usuario actualmente autenticado
        $usuario = $this->getUser();
        
        // Obtener la fecha de hoy
        $hoy = new DateTime();
        
        // Obtener la fecha de mañana (para filtrar registros añadidos hoy)
        $mañana = (new DateTime())->add(new DateInterval('P1D'));
        
        // Obtener todos los registros de alimentos del usuario añadidos hoy
        $registros = $registroAlimentoRepository->findBy([
            'usuario' => $usuario,
            'fecha' => $hoy,
        ]);
        
        // Obtener las calorías totales consumidas
        $totalCalorias = $registroAlimentoRepository->findTotalCaloriasByUsuario($usuario);
        $registros = $registroAlimentoRepository->findByDateAndUser($usuario, $hoy);


        // Renderizar la página de inicio y pasar los datos a la plantilla Twig
        return $this->render('home/prueba.html.twig', [
            'registros' => $registros,
            'totalCalorias' => $totalCalorias,
        ]);
    }
}