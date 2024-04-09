<?php

namespace App\Controller;

use App\Entity\RegistroAlimento;
use App\Form\RegistroAlimentoFormType;
use App\Service\EdamamFoodDatabaseApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistroAlimentoController extends AbstractController
{
    private $entityManager;
    private $edamamApi;

    public function __construct(EntityManagerInterface $entityManager, EdamamFoodDatabaseApiService $edamamApi)
    {
        $this->entityManager = $entityManager;
        $this->edamamApi = $edamamApi;
    }

    #[Route('/registro-alimento', name: 'registro_alimento')]
    public function index(Request $request): Response
    {
        $registroAlimento = new RegistroAlimento();
        $form = $this->createForm(RegistroAlimentoFormType::class, $registroAlimento);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $alimento = $data->getNombreAlimento();
            $cantidad = $data->getCantidad();

            try {
                // Utilizamos el servicio EdamamFoodDatabaseApiService para consultar las calorías
                $calorias = $this->edamamApi->consultarCalorias($alimento, $cantidad);

                $registroAlimento->setCalorias($calorias);

                // Asignar el usuario autenticado al registro de alimento
                $registroAlimento->setUsuario($this->getUser());

                $this->entityManager->persist($registroAlimento);
                $this->entityManager->flush();

                $this->addFlash('success', 'Alimento registrado exitosamente.');

                return $this->redirectToRoute('app_home');
            } catch (\Exception $e) {
                // Capturar la excepción y mostrar un mensaje de error
                $error = $e->getMessage();
                $this->addFlash('error', $error);
            }
        }

        return $this->render('registro_alimento/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
