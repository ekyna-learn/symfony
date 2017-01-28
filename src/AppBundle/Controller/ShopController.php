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

    /**
     * Shop category action.
     *
     * @param string $categorySlug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($categorySlug)
    {
        // TODO Fetch category by slug

        // TODO Fetch products by category

        return $this->render('AppBundle:Shop:category.html.twig');
    }

    /**
     * Shop product action.
     *
     * @param string $categorySlug
     * @param string $productSlug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productAction($categorySlug, $productSlug)
    {
        // TODO Fetch category by slug

        // TODO Fetch product by slug

        return $this->render('AppBundle:Shop:product.html.twig');
    }
}
