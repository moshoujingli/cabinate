<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\Product;
use Cabinate\DAOBundle\Entity\Restaurant;
use Cabinate\APIBundle\Exceptions\ResourceNotFoundException;
use Cabinate\APIBundle\Exceptions\BadOperationException;
use Cabinate\APIBundle\Model\ProductModel;


class ProductController extends APIBaseController 
{
    public function preExcute()
    {
        parent::preExcute();
        $this->model= new ProductModel($this->em,$this->logger);
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
    *     {"name"="type", "dataType"="smallint", "required"=false, "description"="Product Type"},
    *     {"name"="status", "dataType"="smallint", "required"=false, "description"="Product status"},
    *     {"name"="restaurant_id", "dataType"="integer", "required"=false, "description"="Product restaurant id"},
    *     {"name"="floor", "dataType"="float", "required"=false, "description"="Product price low bound"},
    *     {"name"="ceiling", "dataType"="float", "required"=false, "description"="Product price up bound"},
    *
    * })
    */
    public function getAction()
    {
        $this->preExcute();
        $query = $this->getRequest()->query;
        return $this->model->getProduct($query);
    }
    /**
    * @Rest\View(statusCode=204)
    * @Rest\Put("/product")
    * @ApiDoc(
    * description="update the product by param with id",
    * resource=false,
    * output="string",
    *  requirements={
    *      {
    *          "name"="content",
    *          "dataType"="Cabinate\DAOBundle\Entity\Product",
    *          "requirement"="json",
    *          "description"="currently it should contins a entity Products encode by json"
    *      }
    *  },
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="Product Id"}
    *
    * })
    */
    public function putAction($id)
    {
        $this->preExcute();
        $this->model->changeProduct($id,$this->getParams());
    }
    /**
    * @Rest\View(statusCode=201)
    * @Rest\Post("/product")
    * @ApiDoc(
    * description="create the product by param",
    * resource=false,
    * output="Cabinate\DAOBundle\Entity\Product",
    *  requirements={
    *      {
    *          "name"="content",
    *          "dataType"="Cabinate\DAOBundle\Entity\Product",
    *          "requirement"="json",
    *          "description"="currently it should contins a entity Product({'..':'..','restaurant':{'id':'restaurant_id'}}) encode by json"
    *      }
    *  })
    */
    public function postAction()
    {
        $this->preExcute();        
        return $this->model->saveProduct($this->getParams());

    }
    /**
    * @Rest\View(statusCode=204)
    * @Rest\Delete("/product")
    * @ApiDoc(
    * description="delete the product by param with id",
    * resource=false,
    * output="string",
    * parameters={
    *     {"name"="id", "dataType"="integer", "required"=false, "description"="Product Id"}
    * })
    */
    public function deleteAction($id)
    {
        $this->preExcute();
        $this->model->deleteProduct($id);
    }


}
