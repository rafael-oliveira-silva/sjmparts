<?php
class VladimirPopov_WebForms_Model_Captcha extends Zend_Service_ReCaptcha{

	public function getHtml(){
		if(Mage::getStoreConfig('webforms/captcha/api') != 'ajax')
			return parent::getHtml();
		
		$return = "";
		
		$div_id = "webform_recaptcha";
		if(Mage::registry('webform')){
			$div_id = "webform_".Mage::registry('webform')->getId()."_recaptcha";
		}
		
// 		$return .= <<<HTML
// 		<div id="{$div_id}"></div>
// HTML;

		$return .= '<div class="g-recaptcha" data-sitekey="6Ld3x1MUAAAAALAbD9ly9CCPDeVWwEYeXfSWun9k"></div>';

		$return .= <<<SCRIPT
<script src='https://www.google.com/recaptcha/api.js'></script>
SCRIPT;
		
		if (!empty($this->_options)) {
			$encoded = Zend_Json::encode($this->_options);
		}
		
// 		$return .= <<<SCRIPT
// <script type="text/javascript">
// 	Recaptcha.create("{$this->_publicKey}", "{$div_id}", {$encoded});
// </script>
// SCRIPT;
		
		return $return;
	}

	public function verify($response = NULL){
		if( empty($response) ) return FALSE;

		$client = new Varien_Http_Client('https://www.google.com/recaptcha/api/siteverify');
		$client->setMethod(Varien_Http_Client::POST);
		$client->setParameterPost('secret', Mage::getStoreConfig('webforms/captcha/private_key'));
		$client->setParameterPost('response', $response);
		
		try{
			$response = $client->request();
			if( $response->isSuccessful() ){
				$data = json_decode($response->getBody());

				return $data->success;
			}
		}catch(Exception $e){

		}

		return FALSE;
	}
}
?>
