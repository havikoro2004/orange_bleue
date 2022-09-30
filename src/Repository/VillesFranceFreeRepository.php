<?php

namespace App\Repository;

use App\Entity\VillesFranceFree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VillesFranceFree>
 *
 * @method VillesFranceFree|null find($id, $lockMode = null, $lockVersion = null)
 * @method VillesFranceFree|null findOneBy(array $criteria, array $orderBy = null)
 * @method VillesFranceFree[]    findAll()
 * @method VillesFranceFree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VillesFranceFreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VillesFranceFree::class);
    }

    public function add(VillesFranceFree $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VillesFranceFree $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function finVilleByCode($code)
    {
        return $this->createQueryBuilder('v')
            ->where('v.ville_code_postal = :code')
            ->setParameter('code', $code)
            ->orderBy('v.ville_nom_reel', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?VillesFranceFree
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
