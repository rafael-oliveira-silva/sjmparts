<?php
/*____________________________________________________________________

* Name Extension: Magento Ajaxsearch Autocomplete And Suggest

* Author: The Cmsmart Development Team 

* Date Created: 2013

* Websites: http://cmsmart.net

* Technical Support: Forum - http://cmsmart.net/support

* GNU General Public License v3 (http://opensource.org/licenses/GPL-3.0)

* Copyright © 2011-2013 Cmsmart.net. All Rights Reserved.

______________________________________________________________________*/
?>
<?php
class Cmsmart_Ajaxsearch_SuggestController extends Mage_Core_Controller_Front_Action
{
    public function resultAction()
    {

		$this->loadLayout();     
		$this->renderLayout();

    }
}
