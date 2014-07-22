<?php

namespace Cabinate\APIBundle\Tests\Model;

use Cabinate\APIBundle\Model\OrderPlanModel;
use Cabinate\UtilsBundle\lib\CabinateTestUnit;
use Cabinate\DAOBundle\Entity\OrderPlan;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Product;
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
        $restaurant= new Restaurant();
        $tableUnit = new TableUnit();
        $tableUnit->setRestaurant($restaurant);
        $product = new Product();
        $product->setRestaurant($restaurant);
        $tableUnitRepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\TableUnitRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $tableUnitRepository->expects($this->any())
             ->method('findOneBy')
             ->will($this->onConsecutiveCalls($tableUnit,null));
        $productRepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\ProductRepository')
            ->disableOriginalConstructor()
            ->getMock();


        $productRepository->expects($this->any())
             ->method('findOneBy')
             ->will($this->onConsecutiveCalls($product,null));

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
        $query  = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')
            ->disableOriginalConstructor()
            ->getMock();
        $ctx = $this;
        $query->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function ($name) use($ctx)
            {
                $params = $ctx->params;
                return isset($params[$name])?$params[$name]:null;
            }));
        $this->params=array(
            'id'=>0,
            'status'=>0,
            'TradeSessionId'=>0,
            'product_id'=>0,
            'table_id'=>0,
            'createdTime'=>'11-33',
            'updatedTime'=>'11-33',
            'servedTime'=>'11-33'
            );
        $this->object->getOrderPlan($query);

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
        $parameters = array();
        $parameters['table']= array();
        $parameters['product']= array();
        $parameters['TradeSessionId']= 1;
        $this->object->saveOrderPlan($parameters);
    }    
    public function testChangeState()
    {
        $parameters = array('op'=>'change','path'=>'status','new'=>'');
        $id= 0;
        $this->object->changeOrderPlanStatus($id,$parameters);

    }    
    public function testChangeOrderPlan()
    {
        $parameters = array();
        $parameters['table']= array();
        $parameters['status']= 2;
        $parameters['product']= array();
        $parameters['TradeSessionId']= 1;
        $this->object->changeOrderPlanContent(0,$parameters);

    }
}
