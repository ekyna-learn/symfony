<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PageController
 * @package AppBundle\Controller
 */
class PageController extends Controller
{
    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Page:index.html.twig');
    }

    /**
     * Cart action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartAction()
    {
        return $this->render('AppBundle:Page:cart.html.twig');
    }

    /**
     * Contact action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction()
    {
        return $this->render('AppBundle:Page:contact.html.twig');
    }
}
