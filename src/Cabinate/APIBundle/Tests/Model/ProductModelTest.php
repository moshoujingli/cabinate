<?php

namespace Cabinate\APIBundle\Tests\Model;

use Cabinate\APIBundle\Model\ProductModel;
use Cabinate\UtilsBundle\lib\CabinateTestUnit;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
class ProductModelTest extends CabinateTestUnit
{
    private $object;
    private $params;
    public function setup()
    {
        parent::setup();
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


    } 
    public function testDeleteProduct()
    {
       # code...
    }   
    public function testChangeProduct()
    {


    }
}
