<?php

namespace Cabinate\APIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController,
	FOS\RestBundle\Routing\ClassResourceInterface;

use Symfony\Component\HttpFoundation\Request;

class APIBaseController extends FOSRestController implements ClassResourceInterface 
{
	protected $repository;

    protected function preExcute()
    {
    }

    private function auth(Request $request)
    {
    	# code...
    }
}
