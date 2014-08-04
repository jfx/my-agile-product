<?php

namespace Map3\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Map3ProductBundle:Default:index.html.twig', array('name' => $name));
    }
}
