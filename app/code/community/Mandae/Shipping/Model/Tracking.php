<?php
/**
 * Mandaê
 *
 * @category   Mandae
 * @package    Mandae_Shipping
 * @author     Thiago Contardi
 * @copyright  Copyright (c) 2017 Bizcommerce
 */
class Mandae_Shipping_Model_Tracking
{
    protected $_helper;
    /**
     * Get Tracking, opens the request to Correios
     *
     * @param string $code
     * @param Mandae_Shipping_Model_Carrier_Mandae $carrier
     * @return boolean
     */
    public function getTracking($trackingCode, Mandae_Shipping_Model_Carrier_Mandae $carrier)
    {
        $progress = array();
        $code = $carrier->getCarrierCode();

        /** @var Mage_Shipping_Model_Tracking_Result_Error $error */
        $error = Mage::getModel('shipping/tracking_result_error');
        $error->setTracking($trackingCode);
        $error->setCarrier($code);
        $error->setCarrierTitle($carrier->getConfigData('title'));
        $error->setErrorMessage($this->_getHelper()->__('Nenhum registro encontrado.'));

        try {
            /** @var Mandae_Shipping_Model_Api $api */
            $api = Mage::getModel('mandae/api');
            $response = $api->tracking($carrier, $trackingCode);

            if (!$response) {
                Mage::throwException($this->_getHelper()->__('Erro no retorno do Web Service'));
            }

            if (property_exists($response, 'error')) {
                Mage::throwException($this->_getHelper()->__('Erro no retorno do Web Service') . '. ' . 'Error (' . $response->error->code. '): ' . $response->error->message);
            }

            $events = $response->events;
            $status = property_exists($response, 'carrierName') ? $response->carrierName : null;

            if (! empty($events)) {

                $i = 0;
                foreach ($events as $item) {
                    $date = null;
                    $time = null;

                    $dateTime = trim($item->date);
                    $name = trim($item->name);
                    $description = trim($item->description);

                    if ($i == 0) {
                        $status = $name;
                    }

                    if (strpos($dateTime, ' ') !== false) {
                        list($date, $time) = explode(' ', $dateTime);
                    }

                    $progressItem = array(
                        'activity' => $name,
                        'deliverydate' => $date,
                        'deliverytime' => $time,
                        'deliverylocation' => $description
                    );

                    array_push($progress, $progressItem);
                    $i++;
                }

            }

            $track = array(
                'status' => $status,
                'progressdetail' => $this->_validateProgressDetail($progress)
            );

            /** @var Mage_Shipping_Model_Tracking_Result_Status $tracking */
            $tracking = Mage::getModel('shipping/tracking_result_status');
            $tracking->setTracking($trackingCode);
            $tracking->setCarrier($carrier->getCarrierCode());
            $tracking->setCarrierTitle($carrier->getConfigData('title'));
            $tracking->addData($track);
            $carrier->getResult()->append($tracking);
            return true;


        } catch (Exception $e) {
            $this->_getHelper()->log($e->getMessage());
        }

        $carrier->getResult()->append($error);

        return false;
    }

    /**
     * @param array $progressDetail
     * @return array
     */
    protected function _validateProgressDetail($progressDetail)
    {
        if (empty($progressDetail)) {
            $progressDetail = array(
                array(
                    'deliverydate' => date('Y-m-d'),
                    'deliverytime' => date('00:00:00'),
                    'deliverylocation' => $this->_getHelper()->__('Nenhum item encontrado com esse código')
                )
            );
        }

        return $progressDetail;
    }

    /**
     * @return Mandae_Shipping_Helper_Data| Mage_Core_Helper_Abstract
     */
    protected function _getHelper()
    {
        if (!$this->_helper) {
            $this->_helper = Mage::helper('mandae');
        }
        return $this->_helper;
    }

}