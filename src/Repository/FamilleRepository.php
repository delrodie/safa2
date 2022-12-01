<?php

namespace App\Repository;

use App\Entity\Famille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Famille>
 *
 * @method Famille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Famille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Famille[]    findAll()
 * @method Famille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Famille::class);
    }

    public function save(Famille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Famille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Liste des familles concercnées par le concours encours
     * @return float|int|mixed|string
     */
    public function findByConcoursActif(): mixed
    {
        return $this->createQueryBuilder('f')
            ->addSelect('cu')
            ->addSelect('co')
            ->leftJoin('f.commune', 'co')
            ->leftJoin('f.concours', 'cu')
            ->where('cu.fin >= :date')
            ->orderBy('co.nom', "ASC")
            ->setParameter('date', date('Y-m-d'))
            ->getQuery()->getResult()
            ;
    }

    /**
     * Liste des familles concercnées par le concours encours
     * @return float|int|mixed|string
     */
    public function findByDernierConcours(): mixed
    {
        return $this->createQueryBuilder('f')
            ->addSelect('cu')
            ->addSelect('co')
            ->leftJoin('f.commune', 'co')
            ->leftJoin('f.concours', 'cu')
            ->orderBy('cu.fin', "DESC")
            ->setMaxResults(1)
            ->getQuery()->getResult()
            ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOne($id)
    {
        return $this->createQueryBuilder('f')
            ->addSelect('cu')
            ->addSelect('co')
            ->leftJoin('f.commune', 'co')
            ->leftJoin('f.concours', 'cu')
            ->where('f.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult()
            ;
    }

//    /**
//     * @return Famille[] Returns an array of Famille objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Famille
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
