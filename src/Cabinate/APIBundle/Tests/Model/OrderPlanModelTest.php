<?php

namespace Cabinate\APIBundle\Tests\Model;

use Cabinate\APIBundle\Model\OrderPlanModel;
use Cabinate\UtilsBundle\lib\CabinateTestUnit;
use Cabinate\DAOBundle\Entity\OrderPlan;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Product;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
class OrderPlanModelTest extends CabinateTestUnit
{
    private $object;
    private $params;
    public function setup()
    {
        parent::setup();
        $repository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\OrderPlanRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repository->expects($this->any())
            ->method('findOneById')
            ->will($this->returnCallback(function ($id)
            {
                $invalidateOP = new OrderPlan();
                $invalidateOP->setStatus(2);
             $rtn = array(new OrderPlan(),$invalidateOP,null);
             return $rtn[$id];
            }));
         $repository->expects($this->any())
            ->method('search')
            ->will($this->returnCallback(function ($param)
            {
                if (isset($param['id'])&&$param['id']==0) {
                    return new OrderPlan();
                }
            }));
        $tableUnitRepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\TableUnitRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $tableUnitRepository->expects($this->any())
             ->method('findOneBy')
             ->will($this->onConsecutiveCalls(new TableUnit(),null));
        $productRepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\ProductRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $productRepository->expects($this->any())
             ->method('findOneBy')
             ->will($this->onConsecutiveCalls(new Product(),null));

        $rtn = array(
                    OrderPlan::getEntityName()=>$repository,
                    TableUnit::getEntityName()=>$tableUnitRepository,
                    Product::getEntityName()=>$productRepository);
        $this->em->expects($this->any())
             ->method('getRepository')
             ->will($this->returnCallback(function ($name) use ($rtn)
             {
                 return $rtn[$name];
             }));
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
        try{
            $this->object->deleteOrderPlan(2);
            $this->fail('Expecct Exception for bad id');
        }catch(ResourceNotFoundException $e){
        }
        try{
            $this->object->deleteOrderPlan(1);
            $this->fail('Expecct Exception for bad param');
        }catch(BadOperationException $e){
        }

        $this->object->deleteOrderPlan(0);
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
