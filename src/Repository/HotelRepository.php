<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    public function findLocationHotels(Location $location)
    {
        return $this->createQueryBuilder('hotel')
            ->orderBy('hotel.created', 'desc')
            ->where('hotel.location = :location')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }
}