<?php

namespace App\Repository;

use App\Entity\BalanceHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BalanceHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BalanceHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BalanceHistory[]    findAll()
 * @method BalanceHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BalanceHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BalanceHistory::class);
    }

    public function getUserLastOperation(int $userId)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUserLastOperations(int $userId, int $limit = 50)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('b.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
