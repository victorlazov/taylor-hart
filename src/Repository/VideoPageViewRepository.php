<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\VideoPageView;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VideoPageView|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoPageView|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoPageView[]    findAll()
 * @method VideoPageView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoPageViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoPageView::class);
    }

    /**
     * @param User $user
     * @param string $courseId
     * @param int $limit
     * @return VideoPageView[]
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getCourseViewsByCourseId(User $user, string $courseId, int $limit): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $result = $this->createQueryBuilder('cpv')
            ->where('cpv.User =')
            ->getQuery()
            ->getResult()
        ;
        var_dump($result);
        die();

        $sql  = "
            SELECT * FROM course_page_view cpv
            WHERE cpv.user_id = :userId
            AND cpv.course_id = :courseId
            ORDER BY cpv.timestamp DESC
            LIMIT {$limit}
        ";
        $stmt = $connection->prepare($sql);
        $stmt->execute([
            'userId'  => $user,
            'courseId' => $courseId,
        ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
}
