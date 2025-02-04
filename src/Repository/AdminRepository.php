<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Admin>
 */
class AdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    public function findAllAdmins(): array
{
    return $this->createQueryBuilder('a')
        ->orderBy('a.id', 'ASC')
        ->getQuery()
        ->getResult();
}

public function findOneByEmail(string $email): ?Admin
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.email = :email')
        ->setParameter('email', $email)
        ->getQuery()
        ->getOneOrNullResult();
}

public function deleteById(int $id): void
{
    $admin = $this->find($id);
    if ($admin) {
        $this->_em->remove($admin);
        $this->_em->flush();
    }
}


    //    /**
    //     * @return Admin[] Returns an array of Admin objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Admin
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
