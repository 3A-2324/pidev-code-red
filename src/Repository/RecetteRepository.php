<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 *
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }
    public function save(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTopSuggestedRecipesForYear(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.id, r.Nom, r.image, r.description, COUNT(j.id) as recipeCount, r.calorie_recette as calorieRecette')
            ->join('r.journals', 'j')
            ->andWhere('j.Date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('r.id')
            ->orderBy('recipeCount', 'DESC')
            ->setMaxResults(5);
    
        return $qb->getQuery()->getResult();
    }
    

public function findRecipeIdsByIngredientId(int $ingredientId): array
{
    return $this->createQueryBuilder('ri')
        ->select('ri.recette_id')
        ->andWhere('ri.ingredient_id = :ingredientId')
        ->setParameter('ingredientId', $ingredientId)
        ->getQuery()
        ->getResult();
}

public function findBySearchTerm($searchTerm)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.Nom LIKE :searchTerm OR r.Categorie LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }   
    

//    /**
//     * @return Recette[] Returns an array of Recette objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recette
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
