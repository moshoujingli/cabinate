<?php

namespace Cabinate\APIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController,
	FOS\RestBundle\Routing\ClassResourceInterface;
use use Symfony\Component\HttpFoundation\Request;

class APIBaseController extends FOSRestController implements ClassResourceInterface 
{
    public function preExcute()
    {
    }

    private function auth(Request $request)
    {
    	# code...
    }
}
