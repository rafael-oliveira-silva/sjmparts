<?php
/**
 * Short description
 *
 * Long description
 *
 * Baseado no módulo original Cushy_Boleto de Renan Gonçalves <renan.saddam@gmail.com>
 * Copyright 2012, Rafael Camargo <desbloqueio@terra.com.br>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright       Copyright 2012, Rafael Camargo 
 * @category        Boleto_Rafael
 * @package         RafaelCamargo_Boleto
 * @license         http://www.opensource.org/licenses/mit-license.php The MIT License
 */ 
class RafaelCamargo_Boleto_Helper_Data extends Mage_Core_Helper_Abstract {
	/**
	 * @method getDescontoHtml
	 * @param  preco    - Float do preço
	 * @param  desconto - Float do desconto
	 * @return html
	 */
	public function getDescontoHtml($preco, $desconto, $class = 'desconto-boleto'){
		// Limpa os valores
		$desconto = (float) str_replace( ',', '.', $desconto );
		$preco    = (float) str_replace( ',', '.', $preco );
		
		$preco = $preco - (($preco/100) * $desconto);
		$preco = number_format( $preco, 2, ',', '.' );
		
		$html = '<p class="'.$class.'">á vista: <span data-type="payment-with-discount-price">R$ '.$preco.'</span> no Boleto (<span data-type="payment-discount-amount">'.$desconto.'</span>% desconto)</p>';
		
		return $html;
	}
}