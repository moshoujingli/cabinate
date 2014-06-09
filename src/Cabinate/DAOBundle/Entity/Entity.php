<?php
namespace Cabinate\DAOBundle\Entity;

class Entity
{
	public static function getEntityName()
	{
	  return get_called_class();
	}
}