<?php

namespace Cabinate\APIBundle\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Cabinate\DAOBundle\Entity\TableUnit;
class TableController extends APIBaseController 
{

    public function preExcute()
    {
        parent::preExcute();
        $this->repository = $this->getDoctrine()->getRepository(TableUnit::getEntityName());
    }
    /**
    * @View()
    * "get_table"      [GET] /tables/{key}
    * @ApiDoc()
    */
    public function getAction($key)
    {
        $this->preExcute();
        return $this->repository->findOneBy(array('tableKey'=>$key));

    }
    /**
    * @View()
    * "modify_table"      [PATCH] /tables/{key}
    * @ApiDoc()
    */
    public function patchAction($key)
    {
        $table = $this->getAction($key);
    }

}
