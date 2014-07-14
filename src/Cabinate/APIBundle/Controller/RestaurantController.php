<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\APIBundle\Model\RestaurantModel;
use Cabinate\APIBundle\Exceptions\BadOperationException;

class RestaurantController extends APIBaseController 
{

    protected function preExcute()
    {
        parent::preExcute();
        $this->model= new RestaurantModel($this->em,$this->logger);
    }
    /**
    * @Rest\View()
    * @Rest\Get("/restaurant")
    * @ApiDoc(
    * description="get the restaurant by param",
    * resource=true,
    * output="Cabinate\DAOBundle\Entity\Restaurant",
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="Restaurant Id"},
    *     {"name"="name", "dataType"="string", "required"=false, "description"="Restaurant Name"},
    *     {"name"="status", "dataType"="integer", "required"=false, "description"="Restaurant status"},
    *
    * })
    */
    public function getAction()
    {
        $this->preExcute();
        $restaurants = $this->model->getRestaurant($this->getRequest()->query);
        if (count($restaurants)) {
            return $restaurants;
        }else{
            throw new ResourceNotFoundException("No Restaurant with parameters like ".json_encode($parameters));
        }
    }
    /**
    * @Rest\View(statusCode=204)
    * @Rest\Patch("/restaurant")
    * @ApiDoc(    
    * description="change the restaurant by param,currently only support status",
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
        if ($this->model->changeRestaurant($id,$this->getParams())===0) {
            throw new ResourceNotFoundException();
        }
    }
    /**
    * @Rest\View(statusCode=201)
    * @Rest\Post("/restaurant")
    * @ApiDoc(    
    * description="Add a new restaurant with default status 0",
    * resource=false,
    * output="string",
    * input="integer",
    *  requirements={
    *      {
    *          "name"="content",
    *          "dataType"="string",
    *          "requirement"="json",
    *          "description"="entity Tableunit {'name':'\w+'[,'status':'\d']} "
    *      }
    *  })
    */
    public function postAction()
    {
        $this->preExcute();
        if ($restaurant = $this->model->saveRestaurant($this->getParams())) {
            return $restaurant;
        }else{
            throw new BadOperationException('name is invalid');
        }        
    }

}
