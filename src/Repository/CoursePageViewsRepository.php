<?php

namespace App\Repository;

use App\Entity\CoursePageViews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CoursePageViews|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursePageViews|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursePageViews[]    findAll()
 * @method CoursePageViews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursePageViewsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CoursePageViews::class);
    }

    public function getCourseViewsById($userId, $courseId, $limit)
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql  = "
            SELECT * FROM course_page_views pv
            WHERE pv.user_id = :userId
            AND pv.course_id = :courseId
            ORDER BY pv.timestamp DESC
            LIMIT {$limit}
        ";
        $stmt = $connection->prepare($sql);
        $stmt->execute([
            'userId'  => $userId,
            'courseId' => $courseId,
        ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

//    /**
//     * @return CoursePageViews[] Returns an array of CoursePageViews objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CoursePageViews
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
