<?php

namespace Cabinate\APIBundle\Model;
use Cabinate\APIBundle\Exceptions\BadOperationException;

use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\DAOBundle\Entity\Product;
use Cabinate\DAOBundle\Entity\Restaurant;

class ProductModel
{
    private $em;
    private $logger;
    private $repository;
    private $restaurantRepository;
    public function __construct($em,$logger)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->repository = $em->getRepository(Product::getEntityName());
        $this->restaurantRepository = $em->getRepository(Restaurant::getEntityName());
    }
    public function getProduct($query)
    {
        # code...
    }
    public function changeProduct($id,$parameters)
    {
        if (count($undefind = $this->hasKeys(array('name','price','type','restaurant'),$parameters))>0) {
            throw new BadOperationException("Undefind Args :".json_encode($undefind));
        }

        $product = $this->repository->findOneById($id);
        if ($product instanceof Product) {
            $restaurant = $this->restaurantRepository->findOneBy($parameters['restaurant']);
            if (!($restaurant instanceof Restaurant)) {
                throw new ResourceNotFoundException("Restaurant entity not found :".json_encode($parameters['restaurant']));
            }
            $product->setRestaurant($restaurant);
            $product->setName($parameters['name']);
            $product->setPrice($parameters['price']);
            $product->setStatus(isset($parameters['status'])?$parameters['status']:0);
            $product->setType($parameters['type']);
            $this->em->persist($product);
            $this->em->flush();

        }else{
            throw new ResourceNotFoundException("Product to update not exists with id $id");
        }
    }
    public function deleteProduct($id)
    {
        $product = $this->repository->findOneById($id);
        if ($product instanceof Product) {
            $product->setStatus(1);
            $this->em->persist($product);
            $this->em->flush();
        }else{
            throw new ResourceNotFoundException("Product to delete not exists with id $id");
        }
    }
    public function saveProduct($parameters)
    {
        if (count($undefind = $this->hasKeys(array('name','price','type','restaurant'),$parameters))>0) {
            throw new BadOperationException("Undefind Args :".json_encode($undefind));
        }
        $restaurant = $this->restaurantRepository->findOneBy($parameters['restaurant']);
        if (!($restaurant instanceof Restaurant)) {
            throw new ResourceNotFoundException("Restaurant entity not found :".json_encode($parameters['restaurant']));
        }

        $product = new Product();
        $product->setRestaurant($restaurant);
        $product->setName($parameters['name']);
        $product->setPrice($parameters['price']);
        $product->setStatus(0);
        $product->setType($parameters['type']);
        $this->em->persist($product);
        $this->em->flush();
        return $product;
    }
    private function hasKeys($keys,$param)
    {
        $rtn = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $param)) {
                $rtn[]=$key;
            }
        }
        return $rtn;
    }
}
