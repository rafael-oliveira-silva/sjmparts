<?php
class Smarthint_Recommendation_IndexController extends Mage_Core_Controller_Front_Action{

    public function indexAction()
    {
        $process = new stdClass();
        $process->status = "Atualização";
        $process->version = "1.1.3";
        $process->domain = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $process->email  = Mage::getStoreConfig('smarthint/identify/email', $observer->store);
        $JSON = json_encode($process);
        Mage::helper('smarthint')->log($JSON);

        //Timeout changed
        set_time_limit(0);
        //Processa categoria
        Mage::helper('smarthint')->processCategory();
        //Processa produtos
        Mage::helper('smarthint')->processProduct();
    }
}