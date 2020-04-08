<?php
/**
* Trezo Soluções Web
*
* NOTICE OF LICENSE
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to https://www.trezo.com.br for more information.
*
* @category Trezo
* @package Trezo_Cielo
*
* @copyright Copyright (c) 2017 Trezo Soluções Web. (https://www.trezo.com.br)
*
* @author Trezo Core Team <contato@trezo.com.br>
*/
class Trezo_Cielo_Block_Transaction extends Mage_Core_Block_Template
{
    public function getCieloInfos()
    {
        $infos = json_decode(json_encode($this->getInfos()), true);
        return $infos;
    }

    public function showInfo($key, $value, $msg='')
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $msg .= $this->showInfo($k, $v);
            }
        } else {
            $msg .= $this->__($key) . ': <strong>' . $this->__($value) . '</strong><br />';
        }

        return $msg;
    }
}
