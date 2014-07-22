<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\OrderPlan;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Product;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Model\OrderPlanModel;


/**
 *
 * gppd
 *
 *
 */
class OrderController extends APIBaseController 
{
    public function preExcute()
    {
        parent::preExcute();
        $this->model = new OrderPlanModel($this->em,$this->logger);
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
        return $this->model->getOrderPlan($this->getRequest()->query);
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
        $this->model->changeOrderPlanContent($id,$parameters);
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
        return $this->model->saveOrderPlan($parameters);
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
        $this->model->changeOrderPlanStatus($id,$parameters);
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
        $this->model->deleteOrderPlan($id);
    }

}
