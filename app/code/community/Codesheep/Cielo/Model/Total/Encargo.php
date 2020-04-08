<?php

/**
 * Your custom total model
 *
 */
class Codesheep_Cielo_Model_Total_Encargo extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    /** 
     * Constructor that should initiaze 
     */
    public function __construct()
    {
        $this->setCode('encargo');
    }

    /**
     * Used each time when collectTotals is invoked
     * 
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Your_Module_Model_Total_Custom
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
	
      $address->setEncargo(0);
      $address->setBaseEncargo(0);
			$payment = Mage::getSingleton('checkout/session')->getQuote()->getPayment();
			$grandTotal = $address->getGrandTotal() > 0 ? $address->getGrandTotal() : array_sum($address->getAllTotalAmounts());
			
			
			$cieloData = Mage::getSingleton('core/session')->getCieloData();
			
			Mage::log('GrandTotal: '.$grandTotal);
			Mage::log('Method: '.$cieloData['method']);			
			Mage::log('Parcelas: '.$cieloData["cc_parcelas"]);						
			
			if($grandTotal > 0 && $cieloData && $cieloData['method'] == 'cielo'){
//				$additionaldata = unserialize($payment->getData('additional_data'));
				if((int)$cieloData["cc_parcelas"] > (int)Mage::getStoreConfig('payment/cielo/parcelas_sem_juros')){	
					
					$total = $grandTotal;
					$taxa_juros = str_replace( ',', '.', Mage::getStoreConfig('payment/cielo/taxa_juros') );
					$n = $cieloData["cc_parcelas"];
					
					for ($i=0; $i < $n; $i++){ 
							$total *= 1 + ($taxa_juros / 100);
					}
					
					$encargo = $total - $grandTotal;
					
					$address->setEncargo($encargo);
		      $address->setBaseEncargo($encargo);				
		      $address->setGrandTotal($address->getGrandTotal() + $address->getEncargo());
		      $address->setBaseGrandTotal($address->getGrandTotal() + $address->getBaseEncargo());

				}
			}

      return $this;
    }

    /**
     * Used each time when totals are displayed
     * 
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Your_Module_Model_Total_Custom
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        // Display total only if it is not zero
//				echo var_dump($address->getEncargo());
        if ($address->getEncargo() > 0) {
						
	
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => 'Encargos Financeiros',
                'value' => $address->getEncargo()
            ));
        }
    }
}



?>