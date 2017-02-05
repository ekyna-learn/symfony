<?php

namespace AppBundle\Service;

/**
 * Class CartCalculator
 * @package AppBundle\Service
 */
class CartCalculator
{
    /**
     * @var CartProvider
     */
    private $cartProvider;

    /**
     * Constructor.
     *
     * @param CartProvider $cartProvider
     */
    public function __construct(CartProvider $cartProvider)
    {
        $this->cartProvider = $cartProvider;
    }

    /**
     * Returns the cart's total amount.
     *
     * @return float
     */
    public function getCartTotal()
    {
        // TODO

        return 0;
    }
}
