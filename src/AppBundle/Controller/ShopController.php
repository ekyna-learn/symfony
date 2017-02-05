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
        $categories = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findBy(['enabled' => true]);

        return $this->render('AppBundle:Shop:index.html.twig', [
            'categories' => $categories,
        ]);
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
        $category = $this->findCategoryBySlug($categorySlug);

        $products = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findByCategory($category);

        return $this->render('AppBundle:Shop:category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
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
        $category = $this->findCategoryBySlug($categorySlug);

        $product = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findOneBySlug($productSlug);

        if (!$product || $category != $product->getCategory()) {
            throw $this->createNotFoundException('Product not found.');
        }

        return $this->render('AppBundle:Shop:product.html.twig', [
            'product' => $product,
        ]);
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

        $uploader = $this->get('app.upload.image_uploader');
        if (null === $file = $uploader->loadFile($image->getFile())) {
            throw $this->createNotFoundException('File not found');
        }

        $response = new BinaryFileResponse($file);
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * Cart action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartAction()
    {
        return $this->render('AppBundle:Shop:cart.html.twig');
    }

    /**
     * Finds the category by his slug.
     *
     * @param string $slug
     *
     * @return \AppBundle\Entity\Category|null
     */
    private function findCategoryBySlug($slug)
    {
        $category = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Category not found.');
        }

        return $category;
    }
}
