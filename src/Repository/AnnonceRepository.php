<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\Equide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Expr;


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

    public function getFiltersAnnonces($filters = [])
    {
        $query = $this->createQueryBuilder('a')
            ->innerJoin(Equide::class, 'e', Join::WITH, 'e.id = a.equide')
            ->andWhere('a.activation = 1');

        // Type d'annonce
        if (!empty($filters['types'])) {
            $query->andWhere('a.typeA IN(:typea)')
                ->setParameter('typea', $filters['types']);
        }

        // Race
        if (!empty($filters['race'])) {
            $query->andWhere('e.race = :race')
                ->setParameter('race', $filters['race']);
        }

        // Robe
        if (!empty($filters['robe'])) {
            $query->andWhere('e.robe = :robe')
                ->setParameter('robe', $filters['robe']);
        }

        // Age

        if (!empty($filters['ageMin'])) {
            // Calcule la date maximale de naissance pour l’âge minimum
            $dateMaxNaiss = (new \DateTime())->modify('-' . (int)$filters['ageMin'] . ' years');
            $query->andWhere('e.datenaiss <= :dateMaxNaiss')
                ->setParameter('dateMaxNaiss', $dateMaxNaiss->format('Y-m-d'));
        }

        if (!empty($filters['ageMax'])) {
            // Calcule la date minimale de naissance pour l’âge maximum
            $dateMinNaiss = (new \DateTime())->modify('-' . (int)$filters['ageMax'] . ' years');
            $query->andWhere('e.datenaiss >= :dateMinNaiss')
                ->setParameter('dateMinNaiss', $dateMinNaiss->format('Y-m-d'));
        }

        // Département
        if (!empty($filters['departement'])) {
            $query->andWhere('e.dep = :departement')
                ->setParameter('departement', $filters['departement']);
        }

        // Type équidé
        if (!empty($filters['typeEquide'])) {
            $query->andWhere('e.typeEq = :typeEquide')
                ->setParameter('typeEquide', $filters['typeEquide']);
        }

        return $query->getQuery()->getResult();
    }


}
