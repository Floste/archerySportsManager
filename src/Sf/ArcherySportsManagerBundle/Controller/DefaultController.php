<?php

namespace Sf\ArcherySportsManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('SfArcherySportsManagerBundle:Default:index.html.twig');
    }
}
