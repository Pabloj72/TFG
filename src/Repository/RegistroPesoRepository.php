<?php
// src/Repository/RegistroPesoRepository.php

namespace App\Repository;

use App\Entity\RegistroPeso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RegistroPesoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistroPeso::class);
    }

    // Aquí puedes implementar métodos personalizados para interactuar con el repositorio
}
