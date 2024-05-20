<?php

namespace App\Repository;

use App\Entity\Departement;
use App\Entity\Robe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Departement>
 *
 * @method Departement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departement[]    findAll()
 * @method Departement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departement::class);
    }
    public function findByDepEquide(Departement $DepartementEquide): ?Departement
    {
        $qb = $this->createQueryBuilder('d');
        //SELECT * from race innerjoin equide on equide.race = race.id where race = r.id;
        $qb->select('d')
            //   ->innerJoin('r.id', 'e', 'WITH', 'e.race')
            ->andWhere('d.iddepartement = :iddep')
            ->setParameter('iddep', $DepartementEquide)
            ->setMaxResults(1);
        // return equide
        return $query = $qb->getQuery()->getOneOrNullResult();

    }

//    /**
//     * @return Departement[] Returns an array of Departement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Departement
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
