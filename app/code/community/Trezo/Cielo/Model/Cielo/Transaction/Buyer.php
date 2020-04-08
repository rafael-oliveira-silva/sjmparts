<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Model_Cielo_Transaction_Buyer extends Mage_Core_Model_Abstract
{

    public function __construct(Mage_Sales_Model_Order $order, $sale)
    {
        $this->setBuyerData($sale, $order);
    }

    public function setBuyerData($sale, $order)
    {
        $buyer = $this->addBuyerData($sale, $order);

        $billingAddressCielo = $buyer->address();
        $this->addAddressData($order->getBillingAddress(), $billingAddressCielo);

        $deliveryAddressCielo = $buyer->deliveryAddress();

        $shippingAddress = $order->getBillingAddress();

        if ($order->getShippingAddress()) {
            $shippingAddress = $order->getShippingAddress();
        }

        $this->addAddressData($shippingAddress, $deliveryAddressCielo);
    }

    public function addAddressData($address, $addressCielo)
    {
        $buyerAddress = new Trezo_Cielo_Model_Cielo_Transaction_Buyer_Address($address, $addressCielo);
        $buyerAddress->addAddressData();
        return $addressCielo;
    }

    public function addBuyerData($sale, $order)
    {
        $customer = new Trezo_Cielo_Model_Cielo_Transaction_Buyer_Customer($order);
        $buyer = $sale->customer($customer->getName())
                  ->setEmail($customer->getEmail())
                  ->setIdentity($customer->getDocumentNumber())
                  ->setIdentityType($customer->getPersonType());

        if ($birthDate = $customer->getBirthDate()) {
            $buyer->setBirthDate($birthDate);
        }

        return $buyer;
    }
}