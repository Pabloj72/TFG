<?php
// src/Service/EdamamFoodDatabaseApiService.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class EdamamFoodDatabaseApiService
{
    private $client;
    private $baseUrl;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $baseUrl, string $apiKey)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    public function buscarAlimentoPorPalabraClave(string $palabraClave): array
    {
        $url = $this->baseUrl . '/api/food-database/v2/parser';
        
        $query = [
            'app_id' => '1fa1656f', // Reemplaza con tu ID de aplicación
            'app_key' => $this->apiKey,
            'ingr' => $palabraClave
        ];

        $response = $this->client->request('GET', $url, ['query' => $query]);

        return $response->toArray();
    }

// Método para consultar las calorías de un alimento
public function consultarCalorias(string $alimento, float $cantidadGramos): int
{
    // Realizar la consulta a la API de Edamam para obtener las calorías del alimento
    $response = $this->buscarAlimentoPorPalabraClave($alimento);

    // Verificar si se encontró información para el alimento
    if (isset($response['parsed'][0]['food']['nutrients']['ENERC_KCAL'])) {
        $caloriasPor100Gramos = $response['parsed'][0]['food']['nutrients']['ENERC_KCAL'];

        // Calcular las calorías totales
        $caloriasTotales = ($cantidadGramos / 100) * $caloriasPor100Gramos;

        return round($caloriasTotales); // Redondear el resultado y devolverlo como entero
        } else {
            // Si no se encontraron calorías para el alimento, devolver un valor predeterminado
            throw new \Exception('El alimento no se encontró en la base de datos.');
        }
    }
}