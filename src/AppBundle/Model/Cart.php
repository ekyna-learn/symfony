<?php

namespace AppBundle\Model;

use AppBundle\Entity\Product;

/**
 * Class Cart
 * @package AppBundle\Model
 */
class Cart
{
    /**
     * @var array|CartItem[]
     */
    private $items;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Returns whether or not the cart has the given item,
     * regarding to the item's product.
     *
     * @param CartItem $item
     *
     * @return bool
     */
    public function hasItem(CartItem $item)
    {
        return null !== $this->findItemByProduct($item->getProduct());
    }

    /**
     * Adds the item, or adds the quantity if an item
     * already exists for the same product.
     *
     * @param CartItem $item
     *
     * @return Cart
     */
    public function addItem(CartItem $item)
    {
        if (null !== $i = $this->findItemByProduct($item->getProduct())) {
            $i->setQuantity($i->getQuantity() + $item->getQuantity());
        } else {
            $this->items[] = $item;
        }

        return $this;
    }

    /**
     * Removes the item matching the given product id.
     *
     * @param $productId
     */
    public function removeItemByProductId($productId)
    {
        foreach ($this->items as $index => $item) {
            if ($productId === $item->getProduct()->getId()) {
                unset($this->items[$index]);
                break;
            }
        }
    }

    /**
     * Returns all the cart items.
     *
     * @return array|CartItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Finds the item for the given product.
     *
     * @param Product $product
     *
     * @return CartItem|null
     */
    private function findItemByProduct(Product $product)
    {
        foreach ($this->items as $item) {
            if ($product === $item->getProduct()) {
                return $item;
            }
        }

        return null;
    }
}
