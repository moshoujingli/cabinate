<?php

namespace Cabinate\APIBundle\Tests\Model;

use Cabinate\APIBundle\Model\ProductModel;
use Cabinate\UtilsBundle\lib\CabinateTestUnit;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\DAOBundle\Entity\Product;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
class ProductModelTest extends CabinateTestUnit
{
    private $object;
    private $params;
    public function setup()
    {
        parent::setup();
        $repository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\ProductRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repository->expects($this->any())
            ->method('findOneById')
            ->will($this->returnCallback(function ($id)
            {
             $rtn = array(new Product(),null);
             return $rtn[$id];
            }));
         $repository->expects($this->any())
            ->method('search')
            ->will($this->returnCallback(function ($param)
            {
                if (isset($param['id'])&&$param['id']==0) {
                    return new Product();
                }
            }));
        $restaurantRepository=$this->getMockBuilder('Cabinate\DAOBundle\Entity\RestaurantRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $restaurantRepository->expects($this->any())
             ->method('findOneBy')
             ->will($this->onConsecutiveCalls(new Restaurant(),null));
        $rtn = array(
                    Restaurant::getEntityName()=>$restaurantRepository,
                    Product::getEntityName()=>$repository);
        $this->em->expects($this->any())
             ->method('getRepository')
             ->will($this->returnCallback(function ($name) use ($rtn)
             {
                 return $rtn[$name];
             }));
        $this->object = new ProductModel($this->em,$this->logger);
    }
    public function teardown()
    {
      
    }
    public function testGetProduct()
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
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for id a ');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>'s');
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for status s');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>2,'name'=>'');
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for no name');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>2,'name'=>'');
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for no name');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>2,'name'=>'s','type'=>'a');
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for type a');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>2,'name'=>'s','type'=>2,'floor'=>'a');
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for floor a');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>2,'name'=>'s','type'=>2,'floor'=>2,'ceiling'=>'a');
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for ceiling a');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>2,'name'=>'s','type'=>2,'floor'=>2,'ceiling'=>5,'restaurant_id'=>'a');
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for restaurant_id a');
        }catch(BadOperationException $e){
        }
        try{
            $this->params=array('id'=>1,'status'=>2,'name'=>'s','type'=>2,'floor'=>2,'ceiling'=>5,'restaurant_id'=>0);
            $this->object->getProduct($query);
            $this->fail('Expecct Exception for id 1');
        }catch(ResourceNotFoundException $e){
        }
        $this->params=array('id'=>0,'status'=>1,'name'=>'s','type'=>2,'floor'=>2,'ceiling'=>5,'restaurant_id'=>0);
        $this->object->getProduct($query);
    }    
    public function testSaveProduct()
    {
        $params = array('name'=>'kuk','price'=>12,'type'=>1,'restaurant'=>array('id'=>1));
        $this->object->saveProduct($params);
        try{
            $this->object->saveProduct($params);
            $this->fail('Expecct Exception for no restaurant');
        }catch(ResourceNotFoundException $e){
        }
        try{
            $params = array();
            $this->object->saveProduct($params);
            $this->fail('Expecct Exception for bad param');
        }catch(BadOperationException $e){
        }
    } 
    public function testDeleteProduct()
    {
        $this->object->deleteProduct(0);
        try{
            $this->object->deleteProduct(1);
            $this->fail('Expecct Exception for bad id');
        }catch(ResourceNotFoundException $e){
        }

    }   
    public function testChangeProduct()
    {
        $params = array('name'=>'kuk','price'=>12,'type'=>1,'restaurant'=>array('id'=>1));
        $this->object->changeProduct(0,$params);
        try{
            $this->object->changeProduct(1,$params);
            $this->fail('Expecct Exception for no product');
        }catch(ResourceNotFoundException $e){
        }

        try{
            $this->object->changeProduct(0,$params);
            $this->fail('Expecct Exception for no restaurant');
        }catch(ResourceNotFoundException $e){
        }
        try{
            $params = array();
            $this->object->changeProduct(1,$params);
            $this->fail('Expecct Exception for bad param');
        }catch(BadOperationException $e){
        }
    }
}
