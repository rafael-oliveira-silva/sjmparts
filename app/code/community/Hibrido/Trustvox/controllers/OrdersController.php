<?php

class Hibrido_Trustvox_OrdersController extends Mage_Core_Controller_Front_Action {

    private function helper()
    {
        return Mage::helper('hibridotrustvox');
    }

    public function indexAction()
    {
        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);

        $store_id = ($this->getRequest()->getHeader('store_id') != '') ? $this->getRequest()->getHeader('store_id') : Mage::app()->getStore()->getId();

        if ($this->getRequest()->getHeader('trustvox-token') != '' && $this->getRequest()->getHeader('trustvox-token') == $this->helper()->getToken($store_id)) {
            $clientArray = [];
            $productArray = [];

            $period = ($this->getRequest()->getHeader('period') != '' && $this->getRequest()->getHeader('period') > 1) ? $this->getRequest()->getHeader('period') : 30;

            $page = ($this->getRequest()->getHeader('page') != '' && $this->getRequest()->getHeader('page') > 1) ? $this->getRequest()->getHeader('page') : 1;
            $per_page = ($this->getRequest()->getHeader('per-page') != '' && $this->getRequest()->getHeader('per-page') >= 1) ? $this->getRequest()->getHeader('per-page') : 50;

            $orders = $this->helper()->getOrdersByLastDays($period, $page, $per_page, $store_id);

            $json = [
                'moduleVersion' => ($this->helper()->getModuleVersion() != '') ? $this->helper()->getModuleVersion() : '2.0.0',
                'page' => $page,
                'pages' => $orders['pages'],
                'orders' => []
            ];
            foreach ($orders['orders'] as $order) {
                $clientArray = $this->helper()->mountClientInfoToSend($order->getCustomerFirstname(), $order->getCustomerLastname(), $order->getCustomerEmail());

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

                    $extra = [];
                    $campos_extra = explode(',', $this->helper()->getCamposExtra($store_id));
                    foreach ($campos_extra as $campo) {
                        $data = $_item->getData($campo);
                        if($data && $data != ''){
                            $extra[ $campo ] = $data;
                        }
                    }

                    if($_item->getId()){
                        $productArray[$_item->getId()] = array(
                            'name' => $_item->getName(),
                            'id' => $_item->getId(),
                            'price' => $_item->getPrice(),
                            'url' => $product_url,
                            'type' => $item->getProductType(),
                            'photos_urls' => (isset($images[0])) ? $images[0] : '',
                            'extra' => $extra
                        );
                    }
                }

                $shippingDate = '';
                foreach($order->getShipmentsCollection() as $shipment){
                    $shippingDate = $shipment->getCreatedAt();
                }

                if(!$shippingDate || $shippingDate == ''){
                    $shippingDate = $order->getCreatedAt();
                }

                $json['orders'][] = [
                    'order_id' => $order->getId(),
                    'delivery_date' => $shippingDate,
                    'client' => $clientArray,
                    'items' => $productArray
                ];
            }
            return $this->getResponse()->setBody(json_encode($json));
        } else {
            $jsonArray = array(
                'error' => true,
                'message' => 'not authorized',
            );
            $this->getResponse()->setBody(json_encode($jsonArray));
        }
    }
}
