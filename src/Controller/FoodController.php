<?php
// src/Controller/FoodController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\EdamamFoodDatabaseApiService;

class FoodController extends AbstractController
{
    private $edamamApiService;

    public function __construct(EdamamFoodDatabaseApiService $edamamApiService)
    {
        $this->edamamApiService = $edamamApiService;
    }

    public function buscarPorPalabraClave(Request $request): Response
    {
        $busqueda = $request->query->get('busqueda');

        if (!$busqueda) {
            // Si no se proporciona ninguna búsqueda, mostrar la página de búsqueda
            return $this->mostrarPaginaBusqueda();
        }

        // Realizar la búsqueda utilizando la palabra clave proporcionada
        $resultado = $this->edamamApiService->buscarAlimentoPorPalabraClave($busqueda);

        // Renderizar la plantilla Twig con los datos obtenidos de la API
        return $this->render('resultado/resultado.html.twig', [
            'alimento' => $resultado['parsed'][0]['food'] // Utilizar el primer alimento encontrado en los resultados
        ]);
    }

    public function mostrarPaginaBusqueda(): Response
    {
        return $this->render('buscar/buscar_alimento_pagina.html.twig');
    }
}

