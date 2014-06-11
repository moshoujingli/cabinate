<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Form\TableUnitType;
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
    * @Rest\Get("/tables")
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
            if (strlen($query->get('key'))!=40) {
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
        return $this->repository->search($parameters);
    }
    /**
    * @Rest\View()
    * @Rest\Patch("/tables/$key", requirements={"key" = "\w{40}"})
    * "modify_table"      [PATCH] /tables/{key}
    * @ApiDoc()
    */
    public function patchAction($key)
    {
        $table = $this->getAction($key);
    }

}
