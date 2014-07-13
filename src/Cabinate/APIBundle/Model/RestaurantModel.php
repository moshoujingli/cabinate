<?php

namespace Cabinate\APIBundle\Model;

use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;

class RestaurantModel
{
    private $em;
    private $repository;
    public function __construct($em,$logger)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->repository = $em->getRepository(Restaurant::getEntityName());
    }
    public function saveRestaurant($parameters)
    {
        if (isset($parameters['name'])&&($parameters['name'])) {
            $name = $parameters['name'];
            $restaurant = new Restaurant();
            $restaurant->setStatus(isset($parameters['status'])?$parameters['status']:0);
            $restaurant->setName($name);
            $this->em->persist($restaurant);
            $this->em->flush();
            return $restaurant;
        }else{
            return false;
        }
    }
    public function changeRestaurant($id,$parameters)
    {
        if ($parameters['op']!='change'
            ||
            $parameters['path']!='status'
            ||
            !in_array($parameters['new'], array(0,1))
            ) {
            throw new BadOperationException();
        }else{
            $restaurant  = $this->repository->findOneById($id);
            if ($restaurant) {
                $restaurant->setStatus($parameters['new']);
                $this->em->persist($restaurant);
                $this->em->flush();
            }else{
                throw new ResourceNotFoundException("No Restaurant with id $id ");
            }

        }
    }
    public function getRestaurant($query)
    {
        $parameters = array();
        if ($query->get('id')!==null) {
            if (is_numeric($query->get('id'))) {
                $parameters['id']=$query->get('id');
            }else{
                throw new BadOperationException('id must be a number');
            }
        }
        if ($query->get('status')!==null) {
            if (is_numeric($query->get('status'))) {
                $parameters['status']=$query->get('status');
            }else{
                throw new BadOperationException('status must be a number');
            }
        }
        if ($query->get('name')!==null) {
            if (strlen($query->get('name'))!==0) {
                $parameters['name']=$query->get('name');
            }else{
                throw new BadOperationException('name cant be null');
            }
        }
        $this->logger->info(print_r($query,true));
        $this->logger->info(print_r($parameters,true));
        return $this->repository->findBy($parameters);
    }
}
