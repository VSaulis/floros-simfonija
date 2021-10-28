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
            ->where('gallery.location = :location')
            ->orderBy('gallery.position', 'asc')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }

    public function findLocationGalleryById(int $locationId, int $galleryId)
    {
        return $this->createQueryBuilder('gallery')
            ->join('gallery.location', 'location')
            ->where('location.id = :locationId')
            ->andWhere('gallery.id = :galleryId')
            ->orderBy('gallery.position', 'asc')
            ->setParameter('galleryId', $galleryId)
            ->setParameter('locationId', $locationId)
            ->getQuery()
            ->getSingleResult();
    }
}