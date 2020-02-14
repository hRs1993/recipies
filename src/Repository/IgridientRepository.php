<?php

namespace App\Repository;

use App\Entity\Igridient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Igridient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Igridient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Igridient[]    findAll()
 * @method Igridient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IgridientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Igridient::class);
    }

    // /**
    //  * @return Igridient[] Returns an array of Igridient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Igridient
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
