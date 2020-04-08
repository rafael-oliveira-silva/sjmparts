<?php

/**
 * DDE_Cliente extesion for Magento
 * 
 * This script'll install some attribute necessary for itself
 *
 * @version  1.1.0
 * @category DDE
 * @package  DDE_Cliente
 */

$installer = $this;

$installer->startSetup();

$setup = Mage::getModel('customer/entity_setup', 'core_setup');
$setup->addAttribute('customer', 'tipo', array(
		'type'             => 'int',
		'input'            => 'select',
		'label'            => 'Tipo',
		'global'           => 1,
		'visible'          => 1,
		'required'         => 1,
		'user_defined'     => 1,
		'default'          => '1',
		'visible_on_front' => 1,
		'source'           => 'cliente/source_tipo'
	)
);

$setup->addAttribute('customer', 'rgie', array(
		'type'             => 'varchar',
		'input'            => 'text',
		'label'            => 'RG/IE',
		'global'           => 1,
		'visible'          => 1,
		'required'         => 1,
		'user_defined'     => 1,
		'visible_on_front' => 1
	)
);

if (version_compare(Mage::getVersion(), '1.6.0', '<=')){
	$customer = Mage::getModel('customer/customer');
	
	$attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
	
	$setup->addAttributeToSet('customer', $attrSetId, 'General', 'tipo');
	$setup->addAttributeToSet('customer', $attrSetId, 'General', 'rgie');	
}

if (version_compare(Mage::getVersion(), '1.4.2', '>=')){
	Mage::getSingleton('eav/config')
		->getAttribute('customer', 'tipo')
		->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
		->save();
	
	Mage::getSingleton('eav/config')
		->getAttribute('customer', 'rgie')
		->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
		->save();
}

$tablequote = $this->getTable('sales/quote');
$installer->run('
	ALTER TABLE  '.$tablequote.' ADD  `customer_tipo` int(11) DEFAULT NULL;
	ALTER TABLE  '.$tablequote.' ADD  `customer_rgie` varchar(255) DEFAULT NULL;
');

$installer->endSetup();