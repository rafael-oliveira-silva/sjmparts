<?php

/**
 * Mandaê
 *
 * @category   Mandae
 * @package    Mandae_Shipping
 * @author     Thiago Contardi
 * @copyright  Copyright (c) 2017 Bizcommerce
 */
class Mandae_Shipping_Model_Source_Attributes
{
	/**
	 * Retrieves attributes
	 *
	 * @return array
	 */
	public function toOptionArray()
    {
		$attributes = Mage::getModel('catalog/product')->getResource()
			->loadAllAttributes()
			->getAttributesByCode();

		$result = array();
		$result[] = array(
			'value' => '',
			'label' => Mage::helper('adminhtml')->__('-- Please Select --')
		);
		foreach ($attributes as $attribute){
			/* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
			if ($attribute->getId()){
				$result[] = array(
					'value' => $attribute->getAttributeCode(),
					'label' => $attribute->getFrontend()->getLabel(),
				);
			}
		}
		return $result;
	}
}