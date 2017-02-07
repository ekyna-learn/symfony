<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $cart = $this->getCartProvider()->getCart();

        $total = $this
            ->get('app.service:cart_calculator')
            ->getCartTotal();

        return $this->render('AppBundle:Shop:cart.html.twig', [
            'cart'  => $cart,
            'total' => $total,
        ]);
    }

    /**
     * Add product to cart action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $productId = $request->attributes->get('productId');
        $quantity = $request->attributes->get('quantity');

        if ($this->getCartProvider()->add($productId, $quantity)) {
            $this->addFlash('success', 'Le produit a bien été ajouté au panier');
        } else {
            $this->addFlash('danger', 'Erreur lors de l\'ajout au panier');
        }

        // Redirige vers l'url précédente
        $referer = $request->headers->get('referer');
        if (0 < strlen($referer)) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_shop_index');
    }

    /**
     * Remove product form cart action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction(Request $request)
    {
        $productId = $request->attributes->get('productId');

        if ($this->getCartProvider()->remove($productId)) {
            $this->addFlash('success', 'Le produit a bien été retiré du panier');
        } else {
            $this->addFlash('danger', 'Erreur lors du retrait du panier');
        }


        return $this->redirectToRoute('app_shop_cart');
    }

    /**
     * Decrement product action.
     *
     * @param int $productId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function decrementAction($productId)
    {
        $this->getCartProvider()->decrement($productId);

        return $this->redirectToRoute('app_shop_cart');
    }

    /**
     * Increment product action.
     *
     * @param int $productId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function incrementAction($productId)
    {
        $this->getCartProvider()->increment($productId);

        return $this->redirectToRoute('app_shop_cart');
    }

    /**
     * Returns the cart provider.
     *
     * @return \AppBundle\Service\CartProvider
     */
    private function getCartProvider()
    {
        return $this->get('app.service.cart_provider');
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
