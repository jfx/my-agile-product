<?php

namespace Map3\ReleaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Map3ReleaseBundle:Default:index.html.twig', array('name' => $name));
    }
}