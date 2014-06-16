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
    *     {"name"="table", "dataType"="Cabinate\DAOBundle\Entity\TableUnit", "required"=false, "description"="OrderPlan Name"},
    *     {"name"="product", "dataType"="Cabinate\DAOBundle\Entity\Product", "required"=false, "description"="OrderPlan Type"},
    *     {"name"="status", "dataType"="smallint", "required"=false, "description"="OrderPlan status"},
    *     {"name"="created_time", "dataType"="time range", "required"=false, "description"="OrderPlan created time"},
    *     {"name"="updated_time", "dataType"="time range", "required"=false, "description"="OrderPlan updated time"},
    *     {"name"="served_time", "dataType"="time range", "required"=false, "description"="OrderPlan serverd time"}
    *
    * })
    */
    public function getAction()
    {
        # code...
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
        # code...
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
    *          "description"="currently it should contins a entity OrderPlan({'..':'..','product':{'id':'product_id'},'table':{'id':'table_id'}}) encode by json ,time and status will be ignored"
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
        $orderPlan->setTradeSessionId(time());
        $this->em->persist($orderPlan);
        $this->em->flush();
        return $orderPlan;
    }
    /**
    * @Rest\View(statusCode=201)
    * @Rest\Patch("/order")
    * @ApiDoc(    
    * description="change the order by param,currently support status,served_time",
    * resource=false,
    * output="string",
    * input="integer",
    *  requirements={
    *      {
    *          "name"="content",
    *          "dataType"="string",
    *          "requirement"="json",
    *          "description"="currently it should contins {'op':'change','path':'status|served_time','new':'new value',[,'old':'old value' ]}"
    *      }
    *  },
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="Order Id"}
    * })
    */
    public function patchAction($id)
    {
        # code...
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
        # code...
    }

}
