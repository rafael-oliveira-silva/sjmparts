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

class Trezo_Itaushopline_Model_System_Config_Source_Payment_Types
{
    public function toOptionArray()
    {
        $options =  array();

        foreach (Mage::getSingleton('itaushopline/config')->getCcTypes() as $code => $name) {
            $options[] = array(
               'value' => $code,
               'label' => Mage::helper('itaushopline')->__($name),
            );
        }

        return $options;
    }
}
