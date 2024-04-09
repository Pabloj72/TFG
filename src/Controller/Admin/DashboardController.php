<?php

namespace App\Controller\Admin;

use App\Entity\Usuarios;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UsuariosCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Panel Administrador');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Panel prinicpal', 'fa fa-tachometer-alt');
        yield MenuItem::linktoRoute('Volver a la Página', 'fas fa-home', 'get_posts');
        yield MenuItem::linkToCrud('Usuarios', 'fas fa-user', Usuarios::class);
    }
}