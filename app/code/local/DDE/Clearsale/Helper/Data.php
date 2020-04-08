<?php
/**
 * 18digital
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPL 3.0)
 * that is bundled with this package in the file LICENSE_GPL.txt
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to suporte@18digital.com.br so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * You can edit, copy, distribute and change this file, however, no information
 * about it's author, company, owner or any legal information can be changed,
 * erase or edited in no circumstances.
 *
 * @category      DDE
 * @package       DDE_ClearSale
 * @author		  Samir J M Araujo
 * @authorEmail   samir.araujo@18digital.com.br
 * @company	 	  18digital
 * @copyright     Copyright (c) 2013 18digital (http://18digital.com.br)
 * @version		  1.0.0
 * @license       GPL-3.0  GNU General Public License (GPL 3.0)
 * @licenseUrl    http://opensource.org/licenses/gpl-3.0.html
 */

class DDE_Clearsale_Helper_Data extends Mage_Core_Helper_Abstract{
	public function getPaymentTypeId($method = NULL){
		// If no method is found, then set "Outros" => 14
		if( empty($method) ) return '14';
		
		
		// Default: "Outros" => 14
		$id = '14';
		
		if( strstr($method, 'cielo') || strstr($method, 'rede_adquirencia') ) $id = '1';
		elseif( strstr($method, 'boleto') ) $id = '2';
		elseif( strstr($method, 'checkmo') ) $id = '3';
		
		return $id;
	}
	
	public function getOrderStatus($order = NULL){
		if( empty($order) ) return FALSE;
		
		$client = new Varien_Http_Client('http://www.clearsale.com.br/integracaov2/service.asmx/GetOrderStatus');
		$client->setMethod(Varien_Http_Client::POST)
			   ->setParameterPost('entityCode', '7E023DDC-0AFF-492C-82CC-7FB206106DF0')
			   ->setParameterPost('orderID', $order);
		
		try{
			$response = $client->request();
			return $response->getBody();
		}catch( Exception $e ){
			Mage::log($e->getMessage());
			// Zend_Debug::dump( $e->getMessage() );
		}
	}
	
	public function readXml($_xml = NULL){
		if(empty($_xml)) return FALSE;
		
		try{
			$_xml = new SimpleXMLElement($_xml);
			$xml = new SimpleXMLElement(str_replace('utf-16', 'utf-8', $_xml[0]));
			
			return $xml;
		}catch(Exception $e){
			Mage::log($e->getMessage());
			
			return $xml;
		}
	}
	
	// @TODO: Localization
	public function getStatusString($_status = NULL){
		if( empty($_status) ) return FALSE;
		
		$status = NULL;
		
		switch($_status){
			case 'APA':
				$status = '<strong style="color:#00A700;">'.$_status.' (Aprovação Automática)</strong> - Pedido foi aprovado automaticamente segundo parâmetros definidos na regra de aprovação automática.';
				break;
				
			case 'APM':
				$status = '<strong style="color:#00A700;">'.$_status.' (Aprovação Manual)</strong> - Pedido aprovado manualmente por tomada de decisão de um analista.';
				break;
				
			case 'RPM':
				$status = '<strong style="color:#F00;">'.$_status.'(Reprovado Sem Suspeita)</strong> - Pedido Reprovado sem Suspeita por falta de contato com o cliente dentro do período acordado e/ou políticas restritivas de CPF (Irregular, SUS ou Cancelados).';
				break;
				
			case 'AMA':
				$status = '<strong>'.$_status.'(Análise manual)</strong> - Pedido está em fila para análise.';
				break;
				
			case 'ERR':
				$status = '<strong style="color:#F00;">'.$_status.'(Erro)</strong> - Ocorreu um erro na integração do pedido, sendo necessário analisar um possível erro no XML enviado e após a correção reenvia-lo.';
				break;
				
			case 'NVO':
				$status = '<strong style="color:#F00;">'.$_status.'(Novo)</strong> - Pedido importado e não classificado Score pela analisadora (processo que roda o Score de cada pedido).';
				break;
				
			case 'SUS':
				$status = '<strong style="color:#F00;">'.$_status.'(Suspensão Manual)</strong> - Pedido Suspenso por suspeita de fraude baseado no contato com o “cliente” ou ainda na base ClearSale.';
				break;
				
			case 'CAN':
				$status = '<strong style="color:#F00;">'.$_status.'(Cancelado pelo Cliente)</strong> - Cancelado por solicitação do cliente ou duplicidade do pedido.';
				break;
				
			case 'FRD':
				$status = '<strong style="color:#F00;">'.$_status.'(Fraude Confirmada)</strong> - Pedido imputado como Fraude Confirmada por contato com a administradora de cartão e/ou contato com titular do cartão ou CPF do cadastro que desconhecem a compra.';
				break;
				
			case 'RPA':
				$status = '<strong style="color:#F00;">'.$_status.'(Reprovação Automática)</strong> - Pedido Reprovado Automaticamente por algum tipo de Regra de Negócio que necessite aplicá-la (Obs: não usual e não recomendado).';
				break;
				
			case 'RPP':
				$status = '<strong style="color:#F00;">'.$_status.'(Reprovação Por Política)</strong> - Pedido reprovado automaticamente por política estabelecida pelo cliente ou ClearSale.';
				break;
			
			default:
				$status = '<strong>Não foi possível consultar o status.</strong>';
		}
		
		return $status;
	}
	
	// @TODO: Write function description
	public function resendOrder($orderId = NULL){
		if( empty($orderId) ) $orderId = Mage::app()->getRequest()->getParam('order_id', NULL);

		if( empty($orderId) ){
			Mage::log('ClearSale: Can\'t define order ID');
			return FALSE;
		}
		
		$order = Mage::getModel('sales/order')->load($orderId);
		
		// @TODO: Check model call
		$clearSale = Mage::getModel('clearsale/clearsale');
		$xml = $clearSale->generateXml($order);
		// echo '<pre>';
		// var_dump($xml);
		// echo '</pre>';
		// Mage::log('ClearSale Extension XML Debug: '.$xml);
		
		$client = new Varien_Http_Client('http://www.clearsale.com.br/integracaov2/service.asmx/SendOrders');
		$client->setMethod(Varien_Http_Client::POST)
			   ->setParameterPost('entityCode', '7E023DDC-0AFF-492C-82CC-7FB206106DF0')
			   ->setParameterPost('xml', $xml);
		
		try{
			$response = $client->request();
			// echo $response->getBody();
		}catch( Exception $e ){
			// echo $e->getMessage();
			Mage::log( $e->getMessage() );
		}
		
		return $this;
	}
}