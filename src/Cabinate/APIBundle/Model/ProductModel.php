<?php

namespace Cabinate\APIBundle\Model;
use Cabinate\APIBundle\Exceptions\BadOperationException;

use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\DAOBundle\Entity\TableUnit;
use Cabinate\DAOBundle\Entity\Restaurant;

class ProductModel
{
    private $em;
    private $logger;
    public function __construct($em,$logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }
    public function getProduct($query)
    {
        # code...
    }
    public function changeProduct($id,$params)
    {
        # code...
    }
    public function deleteProduct($id)
    {
        # code...
    }
    public function saveProduct($params)
    {
        # code...
    }
}
