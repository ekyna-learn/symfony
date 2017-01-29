<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    /**
     * Shop Image action.
     *
     * @param int $imageId
     *
     * @return BinaryFileResponse
     */
    public function imageAction($imageId)
    {
        /** @var \AppBundle\Entity\Image $image */
        $image = $this->getDoctrine()->getRepository('AppBundle:Image')->find($imageId);

        if (null === $image) {
            throw $this->createNotFoundException('Image not found');
        }

        return new BinaryFileResponse($image->getFile());
    }
}
