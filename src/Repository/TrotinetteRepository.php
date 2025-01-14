<?php

namespace App\Repository;

use App\Entity\Trotinette;
use App\Entity\Station;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trotinette>
 *
 * @method Trotinette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trotinette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trotinette[]    findAll()
 * @method Trotinette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrotinetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trotinette::class);
    }
    public function findStationWithMaxTrotinettes()
{
    return $this->createQueryBuilder('t')
        ->join('t.idStation', 's')
        ->groupBy('s.idStation')
        ->orderBy('COUNT(t.idStation)', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}
public function findAllWithTrottinettesCount()
{
    return $this->createQueryBuilder('t')
        ->select('s.name AS station_name', 'COUNT(t.id) AS trotinettes_count')
        ->leftJoin('t.station', 's')
        ->groupBy('s.name')
        ->getQuery()
        ->getResult();
}
// TrotinetteRepository.php
public function findBySpeed($vitesse)
{
    return $this->createQueryBuilder('t')
        ->andWhere('t.vitesse = :vitesse')
        ->setParameter('vitesse', $vitesse)
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Trotinette[] Returns an array of Trotinette objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trotinette
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
