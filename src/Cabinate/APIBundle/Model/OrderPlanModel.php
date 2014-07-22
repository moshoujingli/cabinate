<?php

namespace Cabinate\APIBundle\Model;
use Cabinate\APIBundle\Exceptions\BadOperationException;

use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\OrderPlan;
use Cabinate\DAOBundle\Entity\Product;

class OrderPlanModel
{
    private $em;
    private $logger;
    private $tableRepository;
    private $repository;
    private $productRepository;

    public function __construct($em,$logger)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->repository = $this->em->getRepository(OrderPlan::getEntityName());
        $this->tableRepository = $this->em->getRepository(TableUnit::getEntityName());
        $this->productRepository = $this->em->getRepository(Product::getEntityName());
    }
    public function getOrderPlan($query)
    {
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
        if ($query->get('TradeSessionId')!==null) {
            if (is_numeric($query->get('TradeSessionId'))) {
                $parameters['TradeSessionId']=$query->get('TradeSessionId');
            }else{
                throw new BadOperationException('TradeSessionId must be a number');
            }
        }

        if ($query->get('product_id')!==null) {
            if (is_numeric($query->get('product_id'))) {
                $parameters['product_id']=$query->get('product_id');
            }else{
                throw new BadOperationException('product_id must be a number');
            }
        }
        if ($query->get('table_id')!==null) {
            if (is_numeric($query->get('table_id'))) {
                $parameters['table_id']=$query->get('table_id');
            }else{
                throw new BadOperationException('table_id must be a number');
            }
        }

        if ($query->get('createdTime')!==null) {
            $timeSpec = explode('-', $query->get('createdTime'));
            $valid = count($timeSpec)==2&&is_numeric($timeSpec[0])&&is_numeric($timeSpec[1]);
            if ($valid) {
                $parameters['createdTime']=$timeSpec;
            }else{
                throw new BadOperationException('createdTime bad format');
            }
        }
        if ($query->get('updatedTime')!==null) {
            $timeSpec = explode('-', $query->get('updatedTime'));
            $valid = count($timeSpec)==2&&is_numeric($timeSpec[0])&&is_numeric($timeSpec[1]);
            if ($valid) {
                $parameters['updatedTime']=$timeSpec;
            }else{
                throw new BadOperationException('updatedTime bad format');
            }
        }
        if ($query->get('servedTime')!==null) {
            $timeSpec = explode('-', $query->get('servedTime'));
            $valid = count($timeSpec)==2&&is_numeric($timeSpec[0])&&is_numeric($timeSpec[1]);
            if ($valid) {
                $parameters['servedTime']=$timeSpec;
            }else{
                throw new BadOperationException('servedTime bad format');
            }
        }

        $this->logger->info(print_r($parameters,true));
        $this->logger->info(print_r($query,true));
        $orderPlan = $this->repository->search($parameters);
        if (count($orderPlan)) {
            return $orderPlan;
        }else{
            throw new ResourceNotFoundException("No orderPlan with parameters like ".json_encode($parameters));
        }
    }
    public function changeOrderPlanContent($id,$parameters)
    {
        $orderPlan  = $this->repository->findOneById($id);
        if (!$orderPlan instanceof OrderPlan) {
            throw new ResourceNotFoundException("No orderPlan with id $id");
        }
        $parameters['table']['status'] = 0;
        $table = $this->tableRepository->findOneBy($parameters['table']);
        if (!($table instanceof TableUnit)) {
            throw new ResourceNotFoundException("table entity not found :".json_encode($parameters['table']));
        }
        $parameters['product']['status'] = 0;
        $product = $this->productRepository->findOneBy($parameters['product']);
        if (!($product instanceof Product)) {
            throw new ResourceNotFoundException("product entity not found :".json_encode($parameters['product']));
        }
        if ($product->getRestaurant()->getId()!=$table->getRestaurant()->getId()) {
            throw new BadOperationException("product not served in the same restaurant of table.");
        }
        $orderPlan->setTableUnit($table);
        $orderPlan->setProduct($product);
        $orderPlan->setStatus($parameters['status']);
        $orderPlan->setTradeSessionId($parameters['TradeSessionId']);
        $this->em->persist($orderPlan);
        $this->em->flush();
    }
    public function changeOrderPlanStatus($id,$parameters)
    {
        $orderPlan  = $this->repository->findOneById($id);
        if (!$orderPlan instanceof OrderPlan) {
            throw new ResourceNotFoundException("No orderPlan with id $id");
        }
        if ($parameters['op']!='change'||$parameters['path']!='status'||!isset($parameters['new'])) {
            throw new BadOperationException("Bad Parament.");
        }
        $orderPlan->setStatus($parameters['new']);
        $this->em->persist($orderPlan);
        $this->em->flush();
    }
    public function deleteOrderPlan($id)
    {
        $orderPlan  = $this->repository->findOneById($id);
        if (!$orderPlan instanceof OrderPlan) {
            throw new ResourceNotFoundException("No orderPlan with id $id");
        }
        if ($orderPlan->getStatus()!=0) {
            throw new BadOperationException('Use Patch to delete a submmited order.');
        }
        $orderPlan->setStatus(5);
        $this->em->persist($orderPlan);
        $this->em->flush();
    }
    public function saveOrderPlan($parameters)
    {
        $parameters['table']['status'] = 0;
        $table = $this->tableRepository->findOneBy($parameters['table']);
        if (!($table instanceof TableUnit)) {
            throw new ResourceNotFoundException("table entity not found :".json_encode($parameters['table']));
        }
        $parameters['product']['status'] = 0;
        $product = $this->productRepository->findOneBy($parameters['product']);
        if (!($product instanceof Product)) {
            throw new ResourceNotFoundException("product entity not found :".json_encode($parameters['product']));
        }
        if ($product->getRestaurant()->getId()!=$table->getRestaurant()->getId()) {
            throw new BadOperationException("product not served in the same restaurant of table.");
        }
        $orderPlan = new OrderPlan();
        $orderPlan->setTableUnit($table);
        $orderPlan->setProduct($product);
        $orderPlan->setStatus(0);
        $orderPlan->setTradeSessionId($parameters['TradeSessionId']);
        $this->em->persist($orderPlan);
        $this->em->flush();
        return $orderPlan;
    }
}
