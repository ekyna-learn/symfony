<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DashboardController
 * @package AppBundle\Controller\Admin
 */
class DashboardController extends Controller
{
    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Admin/Dashboard:index.html.twig');
    }
}
