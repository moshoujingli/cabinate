<?php

namespace Cabinate\DAOBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RestaurantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RestaurantRepository extends EntityRepository
{
	public function findOneById($id)
	{
		return parent::findOneById($id);
	}
}
