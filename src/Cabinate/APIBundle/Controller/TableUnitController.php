<?php

namespace Cabinate\APIBundle\Controller;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\DAOBundle\Form\TableUnitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TableUnitController extends APIBaseController 
{
    private $form;
    public function preExcute()
    {
        parent::preExcute();
        $this->repository = $this->getDoctrine()->getRepository(TableUnit::getEntityName());
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
    *     {"name"="key", "dataType"="string", "required"=false, "description"="TableUnit Name"},
    *     {"name"="status", "dataType"="integer", "required"=false, "description"="TableUnit status"},
    *     {"name"="restaurant_id", "dataType"="integer", "required"=false, "description"="Restaurant status"}
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
                return array('error'=>'id must be a number');
            }
        }
        if ($query->get('key')!==null) {
            if (strlen($query->get('key'))==40) {
                $parameters['key']=$query->get('key');
            }else{
                return array('error'=>'key is not in a right format');
            }
        }
        if ($query->get('status')!==null) {
            if (is_numeric($query->get('status'))) {
                $parameters['status']=$query->get('status');
            }else{
                return array('error'=>'status must be a number');
            }
        }
        if ($query->get('restaurant_id')!==null) {
            if (is_numeric($query->get('restaurant_id'))) {
                $parameters['restaurant_id']=$query->get('restaurant_id');
            }else{
                return array('error'=>'restaurant_id must be a number');
            }
        }
        $this->logger->info(print_r($query,true));
        $this->logger->info(print_r($parameters,true));
        $tableunits = $this->repository->search($parameters);
        if (count($tableunits)) {
            return $tableunits;
        }
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
        $result = $this->repository->search(array('id'=>$id));
        if (count($result)) {
            $tableunit = $result[0];
            $parameters = $this->getParams();
            if ($parameters['op']!='change'
                ||
                $parameters['path']!='status'
                ||
                !in_array($parameters['new'], array(0,1,2,3,4,5))
                ) {
                throw new BadOperationException();
            }else{
                $tableunit->setStatus($parameters['new']);
                $this->em->persist($tableunit);
                $this->em->flush();
            }
        }else{
            throw new ResourceNotFoundException();
        }
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
        $restaurant_id = $parameters['restaurant_id'];
        if (isset($restaurant_id)&&is_numeric($restaurant_id)) {
            $restaurantRepository = $this->getDoctrine()->getRepository(Restaurant::getEntityName());
            $restaurant = $restaurantRepository->find($restaurant_id);
            if (!($restaurant instanceof Restaurant)) {
                throw new BadOperationException('restaurant_id is not found');
            }else{
                $tableunit = new Tableunit();
                $tableunit->setStatus(isset($parameters['status'])?$parameters['status']:0);
                $tableunit->setRestaurant($restaurant);
                $tableunit->setTableKey(sha1(uniqid(mt_rand(),true)));
                $this->em->persist($tableunit);
                $this->em->flush();
                return $tableunit;
            }
        }else{
            throw new BadOperationException('restaurant_id is invalid');
        }
    }

}
