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


    }
}
