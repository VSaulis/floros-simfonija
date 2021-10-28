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
            ->where('hotel.location = :location')
            ->orderBy('hotel.position', 'asc')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }
}