<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Model_Cielo_Transaction_Buyer_Customer extends Mage_Core_Model_Abstract
{
    private $customer;
    private $order;

    public function __construct(Mage_Sales_Model_Order $order)
    {
        $this->order = $order;
        $this->customer = $order->getCustomer();
    }

    public function getName()
    {
        if (!$name = trim($this->customer->getName())) {
            $name = $this->order->getCustomerName();
        }

        return $name;
    }

    public function getEmail()
    {
        if (!$email = $this->customer->getEmail()) {
            $email = $this->order->getCustomerEmail();
        }

        return $email;
    }

    public function getBuyerReference()
    {
        return $this->customer->getIncrementId();
    }

    public function getBirthDate()
    {
        if (!$dob = $this->customer->getDob()) {
            $dob = $this->order->getCustomerDob();
        }

        return \DateTime::createFromFormat('Y-m-d', $dob);
    }

    public function getGender()
    {
        if (!$gender = $this->customer->getGender()) {
            $gender = $this->order->getCustomerGender();
        }

        return $gender;
    }

    public function getDocumentNumber()
    {
        $field = Mage::helper('trezo_cielo')->getConfig('document_number');
        if (!$document = $this->customer->getData($field)) {
            $document = $this->order->getData('customer_' . $field);
        }

        return $this->traitDocument(
            $document
        );
    }

    public function isPerson()
    {
        if (strlen($this->getDocumentNumber()) > 11) {
            return false;
        }

        return true;
    }

    public function getPersonType()
    {
        if ($this->isPerson()) {
            return 'CPF';
        }

        return 'CNPJ';
    }

    public function getDocumentType()
    {
        if ($this->isPerson()) {
            return 'CPF';
        }

        return 'CNPJ';
    }

    private function traitDocument($document)
    {
        return preg_replace('/[^0-9]+/', '', $document);
    }

}