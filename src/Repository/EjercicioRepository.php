<?php

namespace App\Repository;

use App\Entity\Ejercicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ejercicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ejercicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ejercicio[]    findAll()
 * @method Ejercicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EjercicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ejercicio::class);
    }

    // Métodos personalizados si son necesarios
}
