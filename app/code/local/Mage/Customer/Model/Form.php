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
 * @package     Mage_Customer
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Customer Form Model
 *
 * @category    Mage
 * @package     Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Customer_Model_Form extends Mage_Eav_Model_Form
{
    /**
     * Current module pathname
     *
     * @var string
     */
    protected $_moduleName = 'customer';

    /**
     * Current EAV entity type code
     *
     * @var string
     */
    protected $_entityTypeCode = 'customer';

    public function validateData(array $data)
    {
        $errors = parent::validateData($data);
        $dataAll = Mage::app()->getRequest()->getParams();

        if (is_array($errors)) {
            return $errors;
        }

        $recaptcha = null;
        if (isset($dataAll['g_recaptcha']) && !empty($dataAll['g_recaptcha'])) {
            $recaptcha = $dataAll['g_recaptcha'];
        } elseif (isset($dataAll['billing']['g_recaptcha']) && !empty($dataAll['billing']['g_recaptcha'])) {
            $recaptcha = $dataAll['billing']['g_recaptcha'];
        }

        if (!empty($recaptcha)) {
            $client = new Varien_Http_Client('https://www.google.com/recaptcha/api/siteverify');
            $client->setMethod(Varien_Http_Client::POST);
            $client->setParameterPost('secret', Mage::getStoreConfig('webforms/captcha/private_key'));
            $client->setParameterPost('response', $recaptcha);

            try {
                $response = $client->request();
                if ($response->isSuccessful()) {
                    $googleResponse = json_decode($response->getBody());

                    if ($googleResponse->success == 1) {
                        return true;
                    }
                }
            } catch (Exception $e) {
                $errors = array( 'recaptcha é necessário 2' );
            }
        }
        
        return $errors;
    }

    /**
     * Get EAV Entity Form Attribute Collection for Customer
     * exclude 'created_at'
     *
     * @return Mage_Customer_Model_Resource_Form_Attribute_Collection
     */
    protected function _getFormAttributeCollection()
    {
        return parent::_getFormAttributeCollection()
            ->addFieldToFilter('attribute_code', array('neq' => 'created_at'));
    }
}
