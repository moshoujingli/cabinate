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
        $parameters = array();
        if ($query->get('id')!==null) {
            if (is_numeric($query->get('id'))) {
                $parameters['id']=$query->get('id');
            }else{
                throw new BadOperationException('id must be a number');
            }
        }
        if ($query->get('name')!==null) {
            if (strlen($query->get('name'))!==0) {
                $parameters['name']=$query->get('name');
            }else{
                throw new BadOperationException('name is not in a right format');
            }
        }
        if ($query->get('status')!==null) {
            if (is_numeric($query->get('status'))) {
                $parameters['status']=$query->get('status');
            }else{
                throw new BadOperationException('status must be a number');
            }
        }
        if ($query->get('type')!==null) {
            if (is_numeric($query->get('type'))) {
                $parameters['type']=$query->get('type');
            }else{
                throw new BadOperationException('type must be a number');
            }
        }
        if ($query->get('floor')!==null) {
            if (is_numeric($query->get('floor'))) {
                $parameters['floor']=$query->get('floor');
            }else{
                throw new BadOperationException('floor must be a number');
            }
        }
        if ($query->get('ceiling')!==null) {
            if (is_numeric($query->get('ceiling'))) {
                $parameters['ceiling']=$query->get('ceiling');
            }else{
                throw new BadOperationException('ceiling must be a number');
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
        $products = $this->repository->search($parameters);
        if (count($products)) {
            return $products;
        }else{
            throw new ResourceNotFoundException("No product with parameters like ".json_encode($parameters));
        }
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
