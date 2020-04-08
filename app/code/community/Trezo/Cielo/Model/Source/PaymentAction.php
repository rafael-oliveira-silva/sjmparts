<?php

class Trezo_Cielo_Model_Source_PaymentAction
{
    /**
     * Retrn payment transaction options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Trezo_Cielo_Model_Payment_CcMethod::ACTION_AUTHORIZE,
                'label' => Mage::helper('adminhtml')->__('Authorize')
            ),
            array(
                'value' => Trezo_Cielo_Model_Payment_CcMethod::ACTION_AUTHORIZE_CAPTURE,
                'label' => Mage::helper('adminhtml')->__('Capture')
            ),
        );
    }
}