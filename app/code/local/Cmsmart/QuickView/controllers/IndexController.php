<?php

class Cmsmart_QuickView_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Initialize requested product object
     *
     * @return Mage_Catalog_Model_Product
     */
	 
	public function _getProductFromUrl(){
		Mage::dispatchEvent('catalog_controller_product_init_before', array('controller_action'=>$this));
        // $categoryId = (int) $this->getRequest()->getParam('category', false);
        // $product_id  = (int) $this->getRequest()->getParam('id');	
		// $path  = (string) $this->getRequest()->getParam('path');
		$path = explode('index/index/path/', array_shift(explode('?', @$_SERVER['REQUEST_URI'])));
		$ex = explode('/id/', $path);
		//print_r($_SERVER);
		$product_id = 0;
		if(count($ex) > 1){
			$product_id = (int) @$ex[1];
		}
		if(!$product_id && count($path) > 1){
			$path = @$path[1];
			$path[0] == "\/" ? $path = substr($path, 1, strlen($path)) : $path;
			$tableName = Mage::getSingleton('core/resource')->getTableName('core_url_rewrite'); 
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			
			//$rs = $write->query('select `product_id` from `'.Mage::getConfig()->getTablePrefix().$tableName.'` where `request_path` = "'.$path.'"');
			$rs = $write->query('select `product_id` from `'.$tableName.'` where `request_path` = "'.$path.'"');
			
			if ($row = $rs->fetch() ) {
				$product_id = $row['product_id'];
			}	
		}
        if (!$product_id) {
            return false;
        }
		
        $product = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($product_id);

        if (!Mage::helper('catalog/product')->canShow($product)) {
            return false;
        }
        if (!in_array(Mage::app()->getStore()->getWebsiteId(), $product->getWebsiteIds())) {
            return false;
        }

        $category = null;
        if ($categoryId) {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $product->setCategory($category);
            Mage::register('current_category', $category);
        }
        elseif ($categoryId = Mage::getSingleton('catalog/session')->getLastVisitedCategoryId()) {
            if ($product->canBeShowInCategory($categoryId)) {
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $product->setCategory($category);
                Mage::register('current_category', $category);
            }
        }
        Mage::register('current_product', $product);
        Mage::register('product', $product);

        try {
            Mage::dispatchEvent('catalog_controller_product_init', array('product'=>$product));
            Mage::dispatchEvent('catalog_controller_product_init_after', array('product'=>$product, 'controller_action' => $this));
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            return false;
        }

        return $product;
	}
	public function indexAction()
    {    
		if($product = $this->_getProductFromUrl()){
			//$this->loadLayout();
			Mage::dispatchEvent('catalog_controller_product_view', array('product'=>$product));

            if ($this->getRequest()->getParam('options')) {
                $notice = $product->getTypeInstance(true)->getSpecifyOptionMessage();
                Mage::getSingleton('catalog/session')->addNotice($notice);
            }

            Mage::getSingleton('catalog/session')->setLastViewedProductId($product->getId());
            //Mage::getModel('catalog/design')->applyDesign($product, Mage_Catalog_Model_Design::APPLY_FOR_PRODUCT);

            $update = $this->getLayout()->getUpdate();
			$update->addHandle('default');
			$this->addActionLayoutHandles();
			$update->addHandle('PRODUCT_TYPE_'.$product->getTypeId());
			$update->addHandle('PRODUCT_'.$product->getId());
			$this->loadLayoutUpdates();
			$this->generateLayoutXml()->generateLayoutBlocks();	
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('tag/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();
		}
        else {
            if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                $this->_redirect('');
            } elseif (!$this->getResponse()->isRedirect()) {
                $this->_forward('noRoute');
            }
        }
		
	}
	
	public function quickdetailAction()
    {    
		$this->indexAction();
		
	}

}