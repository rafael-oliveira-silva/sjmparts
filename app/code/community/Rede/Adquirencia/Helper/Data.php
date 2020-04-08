<?php

/**
 * Class Rede_Adquirencia_Helper_Data
 */
class Rede_Adquirencia_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * @param $field
     *
     * @return mixed
     */
    public function getPaymentConfig($field)
    {
        return Mage::getStoreConfig(implode('/', array('payment', 'rede_adquirencia', $field)));
    }

    /**
     * @return mixed
     */
    public function getConfigReference()
    {
        $reference = (int) $this->getPaymentConfig('reference');

        if (!is_numeric($reference) || $reference < 0) {
            $reference = 0;
        }

        return $reference;
    }

    /**
     * @return mixed
     */
    public function getConfigAffiliation()
    {
        return $this->getPaymentConfig('affiliation');
    }

    /**
     * @return mixed
     */
    public function getConfigToken()
    {
        return $this->getPaymentConfig('password');
    }

    /**
     * @return mixed
     */
    public function getConfigTitle()
    {
        return $this->getPaymentConfig('title');
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->getPaymentConfig('transaction_type');
    }

    /**
     * @return int
     */
    public function getEnvironment()
    {
        return (int)$this->getPaymentConfig('environment');
    }

    /**
     * @return int
     */
    public function getConfigInstallmentsAmount()
    {
        return (int)$this->getPaymentConfig('installments_amount');
    }

    /**
     * @return int
     */
    public function getConfigInstallmentsMinOrderValue()
    {
        return (int)$this->getPaymentConfig('installments_min_order_value');
    }

    /**
     * @return int
     */
    public function getConfigInstallmentsMinParcelValue()
    {
        return (int)$this->getPaymentConfig('installments_min_parcel_value');
    }

    /**
     * @return mixed
     */
    public function getConfigSoftDescription()
    {
        return $this->getPaymentConfig('soft_description');
    }

    /**
     * @return mixed
     */
    public function getConfigAntifraud()
    {
        return $this->getPaymentConfig('antifraud');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getAdminSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getSession()
    {
        return Mage::getSingleton('rede_adquirencia/session');
    }

    /**
     * @return bool
     */
    public function getIsCheckoutAttemptsExceeded()
    {
        $attempts = $this->getSession()->getCheckoutAttempts();

        return $attempts >= 2;
    }

    public function clearCheckoutAttempts()
    {
        $this->getSession()->clear();
        $this->getSession()->resetCheckoutAttempts();
    }

    /**
     * @param null $width
     *
     * @return mixed
     */
    public function getLogoBlock($width = null)
    {
        return Mage::app()->getLayout()->createBlock('rede_adquirencia/logo')->setWidth($width);
    }

    /**
     * @param null $width
     *
     * @return mixed
     */
    public function getLogoHtml($width = null)
    {
        return $this->getLogoBlock($width)->toHtml();
    }

    public function getStatusLabel($statusId): string
    {
        $options = [
            Rede_Adquirencia_Model_Transacoes_Status::APPROVED => 'Aprovado',
            Rede_Adquirencia_Model_Transacoes_Status::DENIED => 'Negado',
            Rede_Adquirencia_Model_Transacoes_Status::CANCELED => 'Cancelado',
            Rede_Adquirencia_Model_Transacoes_Status::PENDING => 'Pendente'
        ];

        return $options[$statusId];
    }

    public function getStatusLabelColor($statusId)
    {
        $options = [
            Rede_Adquirencia_Model_Transacoes_Status::APPROVED => '#00A700',
            Rede_Adquirencia_Model_Transacoes_Status::DENIED => '#DE1D1D',
            Rede_Adquirencia_Model_Transacoes_Status::CANCELED => '#DE1D1D',
            Rede_Adquirencia_Model_Transacoes_Status::PENDING => '#2F2F2F'
        ];

        return $options[$statusId];
    }

    /** 
     *   @link https://gist.github.com/erikhenrique/5931368
     *   @link http://pt.stackoverflow.com/questions/3715/express%C3%A3o-regular-para-detectar-a-bandeira-do-cart%C3%A3o-de-cr%C3%A9dito#answer-16779
     *   @param string $bin
     *   @return string
     *   @version 1.0
     */
    public function getCreditCardType($bin){
        $bin = preg_replace("/[^0-9]/", "", $bin); //remove caracteres não numéricos
        // if(strlen($bin) < 13 || strlen($bin) > 19) return false;
        //O BIN do Elo é relativamente grande, por isso a separação em outra variável
        // $eloBin = implode("|", array(636368,438935,504175,451416,636297,506699,509048,509067,509049,509069,509050,09074,509068,509040,509045,509051,509046,509066,509047,509042,509052,509043,509064,509040));
        $eloBin = '636368|438935';
        $conditions = array(
            "elo"           => "/^((".$eloBin."[0-9]{10})|(36297[0-9]{11})|(5067|4576|4011[0-9]{12}))/",
            "discover"      => "/^((6011[0-9]{12})|(622[0-9]{13})|(64|65[0-9]{14}))/",
            "diners"        => "/^((301|305[0-9]{11,13})|(36|38[0-9]{12,14}))/",
            "amex"          => "/^((34|37[0-9]{13}))/",
            "hipercard"     => "/^((38|60[0-9]{11,14,17}))/",
            "aura"          => "/^((50[0-9]{14}))/",
            "jcb"           => "/^((35[0-9]{14}))/",
            "mastercard"    => "/^((5[0-9]{15}))/",
            "visa"          => "/^((4[0-9]{12,15}))/"
        );

        foreach($conditions as $type => $condition){
            if(preg_match($condition, $bin)){
                return $type;
            }
        }

        return false;
    }
}