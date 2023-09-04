<?php

namespace App\Repository;

use App\Entity\Typeannonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typeannonce>
 *
 * @method Typeannonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeannonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeannonce[]    findAll()
 * @method Typeannonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeAnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeannonce::class);
    }

//    /**
//     * @return Typeannonce[] Returns an array of Typeannonce objects
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

//    public function findOneBySomeField($value): ?Typeannonce
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
