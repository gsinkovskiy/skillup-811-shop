<?php

namespace App\Repository;

use App\Entity\AttributeValue;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByName($query)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByAttributes(Category $category, array $filter)
    {
        $attributeQueryBuilder = $this->_em->createQueryBuilder()
            ->select('IDENTITY(v.product) as id, COUNT(v.attribute) AS matched')
            ->from(AttributeValue::class, 'v')
            ->groupBy('v.product');

        $usedAttributesCount = 0;

        foreach ($category->getAttributes() as $attribute) {
            $minValue = $filter['attr_min_' . $attribute->getId()];
            $maxValue = $filter['attr_max_' . $attribute->getId()];

            if (!$minValue && !$maxValue) {
                continue;
            }

            $usedAttributesCount++;
            $attrExpression = $attributeQueryBuilder->expr()->andX();
            $attrExpression->add('v.attribute = ' . $attribute->getId());

            if ($minValue) {
                $attrExpression->add('v.value >= ' . floatval($minValue));
            }

            if ($maxValue) {
                $attrExpression->add('v.value <= ' . floatval($maxValue));
            }

            $attributeQueryBuilder->orWhere($attrExpression);
        }

        $attributeQueryBuilder->having('matched >= ' . $usedAttributesCount);
        $attributes = $attributeQueryBuilder->getQuery()->getResult();
        $productIds = array_column($attributes, 'id');

        if (!$productIds) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('p');

        return $queryBuilder
            ->where($queryBuilder->expr()->in('p.id', $productIds))
            ->andWhere('p.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
