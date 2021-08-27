<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Location;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findLocationReviews(Location $location)
    {
        return $this->createQueryBuilder('review')
            ->orderBy('review.created', 'desc')
            ->where('review.location = :location')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }
}