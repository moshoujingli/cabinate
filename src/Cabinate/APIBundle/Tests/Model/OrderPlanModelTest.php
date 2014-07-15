<?php

namespace Cabinate\APIBundle\Tests\Model;

use Cabinate\APIBundle\Model\OrderPlanModel;
use Cabinate\UtilsBundle\lib\CabinateTestUnit;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
class OrderPlanModelTest extends CabinateTestUnit
{
    private $object;
    private $params;
    public function setup()
    {
        parent::setup();
        $this->object = new OrderPlanModel($this->em,$this->logger);
    }
    public function teardown()
    {
      
    }
    public function testGetOrderPlan()
    {

    }     
    public function testDeleteOrderPlan()
    {

    }
    public function testSaveOrderPlan()
    {

    }    
    public function testChangeState()
    {


    }    
    public function testChangeOrderPlan()
    {


    }
}
