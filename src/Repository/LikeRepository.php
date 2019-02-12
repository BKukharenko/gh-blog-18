<?php

namespace App\Repository;

use App\Entity\Like;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Like|null find($id, $lockMode = null, $lockVersion = null)
 * @method Like|null findOneBy(array $criteria, array $orderBy = null)
 * @method Like[]    findAll()
 * @method Like[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Like::class);
    }

    /**
     * @param $postId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return mixed
     */
    public function getLikeCountForPost($postId)
    {
        return $this->createQueryBuilder('l')
          ->andWhere('l.post IN (:post_id)')
          ->setParameter(':post_id', $postId)
          ->select('COUNT(l.id) as likeCount')
          ->getQuery()
          ->getOneOrNullResult();
    }
}
