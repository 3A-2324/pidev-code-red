<?php

namespace App\Repository;

use App\Entity\SuiviObjectif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SuiviObjectif>
 *
 * @method SuiviObjectif|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuiviObjectif|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuiviObjectif[]    findAll()
 * @method SuiviObjectif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuiviObjectifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviObjectif::class);
    }

//    /**
//     * @return SuiviObjectif[] Returns an array of SuiviObjectif objects
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

//    public function findOneBySomeField($value): ?SuiviObjectif
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
