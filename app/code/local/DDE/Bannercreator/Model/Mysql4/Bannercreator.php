<?php
class DDE_Bannercreator_Model_Mysql4_Bannercreator extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("bannercreator/bannercreator", "id");
    }
}