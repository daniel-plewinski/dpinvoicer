<?php

namespace AppBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllProducts()
    {
        return $this->createQueryBuilder('pr')
            ->where("pr.status <> :status OR pr.status IS NULL")
            ->setParameter('status', 'D')
            ->getQuery()
            ->getResult();

    }

    public function findOneProduct($id)
    {
        return $this->createQueryBuilder('pr')
            ->where('pr.id = :productId')
            ->andWhere("pr.status <> :status OR pr.status IS NULL")
            ->setParameters(array('productId' => $id, 'status' => 'D'))
            ->getQuery()
            ->getResult();
    }
}
