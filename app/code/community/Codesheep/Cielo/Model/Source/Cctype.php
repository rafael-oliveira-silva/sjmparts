<?php

class Codesheep_Cielo_Model_Source_Cctype extends Mage_Payment_Model_Source_Cctype
{
  public function getAllowedTypes()
  {
      return array('AE', 'DC', 'DI', 'EL', 'MC', 'VI', 'AG', 'CB', 'CS', 'CZ', 'SC');
  }	
}
