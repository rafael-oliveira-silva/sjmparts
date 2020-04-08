<?php
/**
 * Mandaê
 *
 * @category   Mandae
 * @package    Mandae_Shipping
 * @author     Thiago Contardi
 * @copyright  Copyright (c) 2017 Bizcommerce
 * @author     Bruno Ferreira
 * @copyright  Copyright (c) 2019 Mandaê
 */
class Mandae_Shipping_Model_Carrier_Mandae extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    const WEIGHT_ROUND = 2;

    protected $_helper;

    protected $_code = 'mandae';
    protected $_result = null;

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return array(
            $this->_code => $this->getConfigData('title'),
            'economico' => $this->_getHelper()->__('Economy'),
            'rapido' => $this->_getHelper()->__('Fast'),
            'super-rapido' => $this->_getHelper()->__('Express'),
            'same-day' => $this->_getHelper()->__('Sameday')
        );
    }

    /**
     * Check if current carrier offer support to tracking
     *
     * @return boolean true
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * Define ZIP Code as required
     * @param string $countryId
     * @return boolean
     */
    public function isZipCodeRequired($countryId = null)
    {
        return true;
    }

    /**
     * Coleta os valores e exibe
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return bool|false|Mage_Core_Model_Abstract|null
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        /** @var Mage_Shipping_Model_Rate_Result _result */
        $this->_result = Mage::getModel('shipping/rate_result');

        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $this->_appendMethod($request);

        return $this->_result;
    }

    /**
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return false|Mage_Core_Model_Abstract|null
     */
    protected function _appendMethod(Mage_Shipping_Model_Rate_Request $request)
    {
        /** @var Mage_Shipping_Model_Rate_Result _result */
        $this->_result = Mage::getModel('shipping/rate_result');

        try {
            $data = array();
            $allowFreeShipping = $this->getConfigData('allow_free_shipping');
            $allowedMethods = explode(',', $this->getConfigData('allowed_methods'));
            $freeshippingMethods = explode(',', $this->getConfigData('freeshipping_method'));

            // Clean Zip Code
            $this->setFromZip(preg_replace('/[^0-9]/', '', Mage::getStoreConfig('shipping/origin/postcode', $this->getStore())));
            $this->setToZip(preg_replace('/[^0-9]/', '', $request->getDestPostcode()));

            // Weight
            $weight = (float)$request->getPackageWeight();
            if ($this->getConfigData('weight_type') == 'g') {
                $weight = $weight / 1000;
            }
            $this->setPackageWeight(number_format($weight, self::WEIGHT_ROUND, '.', ''));
            $this->setFreeMethodWeight($this->getPackageWeight());

            $subtotal = 0;
            // $_items = $this->getQuote()->getAllVisibleItems();
            $_items = $request->getAllItems();

            /** @var Mage_Sales_Model_Quote_Item $_item */
            foreach ($_items as $_item) {
                //Sum quote price, without virtual and downloadable products
                if ($_item->getProductType() != 'virtual' && $_item->getProductType() != 'downloadable') {
                    $subtotal += $_item->getBaseRowTotal();
                }
            }
            $subtotal = number_format($subtotal, 2, '.', '');
            $weight = number_format($this->getPackageWeight(), 2, '.', '');

            $data['postcode'] = $this->getToZip();
            $data['items'] = $this->getItems($request);

            /** @var Mandae_Shipping_Model_Api $api */
            $api = Mage::getModel('mandae/api');
            $response = $api->shippingRate($data, $this);

            if ($response === false) {
                Mage::throwException($this->_getHelper()->__('Erro no retorno da consulta'));
            }

            if (property_exists($response, 'error')) {

                //Log the error code and error message
                $this->_getHelper()->log('Error (' . $response->error->code. '): ' . $response->error->message);

                /** @var Mage_Shipping_Model_Rate_Result_Error $error */
                $error = Mage::getModel('shipping/rate_result_error');
                $error->setCarrier($this->_code);
                $error->setCarrierTitle($this->getConfigData('title'));
                $error->setErrorMessage($this->getConfigData('specificerrmsg'));
                $this->_result->append($error);
            } else {
                $this->_getHelper()->log('Rate metadata: ' . json_encode($response->metadata));
                $response = $response->data;

                if (!property_exists($response, 'shippingServices')) {
                    Mage::throwException($this->_getHelper()->__('Erro no retorno da consulta do Web Service'));
                }

                $shippingServices = $response->shippingServices;

                foreach ($shippingServices as $shiping) {
                    //Message to title
                    $methodTitle = $this->getConfigData('message_deadline');

                    $deliveryTime = $shiping->days;
                    $shippingPrice = $shiping->price;
                    $shippingName = $shiping->name;
                    $shipingCode = $shiping->id ?: $this->_getHelper()->slugify($shippingName);

                    if (!in_array($shipingCode, $allowedMethods)) {
                        continue;
                    }

                    $addDeadline = 0;
                    $deadline = 0;
                    $avaiabilityAttribute = $this->getConfigData('attribute_add_deadline');
                    $alwaysAddTime = $this->getConfigData('always_add_deadline');
                    if ($avaiabilityAttribute) {
                        /** @var Mage_Sales_Model_Quote_Item $_item */
                        foreach ($_items as $_item) {
                            $_productId = $_item->getProduct()->getId();

                            //If different of bundle because bundle have a lot of products
                            if ($_item->getProductType() != 'bundle') {
                                $_childProduct = null;
                                if ($_item->getProductType() == 'configurable') {
                                    /** @var int $_childProductId */
                                    $_childProductId = Mage::getModel('catalog/product')->loadByAttribute('sku', $_item->getSku())->getId();
                                    /** @var Mage_Catalog_Model_Product $_parentProduct */
                                    $_childProduct = Mage::getModel('catalog/product')->load($_childProductId);
                                }
                                /** @var Mage_Catalog_Model_Product $_product */
                                $_product = Mage::getModel('catalog/product')->load($_productId);

                                if ($alwaysAddTime) {
                                    $productDeadline = (int)$_product->getResource()->getAttribute($avaiabilityAttribute)->getFrontend()->getValue($_product);
                                    $deadline = ($productDeadline > $deadline) ? $productDeadline : $deadline;
                                } else {
                                    $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
                                    if (($stock - $_item->getQty()) < 0) {
                                        $productDeadline = (int)$_product->getResource()->getAttribute($avaiabilityAttribute)->getFrontend()->getValue($_product);
                                        $deadline = ($productDeadline > $deadline) ? $productDeadline : $deadline;
                                    }
                                }

                                //Se houver produto filho e o produto tiver prazo, adicionará esse prazo
                                if ($_childProduct && $_childProduct->getId() && $alwaysAddTime) {
                                    $childDeadline = (int)$_childProduct->getResource()->getAttribute($avaiabilityAttribute)->getFrontend()->getValue($_childProduct);
                                    $deadline = ($childDeadline > 0 && $childDeadline > $deadline) ? $childDeadline : $deadline;
                                }
                            } else {
                                $productOptions = $_item->getQtyOptions();
                                if ($productOptions) {
                                    //If product is bundle, need to verify if all products are in stock
                                    if ($_item->getProductType() == 'bundle') {
                                        foreach ($productOptions as $_productId => $product) {
                                            $_product = Mage::getModel('catalog/product')->load($_productId);
                                            if ($alwaysAddTime) {
                                                $productDeadline = (int)$_product->getResource()->getAttribute($avaiabilityAttribute)->getFrontend()->getValue($_product);
                                                $deadline = ($productDeadline > $deadline) ? $productDeadline : $deadline;
                                            } else {
                                                $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
                                                if (($stock - $_item->getQty()) < 0) {
                                                    $productDeadline = (int)$_product->getResource()->getAttribute($avaiabilityAttribute)->getFrontend()->getValue($_product);
                                                    $deadline = ($productDeadline > $deadline) ? $productDeadline : $deadline;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $addDeadline += $deadline;
                    }
                    $deliveryTime = ($deliveryTime + $addDeadline + 2);
                    $methodTitle = sprintf($methodTitle, $shippingName, $deliveryTime);

                    if ($this->getConfigData('handling_fee')) {
                        $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);
                    }

                    $methodTitle = $methodTitle ?: $this->getConfigData('title');

                    /** @var $method Mage_Shipping_Model_Rate_Result_Method */
                    $method = Mage::getModel('shipping/rate_result_method');
                    $method->setCarrier($this->getCarrierCode());
                    $method->setCarrierTitle($this->getConfigData('title'));
                    $method->setMethod($shipingCode);
                    $method->setMethodTitle($methodTitle);
                    $method->setMethodDescription($methodTitle);
                    $method->setPrice($shippingPrice);
                    $method->setCost($shippingPrice);

                    if (
                        $allowFreeShipping
                        && $request->getFreeShipping() === true
                        && in_array($shipingCode, $freeshippingMethods)
                    ) {
                        $method->setPrice(0);
                        $method->setCost(0);
                    }

                    $this->_result->append($method);
                }
            }
        } catch (Exception $e) {
            $this->_getHelper()->log($e->getMessage());
        }
        return $this->_result;
    }

    /**
     * Get Tracking
     *
     * @param array $trackings
     * @return Mage_Shipping_Model_Tracking_Result
     */
    public function getTracking($trackings)
    {
        $this->setResult(Mage::getModel('shipping/tracking_result'));

        foreach ((array)$trackings as $trackingCode) {
            /** @var Mandae_Shipping_Model_Tracking $trackingModel */
            $trackingModel = Mage::getModel('mandae/tracking');
            $trackingModel->getTracking($trackingCode, $this);
        }

        return $this->getResult();
    }

    /**
     * Get Tracking Info
     *
     * @param mixed $tracking
     * @return mixed
     */
    public function getTrackingInfo($tracking)
    {
        $result = $this->getTracking($tracking);
        if ($result instanceof Mage_Shipping_Model_Tracking_Result) {
            if ($trackings = $result->getAllTrackings()) {
                return $trackings[0];
            }
        } elseif (is_string($result) && !empty($result)) {
            return $result;
        }
        return false;
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::getSingleton('adminhtml/session_quote')->getQuote();
        } else {
            return Mage::getModel('checkout/cart')->getQuote();
        }
    }

    public function getProductDimensions(Mage_Catalog_Model_Product $_product)
    {
        $heightAttribute = Mage::getStoreConfig('carriers/mandae/attribute_height');
        $height = ($heightAttribute) ? $heightAttribute : 'volume_altura';

        $widthAttribute = Mage::getStoreConfig('carriers/mandae/attribute_width');
        $width = ($widthAttribute) ? $widthAttribute : 'volume_largura';

        $lengthAttribute = Mage::getStoreConfig('carriers/mandae/attribute_length');
        $length = ($lengthAttribute) ? $lengthAttribute : 'volume_comprimento';

        $productHeight = (int) $_product->getData($height);
        $productWidtht = (int) $_product->getData($width);
        $productLength = (int) $_product->getData($length);

        $dimensionsType = $this->getConfigData('dimensions_type');
        if ($dimensionsType == 'm') {
            $productHeight = $productHeight * 100;
            $productWidtht = $productWidtht * 100;
            $productLength = $productLength * 100;
        }

        return array(
            'height' => $productHeight,
            'width' => $productWidtht,
            'length' => $productLength
        );
    }

    public function getItems(Mage_Shipping_Model_Rate_Request $request)
    {
        $items = [];

        // foreach ($this->getQuote()->getAllVisibleItems() as $item) {
        foreach ($request->getAllItems() as $item) {
            /** @var $_product Mage_Catalog_Model_Product */
            $_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $item->getSku());

            if (!$_product) {
                $_product = Mage::getModel('catalog/product')->load($item->getProductId());
            }

            $dimensions = $this->getProductDimensions($_product);

            $itemData = [
                'weight' => floatval($_product->getWeight()),
                'width' => $dimensions['width'],
                'height' => $dimensions['height'],
                'length' => $dimensions['length'],
                'quantity' => $item->getQty()
            ];

            if ($this->getConfigData('use_declared_value')) {
                $itemData['declaredValue'] = $item->getPrice() - $item->getDiscountAmount();
            }

            $items[] = $itemData;
        }

        return $items;
    }

    /**
     * @return Mandae_Shipping_Helper_Data | Mage_Core_Helper_Abstract
     */
    protected function _getHelper()
    {
        if (!$this->_helper) {
            $this->_helper = Mage::helper('mandae');
        }
        return $this->_helper;
    }
}
