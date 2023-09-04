<?php

namespace App\Repository;

use App\Entity\Robe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Robe>
 *
 * @method Robe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Robe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Robe[]    findAll()
 * @method Robe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RobeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Robe::class);
    }

//    /**
//     * @return Robe[] Returns an array of Robe objects
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

//    public function findOneBySomeField($value): ?Robe
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
