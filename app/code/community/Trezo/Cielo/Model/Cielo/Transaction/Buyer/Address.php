<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Model_Cielo_Transaction_Buyer_Address extends Mage_Core_Model_Abstract
{
    private $address;
    private $cieloAddress;

    public function __construct($address, $cieloAddress)
    {
        $this->address = $address;
        $this->cieloAddress = $cieloAddress;
    }

    public function addAddressData()
    {
        $address = $this->address;
        $cieloAddress = $this->cieloAddress;
        $cieloAddress
            ->setZipCode($this->getZipCode($address))
            ->setCountry('BRA')
            ->setState($this->sanitizeString($address->getRegionCode()))
            ->setCity($this->sanitizeString($address->getCity()))
            ->setDistrict($this->sanitizeString($this->getDistrict($address)))
            ->setStreet($this->sanitizeString($this->getStreet($address)))
            ->setComplement($this->sanitizeString($this->getComplement($address)))
            ->setNumber($this->sanitizeString($this->getNumber($address)));
    }

    public function getAddressType($address)
    {
        if ($address->getAddressType() == Mage_Customer_Model_Address_Abstract::TYPE_BILLING) {
            /* TO WORK WITH ANTIFRAUD IT NEED TO SEND -RESIDENTIAL- TYPE TO BUYER ADDRESS*/
            return \Gateway\One\DataContract\Enum\AddressTypeEnum::RESIDENTIAL;
        }

        return \Gateway\One\DataContract\Enum\AddressTypeEnum::SHIPPING;
    }

    public function getStreet($address)
    {
        $street = Mage::helper('trezo_cielo')->getConfig('street');
        return $address->getStreet($street);
    }

    public function getNumber($address)
    {
        $number = Mage::helper('trezo_cielo')->getConfig('number');
        return $address->getStreet($number);
    }

    public function getDistrict($address)
    {
        $district = Mage::helper('trezo_cielo')->getConfig('district');
        return $address->getStreet($district);
    }

    public function getComplement($address)
    {
        $complement = Mage::helper('trezo_cielo')->getConfig('complement');
        return $address->getStreet($complement);
    }

    public function getZipCode($address)
    {
        return $this->traitZipCode(
            $address->getPostcode()
        );
    }

    private function traitZipCode($zipcode)
    {
        return preg_replace('/[^0-9]+/', '', $zipcode);
    }

    public function getHomePhone()
    {
        $field = Mage::helper('trezo_cielo')->getConfig('home_phone');
        return $this->traitPhone($this->address->getData($field));
    }

    public function getMobilePhone()
    {
        $field = Mage::helper('trezo_cielo')->getConfig('mobile_phone');
        return $this->traitPhone($this->address->getData($field));
    }

    public function getWorkPhone()
    {
        $field = Mage::helper('trezo_cielo')->getConfig('work_phone');
        return $this->traitPhone($this->address->getData($field));
    }

    public function getAreaCode($phone)
    {
        return substr($phone, 0, 2);
    }

    public function getPhoneNumber($phone)
    {
        return substr($phone, -(strlen($phone) - 2));
    }

    private function traitPhone($phone)
    {
        $phone = preg_replace('/[^0-9]+/', '', $phone);

        if ($phone) {
            $phone = '(' . $this->getAreaCode($phone) . ')' . $this->getPhoneNumber($phone);
        }

        return $phone;
    }

    public function sanitizeString($string)
    {
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/–/'           =>   '-',
            '/[’‘‹›‚]/u'    =>   ' ',
            '/[“”«»„]/u'    =>   ' ',
            '/ /'           =>   ' ',
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $string);
    }
}
