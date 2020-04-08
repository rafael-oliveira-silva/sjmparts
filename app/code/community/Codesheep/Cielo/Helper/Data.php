<?php

class Codesheep_Cielo_Helper_Data extends Mage_Core_Helper_Abstract{
	public $parcelasSemJuros;
	public $valorMinimo;
	
	public function getValues(){
		$this->parcelasSemJuros = Mage::getStoreConfig('payment/cielo/parcelas_sem_juros');
		$this->valorMinimo 		= Mage::getStoreConfig('payment/cielo/valor_minimo');
		$this->taxaJuros 		= str_replace( ',', '.', Mage::getStoreConfig('payment/cielo/taxa_juros') );
		$this->maximoParcelas	= Mage::getStoreConfig('payment/cielo/parcelas');
	}
	
	public function calcularParcelas($preco){
		$parcelas = array();
		
		// Cálcula as parcelas
		for( $i = 1; $i <= $this->maximoParcelas; $i++ ){
			// Calcula o valor da parcela
			$valorParcela = $preco/$i;
			// Verifica se ele está dentro da regra do valor mínimo
			if( $valorParcela >= $this->valorMinimo ){
				// Juros da parcela
				$jurosParcela = ( $valorParcela / 100 ) * $this->taxaJuros;
				
				// Parcela sem juros
				if( $i <= $this->parcelasSemJuros ) $parcelas[$i] = 'R$ '.number_format( $valorParcela, 2, ',', '.' );
				
				// Parcela com juros
				else $parcelas[$i] = 'R$ '.number_format( round($valorParcela+$jurosParcela,2), 2, ',', '.' );
			}
		}
		
		return $parcelas;
	}
	
	public function getParcelas($preco){
		$this->getValues();
		$parcelas = $this->calcularParcelas($preco);
		return $parcelas;
	}
	
	public function getParcelamentoHtml($preco){
		$this->getValues();
		$parcelas = $this->calcularParcelas($preco);
		
		$html = '<div class="cielo-parcelamento">';
			// $html .= '<p class="parcela-principal">em até de '.$this->parcelasSemJuros.'X de '.$parcelas[$this->parcelasSemJuros].' sem juros <a href="#" class="ver-parcelas">ver todos</a></p>';
			if(count($parcelas) > 1){
				$html .= '<div class="parcelamento-pagseguro">ou em '.count($parcelas).'x de <span>'.$parcelas[count($parcelas)].'</span></div>';
			}else{
				$html .= '<div class="parcelamento-pagseguro">&nbsp;</div>';
			}
			$html .= '<div class="todas-parcelas" style="display: none;">';
				$i = 0;
				$total = count( $parcelas );
				
				foreach( $parcelas as $parcela ){
					
					// Last? First?
					$lastNum = ($total / 4) * ($total / 4 );
					$first = $i == 0 ? ' first' : '';
					$last = $i == $lastNum-1 ? ' last' : '';
					
					if( $i == 0 ) $html .= '<ul class="'.$first.$last.'">';
					
					// Divide 6 por coluna
					if( $i%4 == 0 && $i > 0 ) $html .= '</ul><ul class="'.$first.$last.'">';
						$html .= '<li class="'.$first.$last.'"><p><span>'.($i+1).'X de </span>'.$parcela.'</p></li>';
					if( $i == $total-1 )$html.= '</ul>';
					$i++;
				}
			$html .= '</div>';
		$html .= '</div>';
		
		$html .= '<script type="text/javascript">
			jQuery(\'.ver-parcelas\').toggle( function(e){
				e.preventDefault();
				jQuery(this).parent(\'p\').siblings(\'.todas-parcelas\').slideDown(500);
			}, function(e){
				e.preventDefault();
				jQuery(this).parent(\'p\').siblings(\'.todas-parcelas\').slideUp(500);
			} );
		</script>';
		
		return $html;
	}
}