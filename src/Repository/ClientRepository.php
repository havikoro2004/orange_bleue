<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function add(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
        public function findAllDesc()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createAt','DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function finActif()
    {
        return $this->createQueryBuilder('c')
            ->where('c.active = 1')
            ->orderBy('c.createAt','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function finInactif()
    {
        return $this->createQueryBuilder('c')
            ->where('c.active = 0')
            ->orderBy('c.createAt','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function finByLetter($word,$status)
    {
        $query =  $this->createQueryBuilder('c')
            ->where('c.name LIKE :word')
            ->setParameter(':word',$word.'%')
            ->orderBy('c.createAt','DESC');
            if ($status === 'actifs'){
                $query = $query->andWhere('c.active =1');
            }
            if ($status === 'inactifs'){
                $query = $query->andWhere('c.active =0');
            }
           return $query->getQuery()->getResult();
            ;
    }
//    /**
//     * @return Client[] Returns an array of Client objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


}
