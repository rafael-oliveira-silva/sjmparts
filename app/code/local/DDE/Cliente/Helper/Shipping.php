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
class DDE_Cliente_Helper_Shipping extends Mage_Core_Helper_Abstract
{
    /**
     * Determine if given shipping method code should be visible.
     * If code is not "flatrate", it will always return true,
     * otherwise it will check current postcode against accepted
     * postcode ranges
     *
     * @param string $code
     * @return boolean
     * @deprecated
     */
    public function isShippingMethodEnabled(string $code = null, string $postcode = null): bool
    {
        return true;

        // $isFlatrate = $code === 'flatrate';

        // if (!$isFlatrate) {
        //     return true;
        // }
        
        // return $this->showFixedShippingMethod($postcode);
    }
    
    /**
     * Validate given postcode against accepted postcode ranges.
     * If no postcode parameter is provided, it will try to use
     * current quote shipping address postcode
     *
     * @param string $postcode
     * @return boolean
     */
    public function showFixedShippingMethod(string $postcode = null): bool
    {
        $grandTotal = $this->getOrderGrandTotal();
        if ($grandTotal < 1000) {
            return false;
        }

        if (empty($postcode)) {
            $postcode = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getPostcode();
            $postcode = $this->getPostcode();
        }

        $postcode = (int)str_replace('-', '', $postcode);

        $postcodeRanges = $this->getPostcodeRanges();
        $showFixedShippingRate = false;
        foreach ($postcodeRanges as $postcodeRange) {
            $from = $postcodeRange[0];
            $to = $postcodeRange[1];

            $showFixedShippingRate = ($postcode >= $from && $postcode <= $to);
            if ($showFixedShippingRate) {
                break;
            }
        }
        
        return $showFixedShippingRate;
    }

    /**
     * Returns a list of accepted postcode ranges
     *
     * @return array
     */
    protected function getPostcodeRanges(): array
    {
        $ranges = [
            // Amapá
            [
                68900000,
                68999999
            ],
            // Pará
            [
                66000000,
                68899999
            ],
            // Maranhão
            [
                65000000,
                65999999
            ],
            // Ceará
            [
                60000000,
                63999999
            ],
            // Piauí
            [
                64000000,
                64999999
            ],
            // Tocantins
            [
                77000000,
                77999999
            ],
            // Goiás 1
            [
                72800000,
                72999999
            ],
            // Goiás 2
            [
                73700000,
                76799999
            ],
            // Rio Grande do Norte
            [
                59000000,
                59999999
            ],
            // Paraíba
            [
                58000000,
                58999999
            ]
            ];

        return $ranges;
    }

    /**
     * Get postcode from current quote shipping address.
     * If none is found, it will return an empty string
     *
     * @return string|null
     */
    protected function getPostcode()
    {
        $postcode = '';
        $shippingAddress = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
        
        if (!empty($shippingAddress)) {
            $postcode = $shippingAddress->getPostcode();
        }

        return $postcode;
    }

    /**
     * Return current quote grand total
     *
     * @return float
     */
    protected function getOrderGrandTotal(): float
    {
        return Mage::getSingleton('checkout/session')->getQuote()->getGrandTotal();
    }
}
