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
    private $restaurantRepository;
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
        $parameters = array();
        $query = $this->getRequest()->query;
        if ($query->get('id')!==null) {
            if (is_numeric($query->get('id'))) {
                $parameters['id']=$query->get('id');
            }else{
                throw new BadOperationException('id must be a number');
            }
        }
        if ($query->get('name')!==null) {
            if (strlen($query->get('name'))!==0) {
                $parameters['name']=$query->get('name');
            }else{
                throw new BadOperationException('name is not in a right format');
            }
        }
        if ($query->get('status')!==null) {
            if (is_numeric($query->get('status'))) {
                $parameters['status']=$query->get('status');
            }else{
                throw new BadOperationException('status must be a number');
            }
        }
        if ($query->get('type')!==null) {
            if (is_numeric($query->get('type'))) {
                $parameters['type']=$query->get('type');
            }else{
                throw new BadOperationException('type must be a number');
            }
        }
        if ($query->get('floor')!==null) {
            if (is_numeric($query->get('floor'))) {
                $parameters['floor']=$query->get('floor');
            }else{
                throw new BadOperationException('floor must be a number');
            }
        }
        if ($query->get('ceiling')!==null) {
            if (is_numeric($query->get('ceiling'))) {
                $parameters['ceiling']=$query->get('ceiling');
            }else{
                throw new BadOperationException('ceiling must be a number');
            }
        }
        if ($query->get('restaurant_id')!==null) {
            if (is_numeric($query->get('restaurant_id'))) {
                $parameters['restaurant_id']=$query->get('restaurant_id');
            }else{
                throw new BadOperationException('restaurant_id must be a number');
            }
        }
        $this->logger->info(print_r($query,true));
        $this->logger->info(print_r($parameters,true));
        $products = $this->repository->search($parameters);
        if (count($products)) {
            return $products;
        }else{
            throw new ResourceNotFoundException("No product with parameters like ".json_encode($parameters));
        }
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
        $product = $this->repository->findOneById($id);
        if ($product instanceof Product) {
            $parameters = $this->getParams();
            $restaurant = $this->restaurantRepository->findOneBy($parameters['restaurant']);
            if (!($restaurant instanceof Restaurant)) {
                throw new ResourceNotFoundException("Restaurant entity not found :".json_encode($parameters['restaurant']));
            }
            $product->setRestaurant($restaurant);
            $product->setName($parameters['name']);
            $product->setPrice($parameters['price']);
            $product->setStatus(isset($parameters['status'])?$parameters['status']:0);
            $product->setType($parameters['type']);
            $this->em->persist($product);
            $this->em->flush();

        }else{
            throw new ResourceNotFoundException("Product to update not exists with id $id");
        }
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
