<?php

namespace Cabinate\DAOBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TableUnitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TableUnitRepository extends EntityRepository
{
    public function search($param)
    {
        if (count($param)===0) {
            return array();
        }
        $queryBuilder = $this->createQueryBuilder('table');
        $queryBuilder->innerJoin('table.restaurant','restaurant');
        if (!isset($param['status'])) {
            $queryBuilder->andWhere("table.status != :status")
                             ->setParameter("status", "2");
        }
        foreach ($param as $key => $value) {
            if ($key!='restaurant_id') {
                $queryBuilder->andWhere("table.$key = :$key")
                             ->setParameter("$key", "$value");
            }
        }
        if (isset($param['restaurant_id'])&&$param['restaurant_id']) {
            $queryBuilder->andWhere("restaurant.id = :a")
                         ->setParameter("a", $param['restaurant_id']);
        }
        $query = $queryBuilder->orderBy('table.id', 'DESC')
                              ->getQuery();

        return $query->getResult();
    }
    public function findOneById($id)
    {
        return parent::findOneById($id);
    }

}
