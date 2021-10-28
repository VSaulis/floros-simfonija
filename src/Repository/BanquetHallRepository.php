<?php

namespace App\Repository;

use App\Entity\BanquetHall;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BanquetHallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BanquetHall::class);
    }

    public function findLocationBanquetHalls(Location $location)
    {
        return $this->createQueryBuilder('banquet_hall')
            ->where('banquet_hall.location = :location')
            ->orderBy('banquet_hall.position', 'asc')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }

    public function findLocationBanquetHallById(int $locationId, int $banquetHallId)
    {
        return $this->createQueryBuilder('banquet_hall')
            ->join('banquet_hall.location', 'location')
            ->where('location.id = :locationId')
            ->andWhere('banquet_hall.id = :banquetHallId')
            ->setParameter('banquetHallId', $banquetHallId)
            ->setParameter('locationId', $locationId)
            ->getQuery()
            ->getSingleResult();
    }
}