<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findAllOrdered()
    {
        // $entityManager = $this->getEntityManager();
        // $query = $entityManager->createQuery(
        //     '
        //     SELECT m
        //     FROM App\Entity\Movie m
        //     ORDER BY m.title ASC
        //     '
        // );
        // return $query->getResult();

        // On récupère un objet query Builder
        $qb = $this->createQueryBuilder('m');
        // On préceise les spécificités de la requête
        $qb->orderBy('m.title', 'ASC');
        // On récupère un objet query
        $query = $qb->getQuery();
        // on envoit les résultats de la requête
        return $query->getResult();

        // Ici on a toute les étapes ci-dessus en une seul ligne
        /*
           return $this->createQueryBuilder('m')
            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        */
    }

    public function findOneByGenre($id): ?Movie
    {
        // Version avec DQL
        $em = $this->getEntityManager();
        $query = $em->createQuery(  // Le LEFT JOIN permet de conserver tout les film le INNER JOIN que ceux qui ont un genre
            "SELECT m, g, c, p
            FROM App\Entity\Movie m
            LEFT JOIN m.genres g
            LEFT JOIN m.castings c
            LEFT JOIN c.person p
            WHERE m.id = :id
            ORDER BY g.name DESC
            "
        );
        $query->setParameter(':id', $id);
        // Cette méthode permet de renvoyer un objet ou null si rien n'a été trouvé
        return $query->getOneOrNullResult();

        // ou
        // version avec queryBuilder
        // $qb = $this->createQueryBuilder('m');

        // $qb->addSelect('g');
        // $qb->join('m.genres', 'g');

        // $qb->addSelect('c');
        // $qb->join('m.castings', 'c');

        // $qb->addSelect('p');
        // $qb->join('c.person', 'p');

        //  $qb->andWhere('m.id = :id');
         
        //  $qb->setParameter(':id', $id);

        //  $query = $qb->getQuery();

        //  return $query->getOneOrNullResult();
    }

    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
