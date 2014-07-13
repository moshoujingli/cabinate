<?php

namespace Cabinate\APIBundle\Tests\Model;

use Cabinate\APIBundle\Model\RestaurantModel;
use Cabinate\UtilsBundle\lib\CabinateTestUnit;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
class RestaurantModelTest extends CabinateTestUnit
{
    private $object;
    private $params;
    public function setup()
    {
        parent::setup();
        $repository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\RestaurantRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->em->expects($this->any())
             ->method('getRepository')
             ->will($this->returnValue($repository));
        $this->object = new RestaurantModel($this->em,$this->logger);
    }
    public function teardown()
    {
      
    }
    public function testGet()
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
        try{
            $this->params=array('id'=>'a');
            $this->object->getRestaurant($query);
            $this->fail('Expecct Exception for id a ');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>'s');
            $this->object->getRestaurant($query);
            $this->fail('Expecct Exception for status s');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>2,'name'=>'');
            $this->object->getRestaurant($query);
            $this->fail('Expecct Exception for no name');
        }catch(BadOperationException $e){
        }
        $this->params=array('id'=>1,'status'=>1,'name'=>'s');
        $this->object->getRestaurant($query);

    }    
    public function testPost()
    {
        $this->assertEquals(false, $this->object->saveRestaurant(array()));
        $resaurant = $this->object->saveRestaurant(array('name'=>'a'));
        $this->assertEquals('a',$resaurant->getName());

    }    
    public function testPut()
    {
        $repository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\RestaurantRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repository->expects($this->any())
            ->method('findOneById')
            ->will($this->onConsecutiveCalls(new Restaurant(),null));
       $this->em = $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repository));
        $this->object = new RestaurantModel($this->em,$this->logger);

        try{
            $this->object->changeRestaurant(1,array('op'=>'change','path'=>'status','new'=>2));
            $this->fail('Expecct Exception for new 2');
        }catch(BadOperationException $e){
        }
        $this->assertNull( 
            $this->object->changeRestaurant(0,array('op'=>'change','path'=>'status','new'=>1))
            );
        try{
            $this->object->changeRestaurant(1,array('op'=>'change','path'=>'status','new'=>1));
            $this->fail('Expecct Exception for bad id');
        }catch(ResourceNotFoundException $e){
        }

    }
}
