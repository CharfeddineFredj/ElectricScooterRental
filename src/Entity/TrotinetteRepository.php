<?php

namespace App\Entity;

use App\Entity\Trotinette;
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
