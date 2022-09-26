<?php

namespace App\Repository;

use App\Entity\Branch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Branch>
 *
 * @method Branch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Branch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Branch[]    findAll()
 * @method Branch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BranchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Branch::class);
    }

    public function add(Branch $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Branch $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBranchOfClient($value)
    {
        return $this->createQueryBuilder('b')
            ->select('b,p')
            ->join('b.permission','p')
            ->where('b.client = :id')
            ->andWhere('p.branch=:status')
            ->andWhere('b.token IS NULL')
            ->orderBy('b.createdAt','DESC')
            ->setParameter('status',true)
            ->setParameter('id', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findActif($client)
    {
        return $this->createQueryBuilder('b')
            ->where('b.active =1')
            ->andWhere('b.client = :client')
            ->setParameter('client',$client)
            ->orderBy('b.createdAt','DESC')
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return Branch[] Returns an array of Branch objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

}
