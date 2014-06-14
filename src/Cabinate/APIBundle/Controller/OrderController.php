<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\OrderPlan;
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
    public function preExcute()
    {
        parent::preExcute();
        $this->repository = $this->getDoctrine()->getRepository(OrderPlan::getEntityName());
    }
    public function getAction()
    {
        # code...
    }
    public function putAction($id)
    {
        # code...
    }
    public function postAction()
    {
        # code...
    }
    public function deleteAction($id)
    {
        # code...
    }

}
