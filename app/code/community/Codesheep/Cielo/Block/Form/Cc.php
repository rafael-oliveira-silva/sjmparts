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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Paygate
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Codesheep_Cielo_Block_Form_Cc extends Mage_Payment_Block_Form_Cc
{
    /**
     * Set block template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cielo/form/cc.phtml');
    }

    /**
     * Retreive payment method form html
     *
     * @return string
     */
    public function getMethodFormBlock()
    {
        return $this->getLayout()->createBlock('payment/form_cc')
            ->setMethod($this->getMethod());
    }
	
	/**
	 * Verifica se há algum produto no carrinho dentro das
	 * categorias pré-definidas
	 */
	public function verificarCarrinho(){
		// Produtos do carrinho
		$produtos = Mage::getSingleton('checkout/cart')->getQuote()->getAllItems();
		$catsS = explode( ',', Mage::getStoreConfig('payment/cielo/categorias_especiais') );
		
		foreach( $produtos as $produto ){
			// Desconsidera produtos simples associados à produtos comuns
			if( $produto->getParentItem() ) continue;
			
			// Carrega o model do produto
			$produtoT = Mage::getModel('catalog/product')->load($produto->getProductId());
			
			// Categorias
			$cats = $produtoT->getCategoryIds();
			$catsA = array();
			foreach($cats as $catId) $catsA[] = $catId;
			
			// Se encontra um produto dentro das categorias definidas
			// interrompe o processo e retorna TRUE
			foreach( $catsS as $catS ) if( in_array($catS, $catsA) ) return TRUE;
		}
		
		return FALSE;
	}
	
	public function getParcelas($specialCondition = FALSE){
		if( $specialCondition ){
			$max_parcelas = Mage::getStoreConfig('payment/cielo/parcelas_especiais');
			$valor_minimo = Mage::getStoreConfig('payment/cielo/valor_minimo_especial');
			$parcelas_sem_juros = Mage::getStoreConfig('payment/cielo/parcelas_sem_juros_especial');
			$taxa_juros = str_replace( ',', '.', Mage::getStoreConfig('payment/cielo/taxa_juros_especial') );
		}else{
			$max_parcelas = Mage::getStoreConfig('payment/cielo/parcelas');
			$valor_minimo = Mage::getStoreConfig('payment/cielo/valor_minimo');
			$parcelas_sem_juros = Mage::getStoreConfig('payment/cielo/parcelas_sem_juros');					
			$taxa_juros = str_replace( ',', '.', Mage::getStoreConfig('payment/cielo/taxa_juros') );
		}
									
		$total = Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal();
		
		$totals = Mage::getSingleton('checkout/cart')->getQuote()->getTotals();

		if(isset($totals["encargo"])){
			$encargo = $totals["encargo"]->getValue();							
		}else{
			$encargo = 0;
		}
		if($encargo > 0){
			$total = $total - $encargo;
		}
		
		
		$total_com_juros = $total;

		$n = floor($total / $valor_minimo);
		if($n > $max_parcelas){
			$n = $max_parcelas;
		}elseif($n < 1){
			$n = 1;
		}
		
		$parcelas = array();
	  for ($i=0; $i < $n; $i++){
			$total_com_juros *= 1 + ($taxa_juros / 100);

			if($i+1 == 1){
				$label = 'À vista - '.$this->helper('checkout')->formatPrice($total);
			}elseif($taxa_juros > 0 && $i+1 > $parcelas_sem_juros){
				$label = ($i+1).'x - '.$this->helper('checkout')->formatPrice($total_com_juros/($i + 1)).' (juros de '.str_replace( '.', ',', $taxa_juros ).'% ao mês)';
			}else{
				$label = ($i+1).'x - '.$this->helper('checkout')->formatPrice($total/($i + 1));
			}
			$parcelas[] = array('parcela' => $i+1, 'label' => $label);
		}
		return $parcelas;
	}
}
