<?php
/**
 * Mandaê
 *
 * @category   Mandae
 * @package    Mandae_Shipping
 * @author     Thiago Contardi
 * @copyright  Copyright (c) 2017 Bizcommerce
 */

/*
 * Setup created because most of Brazilians Magento store already has the dimensions attributes and they are usually named as volume_comprimento, volume_altura and volume_largura
 */
$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$codigo = 'volume_comprimento';
if(!$installer->getAttribute(Mage_Catalog_Model_Product::ENTITY, $codigo, 'attribute_id')) {
    $config = array(
        'position' => 1,
        'required' => 0,
        'label' => 'Comprimento (cm)',
        'type' => 'int',
        'input' => 'text',
        'apply_to' => 'simple,bundle,grouped,configurable',
        'note' => 'Comprimento da embalagem do produto'
    );

    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $codigo, $config);
}

$codigo = 'volume_altura';
if(!$installer->getAttribute(Mage_Catalog_Model_Product::ENTITY, $codigo, 'attribute_id')) {
    $config = array(
        'position' => 1,
        'required' => 0,
        'label' => 'Altura (cm)',
        'type' => 'int',
        'input' => 'text',
        'apply_to' => 'simple,bundle,grouped,configurable',
        'note' => 'Altura da embalagem do produto'
    );

    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $codigo, $config);
}
//
$codigo = 'volume_largura';
if(!$installer->getAttribute(Mage_Catalog_Model_Product::ENTITY, $codigo, 'attribute_id')) {
    $config = array(
        'position' => 1,
        'required' => 0,
        'label' => 'Largura (cm)',
        'type' => 'int',
        'input' => 'text',
        'apply_to' => 'simple,bundle,grouped,configurable',
        'note' => 'Largura da embalagem do produto'
    );

    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $codigo, $config);
}

$installer->endSetup();
/**/