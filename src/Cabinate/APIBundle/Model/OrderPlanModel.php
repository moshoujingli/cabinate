<?php

namespace Cabinate\APIBundle\Model;
use Cabinate\APIBundle\Exceptions\BadOperationException;

use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Restaurant;

class OrderPlanModel
{
    private $em;
    private $logger;
    public function __construct($em,$logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }
    public function getOrderPlan($query)
    {
        # code...
    }
    public function changeOrderPlanContent($id,$params)
    {
        # code...
    }
    public function changeOrderPlanStatus($id,$params)
    {
        # code...
    }
    public function deleteOrderPlan($id)
    {
        # code...
    }
    public function saveOrderPlan($params)
    {
        # code...
    }
}
