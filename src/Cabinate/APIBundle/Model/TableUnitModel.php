<?php

namespace Cabinate\APIBundle\Model;

use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;


class TableUnitModel
{
    private $em;
    private $repository;
    public function __construct($em,$logger)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->repository = $em->getRepository(TableUnit::getEntityName());
    }
    public function getTable($query)
    {
        $parameters = array();
        if ($query->get('id')!==null) {
            if (is_numeric($query->get('id'))) {
                $parameters['id']=$query->get('id');
            }else{
                throw new BadOperationException('id must be a number');
            }
        }
        if ($query->get('key')!==null) {
            if (strlen($query->get('key'))==40) {
                $parameters['key']=$query->get('key');
            }else{
                throw new BadOperationException('key is not in a right format');
            }
        }
        if ($query->get('status')!==null) {
            if (is_numeric($query->get('status'))) {
                $parameters['status']=$query->get('status');
            }else{
                throw new BadOperationException('status must be a number');
            }
        }
        if ($query->get('restaurant_id')!==null) {
            if (is_numeric($query->get('restaurant_id'))) {
                $parameters['restaurant_id']=$query->get('restaurant_id');
            }else{
                throw new BadOperationException('restaurant_id must be a number');
            }
        }
        $this->logger->info(print_r($query,true));
        $this->logger->info(print_r($parameters,true));
        $tableunits = $this->repository->search($parameters);
        if (count($tableunits)) {
            return $tableunits;
        }else{
            throw new ResourceNotFoundException("No table with parameters like ".json_encode($parameters));
        }
    }
    public function changeTable($id,$parameters)
    {
        if ($parameters['op']!='change'
            ||
            $parameters['path']!='status'
            ||
            !in_array($parameters['new'], array(0,1,2,3,4,5))
            ) {
            throw new BadOperationException("Bad Operation Args");
        }else{
            $tableunit = $this->repository->findOneById($id);
            if (count($tableunit)) {
                $tableunit->setStatus($parameters['new']);
                $this->em->persist($tableunit);
                $this->em->flush();
            }else{
                throw new ResourceNotFoundException("No table with id $id");
            }
        }
        
    }
    public function saveTable($parameters)
    {
        if (isset($parameters['restaurant_id'])&&is_numeric($restaurant_id = $parameters['restaurant_id'])) {
            $restaurantRepository = $this->em->getRepository(Restaurant::getEntityName());
            $restaurant = $restaurantRepository->findOneById($restaurant_id);
            if (!($restaurant instanceof Restaurant)) {
                throw new ResourceNotFoundException("restaurant_id is not found");
            }else{
                $tableunit = new Tableunit();
                $tableunit->setStatus(isset($parameters['status'])?$parameters['status']:0);
                $tableunit->setRestaurant($restaurant);
                $tableunit->setTableKey(sha1(uniqid(mt_rand(),true)));
                $this->em->persist($tableunit);
                $this->em->flush();
                return $tableunit;
            }
        }else{
            throw new BadOperationException("restaurant_id is invalid");
        }
    }
}
