<?php

namespace AppBundle\Service;

use AppBundle\Model\Cart;
use AppBundle\Model\CartItem;
use AppBundle\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CartProvider
 * @package AppBundle\Service
 */
class CartProvider
{
    const SESSION_CART_KEY = 'cart';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var Cart
     */
    private $cart;


    /**
     * Constructor.
     *
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     */
    public function __construct(
        SessionInterface $session,
        ProductRepository $productRepository
    ) {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * Returns the cart.
     *
     * @return Cart
     */
    public function getCart()
    {
        $this->loadCart();

        return $this->cart;
    }

    /**
     * Adds the product to the cart.
     *
     * @param int $productId
     * @param int $quantity
     *
     * @return bool Whether it succeed or not.
     */
    public function addToCart($productId, $quantity)
    {
        $this->loadCart();

        if (null !== $product = $this->findProductById($productId)) {
            $item = new CartItem();
            $item
                ->setProduct($product)
                ->setQuantity($quantity);

            $this->cart->addItem($item);

            $this->saveCart();

            return true;
        }

        return false;
    }

    /**
     * Saves the cart.
     */
    public function saveCart()
    {
        $this->loadCart();

        $data = [];

        foreach ($this->cart->getItems() as $item) {
            $data[$item->getProduct()->getId()] = $item->getQuantity();
        }

        $this->session->set(static::SESSION_CART_KEY, $data);
    }

    /**
     * Loads the cart from the session.
     */
    private function loadCart()
    {
        // Abort if already loaded
        if (null !== $this->cart) {
            return;
        }

        $this->cart = new Cart();

        // Get the cart data from the session
        $data = $this->session->get(static::SESSION_CART_KEY, []);

        // If data is not empty, a cart has been previously saved
        if (!empty($data)) {
            foreach ($data as $productId => $quantity) {
                /** @var \AppBundle\Entity\Product $product */
                if (null !== $product = $this->productRepository->find($productId)) {
                    // Product has been found, create the cart item
                    $item = new CartItem();
                    $item
                        ->setProduct($product)
                        ->setQuantity($quantity);

                    $this->cart->addItem($item);
                }
            }
        }
    }

    /**
     * Finds a product by its id.
     *
     * @param int $productId
     *
     * @return null|\AppBundle\Entity\Product
     */
    private function findProductById($productId)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->productRepository->find($productId);
    }
}
