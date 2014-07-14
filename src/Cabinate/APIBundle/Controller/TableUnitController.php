<?php

namespace Cabinate\APIBundle\Controller;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\DAOBundle\Form\TableUnitType;
use Cabinate\APIBundle\Model\TableUnitModel;

class TableUnitController extends APIBaseController 
{
    public function preExcute()
    {
        parent::preExcute();
        $this->model= new TableUnitModel($this->em,$this->logger);
    }
    /**
    * @Rest\View()
    * @Rest\Get("/tableunits")
    * @ApiDoc(    
    * description="get the tables by param",
    * resource=true,
    * output="Cabinate\DAOBundle\Entity\TableUnit",
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="TableUnit Id"},
    *     {"name"="key", "dataType"="string", "required"=false, "description"="TableUnit HashKey"},
    *     {"name"="status", "dataType"="integer", "required"=false, "description"="TableUnit status"},
    *     {"name"="restaurant_id", "dataType"="integer", "required"=false, "description"="Restaurant id"}
    *
    * })
    */
    public function getAction()
    {
        $this->preExcute();
        $query = $this->getRequest()->query;
        return $this->model->getTable($query);
    }
    /**
    * @Rest\View(statusCode=204)
    * @Rest\Patch("/tableunits")
    * @ApiDoc(    
    * description="change the table by param,currently only support status",
    * resource=false,
    * output="string",
    * input="integer",
    *  requirements={
    *      {
    *          "name"="content",
    *          "dataType"="string",
    *          "requirement"="json",
    *          "description"="currently it should contins {'op':'change','path':'status','new':'new status code',[,'old':'old status number' ]}"
    *      }
    *  },
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="TableUnit Id"}
    * })
    */
    public function patchAction($id)
    {
        $this->preExcute();
        $this->model->changeTable($id,$this->getParams());
    }
    /**
    * @Rest\View(statusCode=201)
    * @Rest\Post("/tableunits")
    * @ApiDoc(    
    * description="Add a new table with default status 0",
    * resource=false,
    * output="string",
    * input="integer",
    *  requirements={
    *      {
    *          "name"="content",
    *          "dataType"="string",
    *          "requirement"="json",
    *          "description"="entity Tableunit {'restaurant_id':'\d+'[,'status':'\d']} "
    *      }
    *  })
    */
    public function postAction()
    {
        $this->preExcute();
        $parameters = $this->getParams();
        return $this->model->saveTable($parameters);
    }

}
