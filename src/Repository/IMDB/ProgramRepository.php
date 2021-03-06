<?php

namespace App\Repository\IMDB;

use App\Entity\IMDB\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    public function findByKeyword($keyword): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title like :keyword')
            ->setParameters([
                'keyword' =>  '%'. $keyword . '%',
            ])
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllApiKeys()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT p.API_id FROM App\Entity\IMDB\Program p');

        $assoc = $query->execute();
        $keys = [];
        foreach ($assoc as $result) {
            $keys[] = $result['API_id'];
        }
        return $keys;
    }


    // /**
    //  * @return Program[] Returns an array of Program objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Program
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
