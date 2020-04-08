<?php
class DDE_Bannercreator_Block_Content extends Mage_Core_Block_Template{
    protected $_position = 'CONTENT_TOP';
    protected $_isActive = 1;
    protected $_banner = array();

    public function getBanner($position = 'CONTENT_TOP') {
        if (isset($this->_banner[$position])) {
            return $this->_banner[$position];
        }

        $storeId = Mage::app()->getStore()->getId();
        $collection = Mage::getModel('ibanner/banner')->getCollection()
                ->addEnableFilter($this->_isActive);
        if (!Mage::app()->isSingleStoreMode()) {
            $collection->addStoreFilter($storeId);
        }

        if (Mage::registry('current_category')) {
            $_categoryId = Mage::registry('current_category')->getId();
            $collection->addCategoryFilter($_categoryId);
        } elseif (Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms') {
            $_pageId = Mage::getBlockSingleton('cms/page')->getPage()->getPageId();
            $collection->addPageFilter($_pageId);
        }

        if ($position) {
            $collection->addPositionFilter($position);
        } elseif ($this->_position) {
            $collection->addPositionFilter($this->_position);
        }
        $this->_banner[$position] = $collection->getFirstItem();

        return $this->_banner[$position];
    }

    public function getItems(){
        return Mage::getModel('bannercreator/bannercreator')->getCollection()->addFieldToFilter('status', ['eq' => 1])->setOrder('sort_order', 'ASC');
    }
}