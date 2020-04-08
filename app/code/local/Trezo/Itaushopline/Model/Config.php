<?php
/**
* Trezo
*
* NOTICE OF LICENSE
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.trezo.com.br for more information.
*
* @category Trezo
* @package Trezo_Itaushopline
*
* @copyright Copyright (c) 2017 Trezo. (http://www.trezo.com.br)
*
* @author Trezo Core Team <contato@trezo.com.br>
*/

class Trezo_Itaushopline_Model_Config
{
    protected static $_methods;

    public function getActiveMethods($store = null)
    {
        $methods = array();
        $config = Mage::getStoreConfig('itaushopline', $store);
        foreach ($config as $code => $methodConfig) {
            if (Mage::getStoreConfigFlag('itaushopline/'.$code.'/active', $store)) {
                if (array_key_exists('model', $methodConfig)) {
                    $methodModel = Mage::getModel($methodConfig['model']);
                    if ($methodModel && $methodModel->getConfigData('active', $store)) {
                        $methods[$code] = $this->_getMethod($code, $methodConfig);
                    }
                }
            }
        }

        return $methods;
    }

    public function getCcTypes()
    {
        $_types = Mage::getConfig()->getNode('global/payment/itaushopline/types')->asArray();

        $types = array();
        foreach ($_types as $data) {
            if (isset($data['code']) && isset($data['name'])) {
                $types[$data['code']] = $data['name'];
            }
        }
        return $types;
    }
}
