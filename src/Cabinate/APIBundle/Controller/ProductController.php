<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\Product;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\APIBundle\Exceptions\BadOperationException;
/**
 *
 * get (id,restaurant,type,price)
 * post (restaurant,type,price[,status])
 * put (id=>restaurant,type,price[,status])
 * delete (id)
 *
 */
class ProductController extends APIBaseController 
{
    public function preExcute()
    {
        parent::preExcute();
        $this->repository = $this->getDoctrine()->getRepository(Product::getEntityName());
    }
    /**
    * @Rest\View()
    * @Rest\Get("/product")
    * @ApiDoc(
    * description="get the product by param",
    * resource=true,
    * output="Cabinate\DAOBundle\Entity\Product",
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="Product Id"},
    *     {"name"="name", "dataType"="string", "required"=false, "description"="Product Name"},
    *     {"name"="status", "dataType"="integer", "required"=false, "description"="Product status"},
    *
    * })
    */
    public function getAction()
    {
        # code...
    }
    public function putAction($id)
    {
        # code...
    }
    public function postAction()
    {
        # code...
    }
    public function deleteAction($id)
    {
        # code...
    }


}
