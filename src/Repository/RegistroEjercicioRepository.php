<?php

namespace App\Repository;

use App\Entity\RegistroEjercicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegistroEjercicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegistroEjercicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegistroEjercicio[]    findAll()
 * @method RegistroEjercicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistroEjercicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistroEjercicio::class);
    }

    // Métodos personalizados si son necesarios
}
