<?php

namespace App\Repository;

use App\Entity\Room;
use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function findHotelRooms(Hotel $hotel)
    {
        return $this->createQueryBuilder('room')
            ->orderBy('room.created', 'desc')
            ->where('room.hotel = :hotel')
            ->setParameter('hotel', $hotel)
            ->getQuery()
            ->getResult();
    }

    public function findSimilarRooms(Hotel $hotel)
    {
        return $this->createQueryBuilder('room')
            ->where('room.hotel = :hotel')
            ->setParameter('hotel', $hotel)
            ->orderBy('room.created', 'desc')
            ->setFirstResult(0)
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }
}