<?php

require_once Mage::getModuleDir('Model', 'Trezo_Cielo') . '/Model/Conciliation/Conciliate.php';

class Trezo_Cielo_NotificationController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        /* @TODO: Implementar notification */
        echo 'FAIL';
        die;

        try {
            $notificationData = Mage::app()->getRequest()->getRawBody();
            Mage::helper('trezo_cielo')->log(':: LOG NOTIFICATION ::');
            Mage::helper('trezo_cielo')->log($notificationData);
            if ($notificationData) {
                 if ($notificationDataJson = json_decode($notificationData)) {
                    $this->proccessNotification($notificationDataJson);
                    /* CIELO NEEDS TO RECEIVE AN "OK" MESSAGE AS PROCESSED REQUEST */
                    echo 'OK';
                    die;
                }
                else if ($notificationDataXML = simplexml_load_string($notificationData)) {
                    $this->proccessNotification($notificationDataXML);
                    /* CIELO NEEDS TO RECEIVE AN "OK" MESSAGE AS PROCESSED REQUEST */
                    echo 'OK';
                    die;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        echo " FAIL";
    }

    protected function proccessNotification($notificationData)
    {
        $incrementId = $notificationData->OrderReference;
        $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
        $notification = new \Gateway\One\DataContract\Response\BaseResponse(true, $notificationData);
        $conciliate = new Trezo_Cielo_Model_Cielo_Conciliation_Conciliate($notification, $order);
        $conciliate->processNotificationConciliation();
    }
}