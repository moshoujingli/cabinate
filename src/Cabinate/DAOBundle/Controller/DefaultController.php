<?php

namespace Cabinate\DAOBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CabinateDAOBundle:Default:index.html.twig', array('name' => $name));
    }
}
