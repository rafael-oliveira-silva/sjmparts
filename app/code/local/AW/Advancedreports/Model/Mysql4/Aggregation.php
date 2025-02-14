<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedreports
 * @version    2.4.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */

/**
 * Video Resource Model
 */
class AW_Advancedreports_Model_Mysql4_Aggregation extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Class constructor
     */
    protected function _construct()
    {
        $this->_init('advancedreports/aggregation', 'entity_id');
    }


    public function cleanTable()
    {
        $table = $this->getMainTable();
        $write = $this->_getWriteAdapter();
        $write->beginTransaction();
        $write->exec(new Zend_Db_Expr("DELETE FROM `{$table}`"));
        $write->commit();
        return $this;
    }

}