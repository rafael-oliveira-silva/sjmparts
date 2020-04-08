<?php

class DDE_Bannercreator_Adminhtml_BannercreatorController extends Mage_Adminhtml_Controller_Action{
		protected function _isAllowed(){
			return Mage::getSingleton('admin/session')->isAllowed('cms/bannercreator');
		}

		protected function _initAction(){
				$this->loadLayout()->_setActiveMenu("bannercreator/bannercreator")->_addBreadcrumb(Mage::helper("adminhtml")->__("Gerenciar Criador de Banners"),Mage::helper("adminhtml")->__("Gerenciar Criador de Banners"));
				return $this;
		}

		public function indexAction() {
			    $this->_title($this->__("Bannercreator"));
			    $this->_title($this->__("Gerenciar Criador de Banners"));

				$this->_initAction();
				$this->renderLayout();
		}

		public function editAction(){			    
			    $this->_title($this->__("Bannercreator"));
				$this->_title($this->__("Bannercreator"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("bannercreator/bannercreator")->load($id);
				if ($model->getId()) {
					Mage::register("bannercreator_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("bannercreator/bannercreator");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Gerenciar Criador de Banners"), Mage::helper("adminhtml")->__("Gerenciar Criador de Banners"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Bannercreator Description"), Mage::helper("adminhtml")->__("Bannercreator Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("bannercreator/adminhtml_bannercreator_edit"))->_addLeft($this->getLayout()->createBlock("bannercreator/adminhtml_bannercreator_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("bannercreator")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction(){
			$this->_title($this->__("Bannercreator"));
			$this->_title($this->__("Bannercreator"));
			$this->_title($this->__("Novo Item"));

	        $id   = $this->getRequest()->getParam("id");
			$model  = Mage::getModel("bannercreator/bannercreator")->load($id);

			$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register("bannercreator_data", $model);

			$this->loadLayout();
			$this->_setActiveMenu("bannercreator/bannercreator");

			$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Gerenciar Criador de Banners"), Mage::helper("adminhtml")->__("Gerenciar Criador de Banners"));
			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Bannercreator Description"), Mage::helper("adminhtml")->__("Bannercreator Description"));


			$this->_addContent($this->getLayout()->createBlock("bannercreator/adminhtml_bannercreator_edit"))->_addLeft($this->getLayout()->createBlock("bannercreator/adminhtml_bannercreator_edit_tabs"));

			$this->renderLayout();
		}

		public function saveAction(){
			$post_data=$this->getRequest()->getPost();
			if ($post_data) {
				try {
			 		//save image
					try{
						if((bool)$post_data['image_filepath']['delete']==1) $post_data['image_filepath'] = '';
						else{

							unset($post_data['image_filepath']);

							if (isset($_FILES)){

								if ($_FILES['image_filepath']['name']) {
									// clean filename
									$file_name = $_FILES['image_filepath']['name'];
									$file_name = preg_replace("/[^a-zA-Z0-9.]/", "-", $file_name);
									$_FILES['image_filepath']['name'] = $file_name;

									if($this->getRequest()->getParam("id")){
										$model = Mage::getModel("bannercreator/bannercreator")->load($this->getRequest()->getParam("id"));
										if($model->getData('image_filepath')){
												$io = new Varien_Io_File();
												$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('image_filepath'))));	
										}
									}else $post_data['created_at'] = date('Y-m-d H:i:s');

									$path = Mage::getBaseDir('media') . DS . 'bannercreator' . DS .'bannercreator'.DS;
									$uploader = new Varien_File_Uploader('image_filepath');
									$uploader->setAllowedExtensions(array('jpg','png','gif'));
									$uploader->setAllowRenameFiles(TRUE);
									// $uploader->setAllowRenameFiles(FALSE);
									$uploader->setFilesDispersion(FALSE);

									// $destFile = $path.$file_name;
									$destFile = $path.$_FILES['image_filepath']['name'];
									$filename = $uploader->getNewFileName($destFile);
									$uploader->save($path, $filename);

									$post_data['image_filepath']='bannercreator/bannercreator/'.$filename;
								}
						    }
						}

			        }catch (Exception $e){
						Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
						return;
			        }

			        // Row 1 image
					try{
						if((bool)$post_data['row_1_image_filepath']['delete']==1) $post_data['row_1_image_filepath'] = '';
						else{

							unset($post_data['row_1_image_filepath']);

							if (isset($_FILES)){

								if ($_FILES['row_1_image_filepath']['name']) {
									if($this->getRequest()->getParam("id")){
										$model = Mage::getModel("bannercreator/bannercreator")->load($this->getRequest()->getParam("id"));
										if($model->getData('row_1_image_filepath')){
												$io = new Varien_Io_File();
												$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('row_1_image_filepath'))));	
										}
									}else $post_data['created_at'] = date('Y-m-d H:i:s');

									$path = Mage::getBaseDir('media') . DS . 'bannercreator' . DS .'bannercreator'.DS;
									$uploader = new Varien_File_Uploader('row_1_image_filepath');
									$uploader->setAllowedExtensions(array('jpg','png','gif'));
									$uploader->setAllowRenameFiles(FALSE);
									$uploader->setFilesDispersion(FALSE);
									
									$destFile = $path.$_FILES['row_1_image_filepath']['name'];
									$filename = $uploader->getNewFileName($destFile);
									$uploader->save($path, $filename);

									$post_data['row_1_image_filepath']='bannercreator/bannercreator/'.$filename;
								}
						    }
						}

			        }catch (Exception $e){
						Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
						return;
			        }

			        // Row 2 image
					try{
						if((bool)$post_data['row_2_image_filepath']['delete']==1) $post_data['row_2_image_filepath'] = '';
						else{

							unset($post_data['row_2_image_filepath']);

							if (isset($_FILES)){

								if ($_FILES['row_2_image_filepath']['name']) {
									if($this->getRequest()->getParam("id")){
										$model = Mage::getModel("bannercreator/bannercreator")->load($this->getRequest()->getParam("id"));
										if($model->getData('row_2_image_filepath')){
												$io = new Varien_Io_File();
												$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('row_2_image_filepath'))));	
										}
									}else $post_data['created_at'] = date('Y-m-d H:i:s');

									$path = Mage::getBaseDir('media') . DS . 'bannercreator' . DS .'bannercreator'.DS;
									$uploader = new Varien_File_Uploader('row_2_image_filepath');
									$uploader->setAllowedExtensions(array('jpg','png','gif'));
									$uploader->setAllowRenameFiles(FALSE);
									$uploader->setFilesDispersion(FALSE);
									
									$destFile = $path.$_FILES['row_2_image_filepath']['name'];
									$filename = $uploader->getNewFileName($destFile);
									$uploader->save($path, $filename);

									$post_data['row_2_image_filepath']='bannercreator/bannercreator/'.$filename;
								}
						    }
						}

			        }catch (Exception $e){
						Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
						return;
			        }

			        // Row 3 image
					try{
						if((bool)$post_data['row_3_image_filepath']['delete']==1) $post_data['row_3_image_filepath'] = '';
						else{

							unset($post_data['row_3_image_filepath']);

							if (isset($_FILES)){

								if ($_FILES['row_3_image_filepath']['name']) {
									if($this->getRequest()->getParam("id")){
										$model = Mage::getModel("bannercreator/bannercreator")->load($this->getRequest()->getParam("id"));
										if($model->getData('row_3_image_filepath')){
												$io = new Varien_Io_File();
												$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('row_3_image_filepath'))));	
										}
									}else $post_data['created_at'] = date('Y-m-d H:i:s');

									$path = Mage::getBaseDir('media') . DS . 'bannercreator' . DS .'bannercreator'.DS;
									$uploader = new Varien_File_Uploader('row_3_image_filepath');
									$uploader->setAllowedExtensions(array('jpg','png','gif'));
									$uploader->setAllowRenameFiles(FALSE);
									$uploader->setFilesDispersion(FALSE);
									
									$destFile = $path.$_FILES['row_3_image_filepath']['name'];
									$filename = $uploader->getNewFileName($destFile);
									$uploader->save($path, $filename);

									$post_data['row_3_image_filepath']='bannercreator/bannercreator/'.$filename;
								}
						    }
						}

			        }catch (Exception $e){
						Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
						return;
			        }
					
					//save image
					$post_data['updated_at'] = date('Y-m-d H:i:s');
					$model = Mage::getModel("bannercreator/bannercreator")
								 ->addData($post_data)
								 ->setId($this->getRequest()->getParam("id"))
								 ->save();

					Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Salvo com sucesso"));
					Mage::getSingleton("adminhtml/session")->setBannercreatorData(false);

					if ($this->getRequest()->getParam("back")) {
						$this->_redirect("*/*/edit", array("id" => $model->getId()));
						return;
					}
					$this->_redirect("*/*/");
					return;
				} 
				catch (Exception $e) {
					Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
					Mage::getSingleton("adminhtml/session")->setBannercreatorData($this->getRequest()->getPost());
					$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
				return;
				}

			}
			$this->_redirect("*/*/");
		}



		public function deleteAction(){
			if( $this->getRequest()->getParam("id") > 0 ) {
				try {
					$model = Mage::getModel("bannercreator/bannercreator");
					$model->setId($this->getRequest()->getParam("id"))->delete();
					Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
					$this->_redirect("*/*/");
				} 
				catch (Exception $e) {
					Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
					$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
				}
			}
			$this->_redirect("*/*/");
		}

		
		public function massRemoveAction(){
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("bannercreator/bannercreator");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
}
