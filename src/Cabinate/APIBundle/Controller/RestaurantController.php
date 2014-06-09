<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\Restaurant;

class RestaurantController extends APIBaseController 
{

    public function preExcute()
    {
        parent::preExcute();
        $this->repository = $this->getDoctrine()->getRepository(Restaurant::getEntityName());
    }


    /**
    * @View()
    * @ApiDoc()
    * "get_restaurant"      [GET] /restaurants
    */
    public function cgetAction(){
                $this->preExcute();
        return $this->repository->findAll();
    } 
    /**
    * @View()
    * "get_restaurant"      [GET] /restaurants/{id}
    * @ApiDoc()
    */
    public function getAction($id){
        $this->preExcute();
        return $this->repository->find($id);
    }

}
