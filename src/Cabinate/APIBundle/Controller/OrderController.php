<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\OrderPlan;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Product;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\APIBundle\Exceptions\BadOperationException;


/**
 *
 * gppd
 *
 *
 */
class OrderController extends APIBaseController 
{
    private $tableRepository;
    private $productRepository;
    public function preExcute()
    {
        parent::preExcute();
        $this->repository = $this->getDoctrine()->getRepository(OrderPlan::getEntityName());
        $this->tableRepository = $this->getDoctrine()->getRepository(TableUnit::getEntityName());
        $this->productRepository = $this->getDoctrine()->getRepository(Product::getEntityName());
    }
    /**
    * @Rest\View()
    * @Rest\Get("/order")
    * @ApiDoc(
    * description="get the order by param",
    * resource=true,
    * output="Cabinate\DAOBundle\Entity\OrderPlan",
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="OrderPlan Id"},
    *     {"name"="TradeSessionId", "dataType"="integer", "required"=false, "description"="OrderPlan TradeSessionId"},
    *     {"name"="table_id", "dataType"="integer", "required"=false, "description"="OrderPlan table_id"},
    *     {"name"="product_id", "dataType"="integer", "required"=false, "description"="OrderPlan product_id"},
    *     {"name"="status", "dataType"="smallint", "required"=false, "description"="OrderPlan status"},
    *     {"name"="createdTime", "dataType"="time range in sec", "required"=false, "description"="OrderPlan created time"},
    *     {"name"="updatedTime", "dataType"="time range in sec", "required"=false, "description"="OrderPlan updated time"},
    *     {"name"="servedTime", "dataType"="time range in sec", "required"=false, "description"="OrderPlan serverd time"}
    *
    * })
    */
    public function getAction()
    {
        $this->preExcute();
        $parameters = array();
        $query = $this->getRequest()->query;
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
    /**
    * @Rest\View(statusCode=204)
    * @Rest\put("/order")
    * @ApiDoc(
    * description="update the order by param",
    * resource=true,
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="OrderPlan Id"},
    *     {"name"="content", "dataType"="json", "required"=false, "description"="Cabinate\DAOBundle\Entity\Orderplan jsonencode(updated_time and created_time will be ignored)"}
    *
    * })
    */

    public function putAction($id)
    {
        $this->preExcute();
        $parameters = $this->getParams();
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
    /**
    * @Rest\View(statusCode=201)
    * @Rest\Post("/order")
    * @ApiDoc(
    * description="create the order by param",
    * resource=false,
    * output="Cabinate\DAOBundle\Entity\OrderPlan",
    *  requirements={
    *      {
    *          "name"="content",
    *          "dataType"="Cabinate\DAOBundle\Entity\OrderPlan",
    *          "requirement"="json",
    *          "description"="currently it should contins a entity OrderPlan({'TradeSessionId':'integer','product':{'id':'product_id'},'table':{'id':'table_id'}}) encode by json ,time and status will be ignored"
    *      }
    *  })
    */
    public function postAction()
    {
        $this->preExcute();
        $parameters = $this->getParams();
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
    /**
    * @Rest\View(statusCode=204)
    * @Rest\Patch("/order")
    * @ApiDoc(    
    * description="change the order by param,currently support status,and this will effect served_time",
    * resource=false,
    * output="string",
    * input="integer",
    *  requirements={
    *      {
    *          "name"="content",
    *          "dataType"="string",
    *          "requirement"="json",
    *          "description"="currently it should contins {'op':'change','path':'status','new':'new value',[,'old':'old value' ]}"
    *      }
    *  },
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="Order Id"}
    * })
    */
    public function patchAction($id)
    {
        $this->preExcute();
        $parameters = $this->getParams();
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
    /**
    * @Rest\View(statusCode=204)
    * @Rest\Delete("/order")
    * @ApiDoc(
    * description="delete the order by param with id",
    * resource=false,
    * output="string",
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="OrderPlan Id"}
    * })
    */
    public function deleteAction($id)
    {
        $this->preExcute();
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

}
