<?php

namespace Cabinate\UtilsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CabinateUtilsBundle:Default:index.html.twig', array('name' => $name));
    }
}
