<?php

class Hibrido_Trustvox_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getOrdersByLastDays($days, $page, $per_page, $store_id)
    {
        $_completeStatus = [];
        $completeStatus = Mage::getResourceModel('sales/order_status_collection')
            ->addStateFilter(array('complete'))
            ->toOptionHash();
        foreach($completeStatus as $key => $status){
            $_completeStatus[] = $key;
        }

        $totalOrders = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', $store_id)
            ->addFieldToFilter('status', array('in' => $_completeStatus) )
            ->addFieldToFilter('updated_at', array(
                'from' => date('m/d/Y', strtotime("-$days days")),
                'to ' => date('m/d/Y', time()),
                'date' => true,
            ));

        $orders = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', $store_id)
            ->addFieldToFilter('status', array('in' => $_completeStatus) )
            ->addFieldToFilter('updated_at', array(
                'from' => date('m/d/Y', strtotime("-$days days")),
                'to ' => date('m/d/Y', time()),
                'date' => true,
            ))
            ->setCurPage($page)
            ->setPageSize($per_page);

        $result = [
            'pages' => ceil(count($totalOrders) / $per_page),
            'orders' => $orders
        ];

        return $result;
    }

    public function mountClientInfoToSend($firstname, $lastname, $email)
    {
        $clientArray = array(
            'first_name' => $firstname,
            'last_name' => $lastname,
            'email' => $email,
        );

        return $clientArray;
    }

    public function getModuleVersion()
    {
        return Mage::getConfig()->getModuleConfig('Hibrido_Trustvox')->asArray()['version'];
    }

    public function getToken($store_id)
    {
        return Mage::getStoreConfig('hibridotrustvox/configuracoes/trustvox_token', $store_id);
    }

    public function getTrustvoxId($store_id)
    {
        return Mage::getStoreConfig('hibridotrustvox/configuracoes/trustvox_id', $store_id);
    }

    public function getWidgetTitle($store_id)
    {
        return Mage::getStoreConfig('hibridotrustvox/configuracoes/titulo_do_widget', $store_id);
    }

    public function getWidgetPosition($store_id)
    {
        return Mage::getStoreConfig('hibridotrustvox/configuracoes/posicao_do_widget', $store_id);
    }

    public function getPeriod($store_id)
    {
        return Mage::getStoreConfig('hibridotrustvox/configuracoes/periodo', $store_id);
    }

    public function getApiUrl($path = null)
    {
        return 'http://magento.trustvox.com.br/' . $path;
    }

    public function mostrarEstrelas($id)
    {
        $mostrar = Mage::getStoreConfig('hibridotrustvox/configuracoes/mostrar_estrelas');

        $html = '';
        if($mostrar == 'sim'){
            $html .= '<style type="text/css">';
                $html .= '.trustvox-widget-rating .ts-shelf-container,';
                $html .= '.trustvox-widget-rating .trustvox-shelf-container{';
                $html .= 'display: inline-block;';
            $html .= '}';
            $html .= '.trustvox-widget-rating span.rating-click-here{';
                $html .= 'top: -3px;';
                $html .= 'display: inline-block;';
                $html .= 'position: relative;';
                $html .= 'color: #DAA81D;';
            $html .= '}';
            $html .= '.trustvox-widget-rating:hover span.rating-click-here{';
                $html .= 'text-decoration: underline;';
            $html .= '}';
            $html .= '</style>';

            $html .= '<a class="trustvox-fluid-jump trustvox-widget-rating" href="#trustvox-reviews" title="Pergunte e veja opiniões de quem já comprou">';
                $html .= '<div class="trustvox-shelf-container" data-trustvox-product-code="'. $id .'" data-trustvox-should-skip-filter="true" data-trustvox-display-rate-schema="true"></div>';
            $html .= '</a>';
        }

        return $html;
    }

    public function getCamposExtra(){
        return Mage::getStoreConfig('hibridotrustvox/configuracoes/campos_extra', $store_id);
    }

    public function log($message)
    {
        return Mage::log($message);
    }

}
