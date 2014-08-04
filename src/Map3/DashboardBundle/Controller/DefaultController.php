<?php

namespace Map3\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Map3DashboardBundle:Default:index.html.twig', array('name' => $name));
    }
}
