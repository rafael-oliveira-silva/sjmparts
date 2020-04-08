<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Newsletter
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Newsletter subscriber model for MySQL4
 *
 * @category    Mage
 * @package     Mage_Newsletter
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Newsletter_Model_Mysql4_Subscriber extends Mage_Newsletter_Model_Resource_Subscriber{
	protected function _prepareSave(Mage_Newsletter_Model_Subscriber $subscriber){
		// Zend_Debug::dump($subscriber);
		// exit;
		// $data = array();
		// $data['customer_id'] = $subscriber->getCustomerId();
		// $data['store_id'] = $subscriber->getStoreId()?$subscriber->getStoreId():0;
		// $data['subscriber_status'] = $subscriber->getStatus();
		// $data['subscriber_email']  = $subscriber->getEmail();
		// $data['subscriber_confirm_code'] = $subscriber->getCode();
		// $data['firstname'] = $subscriber->getFirstname();
		// $data['person_type'] = $subscriber->getPersonType();
	}
}
