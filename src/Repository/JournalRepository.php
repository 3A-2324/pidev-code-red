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



    
    /**
     * Find journals by week.
     *
     * @param \DateTime $startOfWeek
     * @param \DateTime $endOfWeek
     * @return array
     */
    public function findJournalsByWeek(\DateTime $start): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $startDate = new \DateTime($start->format('Y-m-d'));
        $endDate = (clone $startDate)->modify('+30 days');

        $sql = '
            SELECT DISTINCT ingredient.nom
            FROM journal j
            JOIN journal_recette ON j.id = journal_recette.journal_id
            JOIN recette ON journal_recette.recette_id = recette.id
            JOIN recette_ingredient ON recette.id = recette_ingredient.recette_id
            JOIN ingredient ON recette_ingredient.ingredient_id = ingredient.id
            WHERE j.date BETWEEN :start AND :end
        ';

        // Convert DateTime objects to strings
        $params = [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d')
        ];

        $resultSet = $conn->executeQuery($sql, $params);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
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
