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

class Trezo_Itaushopline_Block_Adminhtml_System_Config_Form_Field_Holiday extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareToRender()
    {
        $this->addColumn('date', array(
                'label' => Mage::helper('itaushopline')->__('Data'),
                'class' => 'input-text required-entry'

        ));
        $this->addColumn('holiday', array(
                'label' => Mage::helper('itaushopline')->__('Feriado')
        ));

        // Disables "Add after" button
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('itaushopline')->__('Adicionar Feriado');
    }
}
