<?php

namespace BiberLtd\Bundle\LocationManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BiberLtdLocationManagementBundle:Default:index.html.twig', array('name' => $name));
    }
}
