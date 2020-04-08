<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Model_Cielo_Transaction_ShoppingCart extends Mage_Core_Model_Abstract
{

    protected $shoppingCart;

    public function __construct(Mage_Sales_Model_Order $order)
    {
        //Dados do Carrinho de compras
        $this->shoppingCart  = new stdClass();
        $this->addShoppingCart($this->shoppingCart, $order);
        $this->addShoppingCartItem($this->shoppingCart, $order);
    }

    public function getShoppingCart()
    {
        return $this->shoppingCart;
    }

    /**
     * Add order's informations to cielo's shopping cart object
     */
    public function addShoppingCart($shoppingCart, $order)
    {
        $shoppingCart->IsGift = false;
        $shoppingCart->ReturnsAccepted = false;
    }

    /**
     * Add items' informations to cielo's shopping cart item object
     */
    public function addShoppingCartItem($shoppingCart, $order)
    {
        $items = [];
        foreach ($order->getAllVisibleItems() as $item) {
            //Adiciona um item ao carrinho
            $cartItem = new stdClass();
            $cartItem->Name = $item->getName();
            $cartItem->Quantity = $item->getQtyOrdered();
            $cartItem->Sku = $item->getSku();
            $cartItem->UnitPrice = Mage::helper('trezo_cielo')->treatToCents($item->getPrice());
            $items[] = $cartItem;
        }

        $shoppingCart->Items = $items;
    }
}