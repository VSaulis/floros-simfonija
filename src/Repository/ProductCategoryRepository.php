<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategory::class);
    }

    public function findLocationCategories(Location $location)
    {
        return $this->createQueryBuilder('product_category')
            ->leftJoin('product_category.products', 'products')
            ->where('products.location = :location')
            ->setParameter('location', $location)
            ->getQuery()
            ->getResult();
    }
}