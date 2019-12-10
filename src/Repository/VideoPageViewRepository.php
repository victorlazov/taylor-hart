<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\VideoPageView;
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
}
