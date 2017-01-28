<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ShopController
 * @package AppBundle\Controller
 */
class ShopController extends Controller
{
    /**
     * Shop index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Shop:index.html.twig');
    }
}
