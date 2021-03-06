<?php

namespace Cabinate\DAOBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
    public function search($param)
    {
        if (count($param)===0) {
            return array();
        }
        $queryBuilder = $this->createQueryBuilder('product');
        $queryBuilder->innerJoin('product.restaurant','restaurant');
        if (!isset($param['status'])) {
            $queryBuilder->andWhere("product.status = :status")
                             ->setParameter("status", "0");
        }
        foreach ($param as $key => $value) {
            if (!in_array($key, array('restaurant_id','floor','ceiling'))) {
                $queryBuilder->andWhere("product.$key = :$key")
                             ->setParameter("$key", "$value");
            }
        }
        if (isset($param['restaurant_id'])&&$param['restaurant_id']) {
            $queryBuilder->andWhere("restaurant.id = :a")
                         ->setParameter("a", $param['restaurant_id']);
        }
        if (isset($param['floor'])&&$param['floor']) {
            $queryBuilder->andWhere("product.price >= :floor")
                         ->setParameter("floor", $param['floor']);
        }
        if (isset($param['ceiling'])&&$param['ceiling']) {
            $queryBuilder->andWhere("product.price <= :ceiling")
                         ->setParameter("ceiling", $param['ceiling']);
        }
        $query = $queryBuilder->orderBy('product.id', 'DESC')
                              ->getQuery();

        return $query->getResult();
    }
    public function findOneById($id)
    {
        return parent::findOneById($id);
    }
}
