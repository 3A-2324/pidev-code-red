<?php

namespace App\Repository;

use App\Entity\SuiviActivite;
use App\Entity\SuiviActivité;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SuiviActivité>
 *
 * @method SuiviActivité|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuiviActivité|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuiviActivité[]    findAll()
 * @method SuiviActivité[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuiviActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviActivite::class);
    }

//    /**
//     * @return SuiviActivité[] Returns an array of SuiviActivité objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SuiviActivité
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}