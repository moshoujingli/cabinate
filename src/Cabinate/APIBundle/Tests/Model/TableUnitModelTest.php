<?php

namespace Cabinate\APIBundle\Tests\Model;

use Cabinate\APIBundle\Model\TableUnitModel;
use Cabinate\UtilsBundle\lib\CabinateTestUnit;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
class TableUnitModelTest extends CabinateTestUnit
{
    private $object;
    private $params;
    public function setup()
    {
        parent::setup();

    }
    public function teardown()
    {
      
    }
    public function testGetTable()
    {
         $tablerepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\TableUnitRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $tablerepository->expects($this->any())
            ->method('search')
            ->will($this->onConsecutiveCalls(array(new TableUnit()),null));
        $this->em->expects($this->any())
             ->method('getRepository')
             ->will($this->returnValue($tablerepository));
        $this->object = new TableUnitModel($this->em,$this->logger);
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
        try{
            $this->params=array('id'=>'a');
            $this->object->getTable($query);
            $this->fail('Expecct Exception for id a ');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'key'=>'11111111111111111111111111111111111111112');//41
            $this->object->getTable($query);
            $this->fail('Expecct Exception for key');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'key'=>'1111111111111111111111111111111111111111','status'=>'a');
            $this->object->getTable($query);
            $this->fail('Expecct Exception for status');
        }catch(BadOperationException $e){
        }   
        try{
            $this->params=array('id'=>1,'key'=>'1111111111111111111111111111111111111111','status'=>1,'restaurant_id'=>'a');
            $this->object->getTable($query);
            $this->fail('Expecct Exception for restaurant_id');
        }catch(BadOperationException $e){
        }
        $this->params=array('id'=>1,'key'=>'1111111111111111111111111111111111111111','status'=>1,'restaurant_id'=>1);
        $this->object->getTable($query);
        try{
            $this->object->getTable($query);
            $this->fail('Expecct Exception for restaurant_id');
        }catch(ResourceNotFoundException $e){
        }
    }
    public function testChangeTable()
    {
        $tablerepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\TableUnitRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $tablerepository->expects($this->any())
            ->method('findOneById')
            ->will($this->onConsecutiveCalls(new TableUnit(),null));
        $this->em->expects($this->any())
             ->method('getRepository')
             ->will($this->returnValue($tablerepository));
        $this->object = new TableUnitModel($this->em,$this->logger);

        try{
            $this->object->changeTable(1,array('op'=>'change','path'=>'status','new'=>100));
            $this->fail('Expecct Exception for new 100');
        }catch(BadOperationException $e){
        }
        $this->assertNull( 
            $this->object->changeTable(0,array('op'=>'change','path'=>'status','new'=>1))
            );
        try{
            $this->object->changeTable(1,array('op'=>'change','path'=>'status','new'=>1));
            $this->fail('Expecct Exception for bad id');
        }catch(ResourceNotFoundException $e){
        }
    }
    public function testSaveTable()
    {
        $tablerepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\TableUnitRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $restaurantrepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\RestaurantRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $restaurantrepository->expects($this->any())
            ->method('findOneById')
            ->will($this->onConsecutiveCalls(new Restaurant(),null));
        $map = array(array(Restaurant::getEntityName(),$restaurantrepository),
                    array(TableUnit::getEntityName(),$tablerepository));
        $this->em->expects($this->any())
             ->method('getRepository')
             ->will($this->returnValueMap($map));
        $this->object = new TableUnitModel($this->em,$this->logger);
        try{
            $this->object->saveTable(array('restaurant_id'=>'a'));
            $this->fail('Expecct Exception for restaurant_id a');
        }catch(BadOperationException $e){
        }
        $this->assertTrue(true,$this->object->saveTable(array('restaurant_id'=>11)) instanceof TableUnit);
        try{
            $this->object->saveTable(array('restaurant_id'=>12));
            $this->fail('Expecct Exception for no restaurant');
        }catch(ResourceNotFoundException $e){
        }

    }
}
