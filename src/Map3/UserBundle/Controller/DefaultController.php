<?php

namespace Map3\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Map3UserBundle:Default:index.html.twig', array('name' => $name));
    }
}
