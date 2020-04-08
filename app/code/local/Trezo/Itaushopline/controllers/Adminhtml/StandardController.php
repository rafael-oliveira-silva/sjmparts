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

class Trezo_Itaushopline_Adminhtml_StandardController extends Mage_Adminhtml_Controller_Action
{
    public function showAction()
    {
        $order_id = (string) $this->getRequest()->getParam('pedido');
        $transaction = Mage::getModel('itaushopline/transactions')->load($order_id, 'order_id');

        if (!$transaction) {
            Mage::throwException('Transaction not found for this order.');
        }

        $submit_form = new Varien_Data_Form();
        $submit_form->addField('submit_dc', 'hidden', array(
            'name' => 'DC',
            'value' => $transaction->getSubmitDc(),
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
        return Mage::getSingleton('admin/session')->isAllowed('admin/trezo/itaushopline_standard');
    }
}
