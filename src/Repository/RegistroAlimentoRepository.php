<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\RegistroAlimento;

class RegistroAlimentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistroAlimento::class);
    }

    public function findTotalCaloriasByUsuarioAndFecha($usuario, $fecha): int
    {
        // Definir la fecha de inicio (medianoche) y la fecha de fin (23:59:59) del día
        $fechaInicio = $fecha->format('Y-m-d') . ' 00:00:00';
        $fechaFin = $fecha->format('Y-m-d') . ' 23:59:59';
    
        $totalCalorias = $this->createQueryBuilder('r')
            ->select('SUM(r.calorias)')
            ->andWhere('r.usuario = :usuario')
            ->andWhere('r.fecha BETWEEN :fechaInicio AND :fechaFin')
            ->setParameter('usuario', $usuario)
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFin', $fechaFin)
            ->getQuery()
            ->getSingleScalarResult();
    
        // Verificar si $totalCalorias es null y devolver 0 en ese caso
        return $totalCalorias !== null ? (int) $totalCalorias : 0;
    }
    
    
}