<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\APIBundle\Exceptions\BadOperationException;

class RestaurantController extends APIBaseController 
{

    public function preExcute()
    {
        parent::preExcute();
        $this->repository = $this->getDoctrine()->getRepository(Restaurant::getEntityName());
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
        if ($query->get('name')!==null) {
            if (strlen($query->get('name'))!==0) {
                $parameters['name']=$query->get('name');
            }else{
                throw new BadOperationException('name cant be null');
            }
        }
        $this->logger->info(print_r($query,true));
        $this->logger->info(print_r($parameters,true));
        $restaurant = $this->repository->findBy($parameters);
        if (count($restaurant)) {
            return $restaurant;
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
        $restaurant = $this->repository->findOneById($id);
        if (count($restaurant)) {
            $parameters = $this->getParams();
            if ($parameters['op']!='change'
                ||
                $parameters['path']!='status'
                ||
                !in_array($parameters['new'], array(0,1))
                ) {
                throw new BadOperationException();
            }else{
                $restaurant->setStatus($parameters['new']);
                $this->em->persist($restaurant);
                $this->em->flush();
            }
        }else{
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
        $parameters = $this->getParams();
        $name = $parameters['name'];
        if (isset($name)&&($name)) {
            $restaurant = new Restaurant();
            $restaurant->setStatus(isset($parameters['status'])?$parameters['status']:0);
            $restaurant->setName($name);
            $this->em->persist($restaurant);
            $this->em->flush();
            return $restaurant;
        }else{
            throw new BadOperationException('name is invalid');
        }
    }

}
