<?php

class PagSeguro_Helper_Data extends Mage_Core_Helper_Abstract{
	public function getParcelamentoHtml($price, $parcela = NULL){
		$parcelas = $this->calcularParcelas($price);
		
		if( empty($parcela) ) $parcela = Mage::getStoreConfig('payment/pagseguro_standard/parcelas_maximas');
		
		$valor = $parcelas[$parcela-1]['label'];
		
		$html = '<div class="parcelamento-pagseguro">ou em '.$parcela.'x de '.$valor.'</div>';
		
		return empty($valor) ? '' : $html;
	}
	
	public function getParcelaHtml($price, $parcela = NULL){
		$parcelas = $this->calcularParcelas($price);
		
		if( empty($parcela) ) $parcela = Mage::getStoreConfig('payment/pagseguro_standard/parcelas_maximas');
		
		$valor = $parcelas[$parcela-1]['label'];
		$maxParcelas = Mage::getStoreConfig('payment/pagseguro_standard/parcelas_maximas');
		
		$html = '<div class="parcelamento-pagseguro"><span>em</span> '.$maxParcelas.'x <span>de</span> '.$valor.'</div>';
		
		return empty($valor) ? '' : $html;
	}
	
	public function calcularParcelas($total){
		$max_parcelas = Mage::getStoreConfig('payment/pagseguro_standard/parcelas_maximas');
		$valor_minimo = 10;
		$parcelas_sem_juros = Mage::getStoreConfig('payment/pagseguro_standard/parcelas_maximas');
		$taxa_juros = str_replace( ',', '.', Mage::getStoreConfig('payment/pagseguro_standard/taxa_pag_seguro') );
		
		$_count = floor( $total / $valor_minimo );
		if( $_count > $max_parcelas ) $_count = $max_parcelas;
		elseif( $_count < 1 ) $_count = 1;
		
		$parcelas = array();
		
		for( $i = 1; $i <= $_count; $i++ ){
			if( $i <= $parcelas_sem_juros ){
				$parcela = $this->price($total, $i, 0);
				$parcelas[] = array( 'parcela' => $i, 'label' => 'R$'.$parcela );
			}else{
				$parcela = $this->price($total, $i, $taxa_juros);
				$parcelas[] = array( 'parcela' => $i, 'label' => 'R$'.$parcela );
			}
		}
		
		return $parcelas;
	}
	
	/**
	 * @method price
	 *
	 * Calculates each installment for given value
	 * and tax using "Price" table
	 *
	 * @param valor 	  	- Product value
	 * @param parcelas 	- Installments
	 * @param juros 	  	- Tax
	 * @return  final			- Final value formated using french notation
	 */
	public function price($valor, $parcelas, $juros) {
		$juros = bcdiv($juros,100,15);
		$E = 1.0;
		$cont = 1.0;

		for($k=1; $k <= $parcelas; $k++){
			$cont = bcmul( $cont, bcadd($juros,1,15), 15 );
			$E = bcadd( $E, $cont, 15 );
		}
		
		$E = bcsub( $E, $cont, 15 );

		$valor = bcmul( $valor, $cont, 15);
		
		$final = number_format( bcdiv( $valor, $E, 15), 2, ',', '.' );
		
		return $final;
	}
}