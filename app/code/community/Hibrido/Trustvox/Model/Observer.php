<?php

class Hibrido_Trustvox_Model_Observer
{

    public function saveOrder($observer)
    {
        $order = $observer->getEvent()->getOrder();
        $status = $order->getStatus();
        if($status && $status != '' && $status == Mage_Sales_Model_Order::STATE_COMPLETE){
            $helper = Mage::helper('hibridotrustvox');
            $store_id = Mage::app()->getStore()->getId();

            $shippingDate = '';
            foreach($order->getShipmentsCollection() as $shipment){
                $shippingDate = $shipment->getCreatedAt();
            }

            if(!$shippingDate || $shippingDate == ''){
                $shippingDate = $order->getCreatedAt();
            }

            $clientArray = $helper->mountClientInfoToSend($order->getCustomerFirstname(), $order->getCustomerLastname(), $order->getCustomerEmail());

            $productArray = [];
            foreach ($order->getAllItems() as $item) {
                $_product = Mage::getModel('catalog/product');

                if ($item->getProductType() == 'simple') {
                    $parents = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($item->getProductId());
                    if(count($parents) >= 1){
                        $productId = $parents[0];
                    }else{
                        $productId = $item->getProductId();
                    }
                }else if($item->getProductType() == 'grouped'){
                    $parents = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($item->getProductId());
                    if(count($parents) >= 1){
                        $productId = $parents[0];
                    }else{
                        $productId = $item->getProductId();
                    }
                }else{
                    $productId = $item->getProductId();
                }

                if ($item->getParentItemId()) {
                    $parent_product_type = Mage::getModel('sales/order_item')->load($item->getParentItemId())->getProductType();
                    if ($parent_product_type == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                        $productId = $item->getParentItemId();
                    }
                }

                $_item = $_product->load($productId);
                $product_url = $_item->getProductUrl();

                $images = [];

                foreach ($_item->getMediaGalleryImages() as $image) {
                    array_push($images, $image->getUrl());
                }

                if($_item->getId()){
                    $productArray[$_item->getId()] = array(
                        'name' => $_item->getName(),
                        'id' => $_item->getId(),
                        'price' => $_item->getPrice(),
                        'url' => $product_url,
                        'type' => $item->getProductType(),
                        'photos_urls' => (isset($images[0])) ? $images[0] : ''
                    );
                }
            }

            $_order = [
                'order_id' => $order->getId(),
                'delivery_date' => $shippingDate,
                'client' => $clientArray,
                'items' => $productArray
            ];

            $client = new Varien_Http_Client($helper->getApiUrl('sync-order'));
            $client->setMethod(Varien_Http_Client::POST);
            $client->setParameterPost('trustvox_id', $helper->getTrustvoxId($store_id));
            $client->setParameterPost('order', $_order);

            try{
                $client->request();
            } catch (Exception $e) {
                Mage::log('ERRO TRUSTVOX: ' . var_export($e));
            }
        }
    }

}
