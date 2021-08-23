<?php

namespace App\Repository;

use App\Entity\Gallery;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }

    public function findLocationGalleries(Location $location)
    {
        return $this->createQueryBuilder('gallery')
            ->orderBy('gallery.created', 'desc')
            ->where('gallery.location = :location')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }

    public function findLocationGalleryById(int $locationId, int $gallery)
    {
        return $this->createQueryBuilder('gallery')
            ->join('gallery.location', 'location')
            ->where('location.id = :locationId')
            ->andWhere('gallery.id = :galleryId')
            ->setParameter('galleryId', $gallery)
            ->setParameter('locationId', $locationId)
            ->getQuery()
            ->getSingleResult();
    }
}