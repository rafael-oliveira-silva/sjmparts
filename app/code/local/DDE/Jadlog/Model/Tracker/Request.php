<?php
class DDE_Jadlog_Model_Tracker_Request{

	private function _trataDados($conteudo)
	{
		$xml = new SimpleXMLElement($conteudo);

		if ($xml === false){
			return false;
		}

		$_progresso = array();
		$i = 0;


		foreach ($xml->Jadlog_Tracking_Consultar->ND->Evento as $evento){
			$_progresso[$i]['data'] = $evento->DataHoraEvento;
			$_progresso[$i]['localizacao'] = $evento->Observacao;
			$_progresso[$i]['status'] = $evento->Descricao;
			$i++;
		}

		return $_progresso;
	}

	public function send($tracking)
	{
		$params = [
			'consulta' => [
				[
					'cte' => $tracking
				]
			]
		];

		try {
			$curl = curl_init('https://www.jadlog.com.br/embarcador/api/tracking/consultar');
			curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjU5OTAyfQ.dYxPKmd83yibFFSIUGFHvgrcAkOhnOdB_dcBTWuFz4g']);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));

			$content = curl_exec($curl);
			$response = json_decode($content);

			$trackingData = $response->consulta[0];

			if (isset($trackingData->error)) {
				return false;
			}

			return $this->normalizeData($trackingData);
		} catch (Exception $e) {
			Mage::log($e->getMessage());
			return false;
		}

		/* $codcliente = Mage::getStoreConfig('carriers/jadlogmethod/conta_corrente');
		$senha = Mage::getStoreConfig('carriers/jadlogmethod/senha_acesso');
		$url = 'http://www.jadlog.com.br:8080/JadlogEdiWs/services/TrackingBean?wsdl';

		try {
			$client = new SoapClient($url);
		}
		catch(Exception $e){
			echo "Seviço temporariamente indisponível.";
		}

		if(!is_numeric($tracking)){
			echo "Código de rastreamento inválido"; exit;
		}

		$result = $client->consultar(array(
			'CodCliente' => $codcliente,
			'Password' => $senha,
			'NDs' => $tracking
		));

		$body = $result->consultarReturn;

		return $this->_trataDados($body); */
	}

	public function normalizeData($data) {
		$steps = [];

		foreach ($data->tracking->eventos as $step) {
			$steps[] = [
				'data' => $step->data,
				'localizacao' => $step->unidade,
				'status' => $step->status
			];
		}

		return $steps;
	}
}
