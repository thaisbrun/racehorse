<?php

namespace App\Repository;

use App\Entity\Region;
use App\Entity\Robe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Region>
 *
 * @method Region|null find($id, $lockMode = null, $lockVersion = null)
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    public function findByDepartement(Region $Departement): ?Region
    {
        $qb = $this->createQueryBuilder('reg');
        //SELECT * from race innerjoin equide on equide.race = race.id where race = r.id;
        $qb->select('reg')
            //   ->innerJoin('r.id', 'e', 'WITH', 'e.race')
            ->andWhere('reg.idregion = :idregiondep')
            ->setParameter('idregiondep', $Departement)
            ->setMaxResults(1);
        // return equide
        return $query = $qb->getQuery()->getOneOrNullResult();

    }

//    /**
//     * @return Region[] Returns an array of Region objects
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

//    public function findOneBySomeField($value): ?Region
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
