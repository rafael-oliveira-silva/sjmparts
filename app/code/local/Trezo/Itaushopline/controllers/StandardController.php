<?php
/**
* Trezo
*
* NOTICE OF LICENSE
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.trezo.com.br for more information.
*
* @category Trezo
* @package Trezo_Itaushopline
*
* @copyright Copyright (c) 2017 Trezo. (http://www.trezo.com.br)
*
* @author Trezo Core Team <contato@trezo.com.br>
*/

class Trezo_Itaushopline_StandardController extends Mage_Core_Controller_Front_Action
{

    public function showAction()
    {
        $pedido = (string) $this->getRequest()->getParam('pedido');
        $order = Mage::getModel('sales/order')->loadByIncrementId($pedido);

        if (!Mage::helper('itaushopline')->canViewOrder($order)) {
            $this->_redirect('sales/order/history');
            return;
        }

        $transactionsModel = Mage::getModel('itaushopline/transactions');
        $transactionsModel->setOrder($order);

        if ($transactionsModel->orderIsCanceled() || $transactionsModel->billetIsExpired()) {
            echo 'O boleto não pôde ser impresso. Verifique se o pedido já expirou ou foi cancelado!';
            return false;
        }

        if (empty($pedido)) {
            return;
        }

        $collection = Mage::getModel('itaushopline/transactions')->getCollection();
        $collection->getSelect()->where("order_id = {$order->getId()}");
        $data = $collection->getFirstItem()->toArray();

        $submit_form = new Varien_Data_Form();
        $submit_form->addField('submit_dc', 'hidden', array(
            'name' => 'DC',
            'value' => $data['submit_dc'],
        ));

        $submit_form->addFieldset("submit_button_form", array());
        $submit_form->addField("test", "submit", array(
        "value" => 'ok',
        "onclick" => 'submit_form.submit()',
        ));

        $form = "<label>Aguarde voc&ecirc; ser&aacute; redirecionado...</label>";
        $form .= '<form method="post" id="itaushopline_form_submit" style="visibility: hidden;" action="https://shopline.itau.com.br/shopline/Impressao.aspx">';
        $form .= $submit_form->toHtml();
        $form .=  "</form>";

        $form .= "
        <script type=\"text/javascript\">
            document.getElementById('itaushopline_form_submit').submit();
    	</script>";

        echo $form;
    }

    protected function _isAllowed()
    {
        return true;
    }
}
