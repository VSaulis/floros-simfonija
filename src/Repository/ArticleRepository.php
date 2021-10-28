<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findLocationArticles(Location $location)
    {
        return $this->createQueryBuilder('article')
            ->orderBy('article.dateFrom', 'desc')
            ->where('article.location = :location')
            ->andWhere('article.visible = true')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }

    public function findLocationArticleById(int $locationId, int $articleId)
    {
        return $this->createQueryBuilder('article')
            ->join('article.location', 'location')
            ->where('location.id = :locationId')
            ->andWhere('article.visible = true')
            ->andWhere('article.id = :articleId')
            ->setParameter('articleId', $articleId)
            ->setParameter('locationId', $locationId)
            ->getQuery()
            ->getSingleResult();
    }
}