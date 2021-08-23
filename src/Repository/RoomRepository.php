<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Room;
use App\Entity\RoomCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function findRoomsByLocationAndCategory(Location $location, RoomCategory $category)
    {
        return $this->createQueryBuilder('room')
            ->orderBy('room.created', 'desc')
            ->where('room.location = :location')
            ->andWhere('room.category = :category')
            ->setParameter('location', $location)
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    public function findLocationRoomById(int $locationId, int $roomId)
    {
        return $this->createQueryBuilder('room')
            ->join('room.location', 'location')
            ->where('location.id = :locationId')
            ->andWhere('room.id = :roomId')
            ->setParameter('roomId', $roomId)
            ->setParameter('locationId', $locationId)
            ->getQuery()
            ->getSingleResult();
    }

    public function findSimilarRooms(Location $location, RoomCategory $category)
    {
        return $this->createQueryBuilder('room')
            ->where('room.location = :location')
            ->andWhere('room.category = :category')
            ->setParameter('category', $category)
            ->setParameter('location', $location)
            ->orderBy('room.created', 'desc')
            ->setFirstResult(0)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}