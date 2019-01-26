<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */

    public function findPublishedQuery(): Query
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->getQuery()
        ;
    }

    public function findByCategoryQuery($slug): Query
    {
        return $this->createQueryBuilder('p')
        ->add('select', 'p')
        ->leftJoin('p.category', 'c')
        ->where('c.name LIKE :category')
        ->setParameter('category', $slug)
        ->getQuery()
        ;
    }

    public function findByTagQuery($slug): Query
    {
        return $this->createQueryBuilder('p')
      ->add('select', 'p')
      ->leftJoin('p.tags', 't')
      ->where('t.name LIKE :tag')
      ->setParameter('tag', $slug)
      ->getQuery()
      ;
    }

  public function findBySearchQuery(string $rawQuery) {
      $query = $this->sanitizeSearchQuery($rawQuery);
      $searchTerms = $this->extractSearchTerms($query);

      if (0 === \count($searchTerms)) {
        return [];
      }

      $queryBuilder = $this->createQueryBuilder('p');
      foreach ($searchTerms as $key=>$term) {
        $queryBuilder
          ->orWhere('p.title LIKE :t_'.$key)
          ->setParameter('t_'.$key, '%'.$term.'%')
        ;
      }

      return $queryBuilder
        ->orderBy('p.createdAt', 'DESC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
    }


  private function sanitizeSearchQuery(string $query): string
  {
    return trim(preg_replace('/[[:space:]]+/', ' ', $query));
  }

  private function extractSearchTerms(string $searchQuery): array
  {
    $terms = array_unique(explode(' ', $searchQuery));
    return array_filter($terms, function ($term) {
      return 2 <= mb_strlen($term);
    });
  }
}
