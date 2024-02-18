<?php

namespace App\Repository;

use App\Entity\Journal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Journal>
 *
 * @method Journal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Journal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Journal[]    findAll()
 * @method Journal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Journal::class);
    }

//    /**
//     * @return Journal[] Returns an array of Journal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Journal
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
// public function sumCaloriesByDate(\DateTimeInterface $date): int
// {
//     return (int) $this->createQueryBuilder('j')
//         ->select('SUM(r.calorie_recette) as totalCalories')
//         ->leftJoin('j.RecetteRef', 'r')
//         ->where('j.Date = :date')
//         ->setParameter('date', $date)
//         ->getQuery()
//         ->getSingleScalarResult();
// }
public function sumCaloriesByDate(\DateTimeInterface $date): array
{
    return $this->createQueryBuilder('j')
        ->select('j.id', 'SUM(r.calorie_recette) as totalCalories')
        ->leftJoin('j.RecetteRef', 'r')
        ->andWhere('j.Date = :date')
        ->setParameter('date', $date)
        ->groupBy('j.id')
        ->getQuery()
        ->getResult();
}

}
