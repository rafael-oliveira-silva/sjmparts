<?php
// 	exit;
// echo 'teste';
require_once('app/Mage.php');
umask(0);
Mage::app();
ini_set('display_errors', true);
$app = Mage::app('');
 
$myFile = 'var/export/subscribers_newsletter'.date('d-m-y').'.csv';
$fp = fopen($myFile, 'w');
 
// $columns = array('Nome','Sobrenome','Email');
$columns = array('Name','Email');
fputcsv($fp,$columns);
 
/* get Newsletter Subscriber whose status is equal to "Subscribed"    */
 
$sql = "SELECT * FROM r35e_newsletter_subscriber WHERE subscriber_status = 1";
$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
foreach ($connection->fetchAll($sql) as $arr_row) {
 
	$loademail = $arr_row['subscriber_email'];
	 
	$customer = Mage::getModel('customer/customer');
	$customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
	$customer->loadByEmail($loademail);
	 
	$fname = explode(',', $customer->getData('firstname'));
	$lname = explode(',', $customer->getData('lastname'));
	$email = explode(',', $customer->getData('email'));
	$fname = $fname[0].' '.$lname[0];
	// $lname = $lname[0];
	$email = $email[0];

	if ($fname=="" && $lname==""){
		$fname="--";
		$lname="--";
		$email=$arr_row['subscriber_email'];
	}

	// $subscribers = array('firstname'=>$fname,'lastname'=>$lname,'email'=>$email);
	$subscribers = array('firstname'=>$fname,'email'=>$email);
	fputcsv($fp,$subscribers);
}
 
fclose($fp);
header('Content-disposition: attachment; filename=' . $myFile);
header('Content-type: application/text');
readfile($myFile);
// echo 'teste';
exit;
?>