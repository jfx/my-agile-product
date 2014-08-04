<?php

namespace Map3\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Map3CoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
