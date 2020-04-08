<?php
exit;
error_reporting(E_ALL | E_STRICT);
define('MAGENTO_ROOT', getcwd());

$mageFilename = MAGENTO_ROOT . '/app/Mage.php';

require_once $mageFilename;
Mage::setIsDeveloperMode(true);

ini_set('display_errors', 1);
Mage::app();
$products = Mage::getModel("catalog/product")->getCollection();
// $products->addAttributeToSelect('*');
$products->addAttributeToSelect('name');
$products->addAttributeToSelect('category_ids');
// $products->addAttributeToSelect('description');
$products->addAttributeToSelect('short_description');
$products->addAttributeToSelect('sku');
// $products->addAttributeToSelect('gtin');
$products->addAttributeToSelect('url_path');
// $products->addAttributeToSelect('image');

$fp = fopen('exports.csv', 'w');
// $csvHeader = array("Nome do produto", "SKU", "Categorias", "GTIN", "Descrição", "URL do produto", "URL da imagem principal");
$csvHeader = array("Nome do produto", "SKU", "Categorias", "URL do produto", "short_description");
fputcsv( $fp, $csvHeader,"#");

foreach ($products as $product){
	// Zend_Debug::dump($product->getData());
	$name               = $product->getName();
	$sku                = $product->getSku();
	// $gtin               = $product->getGtin();
	$description        = $product->getData('short_description');
	$cleanDescription   = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($description))))));
	$baseUrl            = Mage::getBaseUrl();
	$url                = $product->getData('url_path');
	// $imageUrl           = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
	$productUrls        = array();
	// $categoryIds = implode('|', $product->getCategoryIds());//change the category separator if needed
	$categoryIds = $product->getCategoryIds();
	$categoryNames = array();
	
	$productUrls[] = $baseUrl.$product->getUrlPath();
	foreach($categoryIds as $_category){
		$category = Mage::getModel('catalog/category')->load($_category);
		$categoryNames[] = $category->getName();
		// get all variations of a product url by category
		$productUrls[] = $baseUrl.$product->getUrlPath($category);
	}
	// Zend_Debug::dump($productUrls);
	// exit;
	// remove all the duplicates in the url
	$productUrls = array_unique($productUrls);
	$productUrls = implode(' | ', $productUrls);
	$categoryNames = implode(' | ', $categoryNames);

	// fputcsv($fp, array($name, $sku, $categoryNames, $gtin, $cleanDescription, $baseUrl.$url, $imageUrl), "#");
	fputcsv($fp, array($name, $sku, $categoryNames, $productUrls, $cleanDescription), "#");
}

fclose($fp);