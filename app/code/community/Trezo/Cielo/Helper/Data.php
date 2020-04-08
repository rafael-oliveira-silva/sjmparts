<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getConfig($key, $group = 'cielo_general')
    {
        return Mage::getStoreConfig('payment/' . $group . '/' . $key, Mage::app()->getStore());
    }

    public function isAntiFraud()
    {
        return $this->getConfig('anti_fraud');
    }

    public function isSandbox()
    {
        $environment = $this->getConfig('environment');
        if ($environment == Trezo_Cielo_Model_Source_Environment::SANDBOX) {
            return true;
        }

        return false;
    }

    public function log($data)
    {
        if (!$this->canLog()) {
            return false;
        }

        /* FORCE LOG IF CONFIGURED ON ADMIN */
        Mage::log($data, null, 'cielo.log', true);
    }

    public function canLog()
    {
        return Mage::getStoreConfig('payment/cielo_general/log');
    }

    public function getGrandTotalWithInterest($grandTotal, $qtyParcels, $interest)
    {
        if (!$interest) {
            return $grandTotal;
        }

        if ($qtyParcels == 1) {
            return round($grandTotal + ($grandTotal * ($interest / 100)), 2);
        }

        $total = 0;

        for ($i = 1; $i <= $qtyParcels; $i++) {
            $total += $this->getPortionWithInterest($grandTotal, $qtyParcels, $interest);
        }

        return round($total, 2);
    }

    public function getPortionWithInterest($grandTotal, $qtyParcels, $interest)
    {
        $portionAmount = $grandTotal / $qtyParcels;

        if (!$interest) {
            return $portionAmount;
        }

        return $portionAmount + ($portionAmount * ($interest / 100));
    }

    public function getInterestPercentage($qtyParcels)
    {
        if ($qtyParcels == 1) {
            $interest = Mage::getStoreConfig('payment/cielo_cc/installment_interest_1', Mage::app()->getStore());
        } elseif ($qtyParcels > 1 && $qtyParcels <= 6) {
            $interest = Mage::getStoreConfig('payment/cielo_cc/installment_interest_2', Mage::app()->getStore());
        } elseif ($qtyParcels > 6 && $qtyParcels <= 12) {
            $interest = Mage::getStoreConfig('payment/cielo_cc/installment_interest_3', Mage::app()->getStore());
        } else {
            $interest = 0;
        }
        return (float)$interest;
    }

    public function formatInterestString($interestPercentage)
    {
        return number_format($interestPercentage, 2, ',', '') . "%";
    }

    /**
     * Treat number to convert to cents
     *
     * @param  float $number amount value
     * @return string         formatted number in cents
     */
    public function treatToCents($number)
    {
        return (int)number_format($number, 2, '', '');
    }

    /**
     * Verify status number with valid numbers
     * [REFERENCIA]: https://developercielo.github.io/Webservice-3.0/#status
     *
     * @param  int $status status returned from api cielo
     * @return bool        flag if status is valid
     */
    public function validateStatus($status)
    {
        if (in_array($status, [1, 2, 12, 20])) {
            return true;
        }

        return false;
    }

    public function getStatusLabel($status)
    {
        $label = $status;

        switch ($status) {
            case 0:
                $label = 'Aguardando atualização de status';
                break;

            case 1:
                $label = 'Autorizado';
                break;

            case 2:
                $label = 'Aprovado';
                break;

            case 3:
                $label = 'Negado';
                break;

            case 10:
                $label = 'Cancelado';
                break;

            case 11:
                $label = 'Estornado';
                break;

            case 12:
                $label = 'Aguardando aprovação';
                break;

            case 13:
                $label = 'Falha ao processar';
                break;

            case 20:
                $label = 'Recorrência agendada';
                break;
        }

        return $label;
    }

    public function getStatusLabelColor($statusId)
    {
        $options = [
            0 => '#000',
            1 => '#000',
            2 => '#00A700',
            3 => '#DE1D1D',
            10 => '#DE1D1D',
            11 => '#000',
            12 => '#2F2F2F',
            13 => '#000',
            20 => '#000'
        ];

        return $options[$statusId];
    }
}
