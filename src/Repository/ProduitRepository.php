<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Filter\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }


    public function findBySearchFilter(Search $search, int $offset, int $limit = 6): array
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.categorie', 'c')
            ->orderBy('p.nom', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if (!empty($search->SearchCategory)) {
            $query = $query->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->SearchCategory);
        }

        if (!empty($search->SearchName)) {
            $query = $query->andWhere('p.nom LIKE :SearchName')
                ->setParameter('SearchName', "%{$search->SearchName}%");
        }

        return $query->getQuery()->getResult();
    }


    public function findAllPaginated(int $offset, int $limit = 6): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countBySearchFilter(Search $search): int
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->leftJoin('p.categorie', 'c');

        if (!empty($search->SearchCategory)) {
            $query = $query->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->SearchCategory);
        }

        if (!empty($search->SearchName)) {
            $query = $query->andWhere('p.nom LIKE :SearchName')
                ->setParameter('SearchName', "%{$search->SearchName}%");
        }

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    public function findOneById(int $id): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }



}
