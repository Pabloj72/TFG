<?php

namespace App\Controller\Admin;

use App\Entity\Usuarios;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;


class UsuariosCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Usuarios::class;
    }

public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['nombre', 'email'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('Usuario'))
        ;
    }
}


