<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\Equide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function save(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFiltersAnnonces($filters = null)
   {
        $query = $this->createQueryBuilder('a')
            ->innerJoin(
                Equide::class,    // Entity
                'e',               // Alias
                Join::WITH,        // Join type
                'e.id = a.equide')
           ->Where('a.activation = 1');
            //On filtre les données
           if($filters != null){
                    $query->andWhere('a.typea IN(:$typeA)')
                    ->setParameter(':typea', array_values(array($filters)));
            }
                return $query->getQuery()->getResult();
   }

}
