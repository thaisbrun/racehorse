<?php

namespace App\Repository;

use App\Entity\Typeequide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typeequide>
 *
 * @method Typeequide|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeequide|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeequide[]    findAll()
 * @method Typeequide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEquideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeequide::class);
    }

//    /**
//     * @return Typeequide[] Returns an array of Typeequide objects
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

//    public function findOneBySomeField($value): ?Typeequide
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
