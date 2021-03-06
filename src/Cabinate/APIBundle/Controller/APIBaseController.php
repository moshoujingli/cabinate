<?php

namespace Cabinate\APIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Routing\ClassResourceInterface;

use Symfony\Component\HttpFoundation\Request;

class APIBaseController extends FOSRestController implements ClassResourceInterface 
{
    protected $model;
    protected $em;
    protected $logger;
    protected function preExcute()
    {
        $this->em = $this->getDoctrine()->getManager();
        $this->logger = $this->get('logger');
    }

    private function auth(Request $request)
    {
        # code...
    }
    protected function getParams()
    {
        $content = $this->getRequest()->request->all();
        return ($content);
    }
}
