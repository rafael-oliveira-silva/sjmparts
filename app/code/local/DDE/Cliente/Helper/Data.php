<?php
/**
 * DDE_Cliente extension for Magento
 *
 * Helper class to allow localization within the extension itself
 * and to get the right region for JSON
 *
 * @category   DezoitoDigital
 * @package    DezoitoDigital_Cliente
 * @version    0.1.0
 */
class DDE_Cliente_Helper_Data extends Mage_Core_Helper_Abstract{
	
	/* @method getRegionJson
	 * @return json
	 */
	public function getRegionJson(){
		$collection = Mage::getModel('directory/region')
			->getResourceCollection()
            ->addCountryFilter('BR')
            ->load();
		
		$regions = array();
		
		foreach ($collection as $region) $regions[$region->getCode()] = $region->getId();
		
        $json = Mage::helper('core')->jsonEncode($regions);
		
		return $json;
	}
}