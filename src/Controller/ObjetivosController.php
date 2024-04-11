<?php
// src/Controller/ObjetivosController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObjetivosController extends AbstractController
{
    #[Route('/objetivos', name: 'app_objetivos')]
    public function index(Request $request): Response
    {
        // Obtener el usuario autenticado
        $usuario = $this->getUser();
        
        // Calcular las calorías necesarias para cada objetivo
        $caloriasDeficit = $this->calcularCaloriasNecesarias($usuario, 'deficit');
        $caloriasMantenimiento = $this->calcularCaloriasNecesarias($usuario, 'mantenimiento');
        $caloriasSuperavit = $this->calcularCaloriasNecesarias($usuario, 'superavit');

        return $this->render('objetivos/index.html.twig', [
            'caloriasDeficit' => $caloriasDeficit,
            'caloriasMantenimiento' => $caloriasMantenimiento,
            'caloriasSuperavit' => $caloriasSuperavit,
        ]);
    }

    /**
     * Calcula las calorías necesarias según los objetivos del usuario.
     */
    private function calcularCaloriasNecesarias($usuario, $objetivo)
    {
        // Obtener los datos del usuario
        $edad = $usuario->getEdad();
        $altura = $usuario->getAltura();
        $peso = $usuario->getPeso();
        $genero = $usuario->getGenero();
        $intensidadFisica = $usuario->getIntensidadFisica();

        // Calcular el factor de actividad física
        $factorActividadFisica = $this->calcularFactorActividadFisica($intensidadFisica);

        // Calcular las calorías necesarias para el objetivo específico
        switch ($objetivo) {
            case 'deficit':
                $calorias = $this->calcularCaloriasMantenimiento($edad, $altura, $peso, $genero, $factorActividadFisica) - 500;
                break;
            case 'mantenimiento':
                $calorias = $this->calcularCaloriasMantenimiento($edad, $altura, $peso, $genero, $factorActividadFisica);
                break;
            case 'superavit':
                $calorias = $this->calcularCaloriasMantenimiento($edad, $altura, $peso, $genero, $factorActividadFisica) + 500;
                break;
            default:
                $calorias = 0;
        }

        return $calorias;
    }

    /**
     * Calcula el factor de actividad física basado en la intensidad de la actividad física del usuario.
     */
    private function calcularFactorActividadFisica($intensidadFisica)
    {
        switch ($intensidadFisica) {
            case 'sedentario':
                return 1.2;
            case 'ligero':
                return 1.375;
            case 'moderado':
                return 1.55;
            case 'activo':
                return 1.725;
            case 'muy_activo':
                return 1.9;
            default:
                return 1.2; // Por defecto, asumimos actividad sedentaria
        }
    }

    /**
     * Calcula las calorías necesarias para mantener el peso del usuario.
     */
    private function calcularCaloriasMantenimiento($edad, $altura, $peso, $genero, $factorActividadFisica)
    {
        // Fórmula básica de Harris-Benedict para calcular las calorías de mantenimiento
        if ($genero === 'masculino') {
            $caloriasMantenimiento = (88.362 + (13.397 * $peso) + (4.799 * $altura) - (5.677 * $edad)) * $factorActividadFisica;
        } elseif ($genero === 'femenino') {
            $caloriasMantenimiento = (447.593 + (9.247 * $peso) + (3.098 * $altura) - (4.330 * $edad)) * $factorActividadFisica;
        } else {
            // Si el género no está definido correctamente, se utiliza un valor promedio
            $caloriasMantenimiento = ((88.362 + (13.397 * $peso) + (4.799 * $altura) - (5.677 * $edad)) +
                (447.593 + (9.247 * $peso) + (3.098 * $altura) - (4.330 * $edad))) / 2 * $factorActividadFisica;
        }

        return $caloriasMantenimiento;
    }
}
