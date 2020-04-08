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

class Trezo_Itaushopline_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
    const PAYMENT_METHOD = 'itaushopline_standard';
    /**
     * Paymento Code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD;

    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_isInitializeNeeded = true;

    protected $_formBlockType = 'itaushopline/standard_form';
    protected $_infoBlockType = 'itaushopline/standard_info';

    const ITAU_SHOPLINE_SUBMIT_TRANSACTION_LENGTH = 1600;
    const ITAU_SHOPLINE_QUERY_TRANSACTION_LENGTH = 120;

    public function getExpirationWithBusinessDays($numberOfDays, $format = 'Y-m-d')
    {
        $DataAct = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));

        $d = new DateTime($DataAct);
        $inicialTimeStamp = $d->getTimestamp();
        $t = $d->getTimestamp();

        // loop for X days
        for ($i=0; $i<$numberOfDays; $i++) {
            // add 1 day to timestamp
            $addDay = 86400;

            // get what day it is next day
            $nextDay = date('w', ($t+$addDay));

            // if it's Saturday or Sunday get $i-1
            if ($nextDay == 0 || $nextDay == 6) {
                $i--;
            }

            // modify timestamp, add 1 day
            $t = $t+$addDay;
        }

        $holidayDates = $this->getHolidayDates();

        for ($i=0; $i < sizeof($holidayDates); $i++) {
            //  foreach ($holidayDates as $holidayDate){
            $holidayDateTime = new DateTime($holidayDates[$i]);
            $holidayDateTimeStamp = $holidayDateTime->getTimestamp();

            $dayOfWeek = date('w', ($holidayDateTimeStamp));

            // if isen't Saturday or Sunday
            if ($dayOfWeek != 0 && $dayOfWeek != 6) {
                if ($holidayDateTimeStamp >= $inicialTimeStamp && $holidayDateTimeStamp <= $t) {
                    // add 1 day to timestamp
                    $addDay = 86400;
                    $t = $t+$addDay;
                }
            }

            $dayOfWeek = date('w', ($t));

            while ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $addDay = 86400;
                $t = $t+$addDay;

                $dayOfWeek = date('w', ($t));
            }
        }

        $d->setTimestamp($t);

        return $d->format($format);
    }

    public function getHolidayDates()
    {
        $holidayArray = unserialize(Mage::getStoreConfig("payment/itaushopline_settings/holidays"));
        $holidayDates = array();
        $year = date('Y');

        foreach ($holidayArray as $holiday) {
            //remove empty spaces
            $h = str_replace(" ", "", $holiday['date']);
            if ($h != "") {
                $dataArray = explode("/", $h);
                $mounth = "";
                $day = "";

                if (array_key_exists(0, $dataArray)) {
                    $day = $dataArray[0];
                }

                if (array_key_exists(1, $dataArray)) {
                    $mounth = $dataArray[1];
                }

                //id is a valid date
                if (checkdate($mounth, $day, $year)) {
                    $fullDate = $year . $mounth . $day;
                    $holidayDates[] = $fullDate;
                }
            }
        }


        //remove duplicated values
        $holidayDates = array_unique($holidayDates);
        //sort
        sort($holidayDates);

        return $holidayDates;
    }

    public function _getStoreConfig($field)
    {
        return Mage::getStoreConfig("payment/itaushopline_settings/$field");
    }

    public function _debug($debugData)
    {
        if (Mage::getStoreConfig('payment/itaushopline_settings/debug')) {     //ve se no modulo esta ligado o modo debug
            Mage::log($debugData, null, 'Itaushopline.log', true);
        }
    }

    /**
     * @param string $paymentAction
     * @param object $stateObject
     * This method was implemented to override the authorize method to generate the ticket,
     * and for the magento not to add the status 'processing' when the order is created.
     * Then the authorize method is called to generate the ticket information.
     * @return Mage_Payment_Model_Abstract
     */
    public function initialize($paymentAction, $stateObject)
    {
        $payment = $this->getInfoInstance();
        $order = $payment->getOrder();

        $this->authorize($payment, $order->getBaseTotalDue());
    }

    public function authorize(Varien_Object $payment, $amount)
    {
        $order = $payment->getOrder();
        $order_id = $order->getId();

        $order_increment_id = $order->getIncrementId();
        //$order_increment_id = 100000146;  //TESTE quando der erro 08 - Forcar sempre o msm numero para eu montar a parte do sucesso

        $quote = $order->getQuote();
        $store_id = $order->getStoreId();

        $ccType = $payment->getCcType();

        $code = $this->_getStoreConfig('code');
        $key = $this->_getStoreConfig('key');

        $expirationConfig = $this->_getStoreConfig('expiration');
        $bank_expiration = $this->getExpirationWithBusinessDays($expirationConfig, 'dmY');
        $observação_expiration = $this->getExpirationWithBusinessDays($expirationConfig, 'd/m/Y');

        $adicionalDaysToTransactionExpirationConfig = $this->_getStoreConfig('adicional_days_to_transaction_expiration');

        $transactionExpirationDays = $expirationConfig;

        //adiciona dias a mais para a expiração da transação do boleto, ideal para quando o banco demora a confirmar o pagamento.
        $adicionalDaysToTransactionExpirationConfig = $this->_getStoreConfig('adicional_days_to_transaction_expiration');
        if ($adicionalDaysToTransactionExpirationConfig) {
            $transactionExpirationDays += $adicionalDaysToTransactionExpirationConfig;
        }

        $transaction_expiration = $this->getExpirationWithBusinessDays($transactionExpirationDays, 'Y-m-d');

        //$obs = $this->_getStoreConfig ('obs');
        $obs = 3;

        //$obsadd1 = $this->_getStoreConfig ('obsadd1');
        $obsadd1 = "NAO RECEBER APOS: ".$observação_expiration;

        //$obsadd2 = $this->_getStoreConfig ('obsadd2');
        $obsadd2 = "NUMERO DO PEDIDO: ".$order_increment_id;
        $this->_debug('order_increment_id=' . $order_increment_id);

        $obsadd3 = $this->_getStoreConfig('obsadd3');

        $documentAttribute = $this->_getStoreConfig('taxvat_attribute');
        $tax_vat = preg_replace('/[^0-9]+/', '', $order->getCustomer()->getData($documentAttribute));
        if (!$tax_vat) {
            $tax_vat = preg_replace('/[^0-9]+/', '', $order->getData('customer_' . $documentAttribute));
        }

        $address = $quote->getBillingAddress();
        $name = $address->getName();
        $address1 = $address->getStreet1(). ", " . $address->getStreet2(); //rua, numero - complemento
        if (trim($address->getStreet3()) != "") {
            $address1 .= " - " . $address->getStreet3();
        }
        $address2 = $address->getStreet4(); //bairo
        if (!$address2) {
            $address2 = 'centro';
        }
        $postcode = preg_replace('/[^0-9]+/', '', $address->getPostcode());

        $city = $address->getCity();
        $region = $address->getRegionCode();
        $return_url = $this->_getStoreConfig('return_url');

        $short_number = substr($order_increment_id, -8); /* Order number max. length for ItauShopLine */

        // Validação para pedidos  que foram editados no admin
        if (strpos($short_number, '-')) {
            $short_number = substr($order_increment_id, -9);
            $short_number = str_replace('-', '', $short_number);
        }

        $submit_dc = Mage::getModel('itaushopline/itaucripto')->geraDados(
            $code,
            $short_number,
            str_replace('.', "", number_format($amount, 2, ',', '.')),
            $obs,
            $key,
            $name,
            (strlen($tax_vat) > 11) ? '02' : '01' /* 01:CPF, 02:CNPJ */,
            $tax_vat,
            $address1,
            $address2,
            $postcode,
            $city,
            $region,
            $bank_expiration,
            $return_url,
            $obsadd1,
            $obsadd2,
            $obsadd3
        );

        if (strlen($submit_dc) < self::ITAU_SHOPLINE_SUBMIT_TRANSACTION_LENGTH) {
            Mage::throwException(Mage::helper('itaushopline')->__('Unable to generate submit transaction code. Please check your settings.'));
        }

        $query_dc = Mage::getModel('itaushopline/itaucripto')->geraConsulta(
            $code,
            $short_number,
            '0' /* 0:HTML, 1:XML */,
            $key
        );

        if (strlen($query_dc) < self::ITAU_SHOPLINE_QUERY_TRANSACTION_LENGTH) {
            Mage::throwException(Mage::helper('itaushopline')->__('Unable to generate query transaction code. Please check your settings.'));
            $this->_debug('Unable to generate query transaction code. Please check your settings. ERRO: '.$query_dc);
        }


        $data = array ('order_id' => $order_id, 'amount' => $amount, 'expiration' => $transaction_expiration, 'number' => $short_number,
                   'submit_dc' => $submit_dc, 'query_dc' => $query_dc);



        $this->_debug('enviado=' . print_r($data, 1));


        $result = Mage::getModel('itaushopline/sql')->insert('trezo_itaushopline_transactions', $data);

        $this->_debug('result=' . $result);


        if (!$result) {
            Mage::throwException(Mage::helper('itaushopline')->__('Unable to save the Itau ShopLine informations. Please verify your database.'));
        }

        $this->setStore($payment->getOrder()->getStoreId());
        $payment->setAmount($amount);
        $payment->setLastTransId($order_id);
        $payment->setStatus(self::STATUS_APPROVED);

        return $this;
    }
}
