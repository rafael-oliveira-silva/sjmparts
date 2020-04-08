<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Block_Form_Cc extends Mage_Payment_Block_Form_Cc
{

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('trezo/cielo/form/cc.phtml');
    }

    public function getInstallmentsAvailables()
    {
        $helper = $this->helper('trezo_cielo');
        $maxInstallments = (int) Mage::getStoreConfig('payment/cielo_cc/max_installments');
        $minInstallmentValue = (float) Mage::getStoreConfig('payment/cielo_cc/min_installment_value');
        $quote = Mage::helper('checkout')->getQuote();
        $interestPercentage = $helper->getInterestPercentage(1);
        // admin session quote is other
        $session = Mage::getSingleton('admin/session');
        if ($session->isLoggedIn()) {
            $quote = Mage::getSingleton('adminhtml/session_quote')->getQuote();
        }
        $baseGrandTotal = $quote->getGrandTotal();

        if ($interestPercentage > 0) {
            $portions = array(
                            1 => $helper->__('In cash %s with %s interest',
                                Mage::helper('core')->currency($helper->getPortionWithInterest($baseGrandTotal, 1, $interestPercentage), true, false), $helper->formatInterestString($interestPercentage)
                            )
                        );
        } else {
            $portions = array(
                        1 => $helper->__('In cash %s',
                            Mage::helper('core')->currency($baseGrandTotal, true, false)
                        )
                    );
        }

        // installments is not avaliable
        if ($baseGrandTotal < $minInstallmentValue) {
            return $portions;
        }

        for ($i = 2; $i <= $maxInstallments; $i++) {
            $interestPercentage = $helper->getInterestPercentage($i);
            $portionAmount = $helper->getPortionWithInterest($baseGrandTotal, $i, $interestPercentage);

            // Check if the installment is not below the minimum
            if ($minInstallmentValue >= 0 && $portionAmount < $minInstallmentValue) {
                break;
            }

            if ($interestPercentage > 0) {
                $portions[$i] = $helper->__('%dx of %s with %s interest', $i, Mage::helper('core')->currency($portionAmount, true, false), $helper->formatInterestString($interestPercentage));
            } else {
                $portions[$i] = $helper->__('%dx of %s', $i, Mage::helper('core')->currency($portionAmount, true, false));
            }
        }

        return $portions;
    }

    public function getCcTypes()
    {
        $ccTypesAvaliable = Mage::getStoreConfig('payment/cielo_cc/cctypes');

        $ccTypes = array();
        if ($ccTypesAvaliable != '') {
            $ccTypes = explode(",", $ccTypesAvaliable);
        }

        return $ccTypes;
    }
}
