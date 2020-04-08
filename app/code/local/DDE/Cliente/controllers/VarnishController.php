<?php

/**
 *
 */
class DDE_Cliente_VarnishController extends Mage_Core_Controller_Front_Action{
	public function getnewslletersplashAction(){
		// Zend_Debug::dump(rand());
		echo $this->getLayout()->createBlock('core/template')->setTemplate('page/html/splash_newsletter_ajax.phtml')->toHtml();
	}

	public function updatecookieAction(){
		unset($_COOKIE['splash_screen']);

		$date = new DateTime;
		$date->add(new DateInterval('P6M'));
		setcookie('splash_screen', 'TRUE', $date->format('U'));
	}

}
