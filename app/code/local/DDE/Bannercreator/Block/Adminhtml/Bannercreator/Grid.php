<?php

class DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct(){
			parent::__construct();
			$this->setId("bannercreatorGrid");
			$this->setDefaultSort("id");
			$this->setDefaultDir("DESC");
			$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection(){
			$collection = Mage::getModel("bannercreator/bannercreator")->getCollection();
			$this->setCollection($collection);

			return parent::_prepareCollection();
		}
		
		protected function _prepareColumns(){
			$this->addColumn("id", array(
				"header" => Mage::helper("bannercreator")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
			));
            
			$this->addColumn("title", array(
				"header" => Mage::helper("bannercreator")->__("Título"),
				"index" => "title",
			));

			$this->addColumn('image_position', array(
				'header' => Mage::helper('bannercreator')->__('Posição da Imagem'),
				'index' => 'image_position',
				'type' => 'options',
				'options'=>DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getOptionArray2(),				
			));
					
			$this->addColumn("url", array(
				"header" => Mage::helper("bannercreator")->__("URL"),
				"index" => "url",
			));

			$this->addColumn("sort_order", array(
				"header" => Mage::helper("bannercreator")->__("Ordenação"),
				"index" => "sort_order",
			));

			return parent::_prepareColumns();
		}

		public function getRowUrl($row){
			return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction(){
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_bannercreator', array(
				 'label'=> Mage::helper('bannercreator')->__('Remove Bannercreator'),
				 'url'  => $this->getUrl('*/adminhtml_bannercreator/massRemove'),
				 'confirm' => Mage::helper('bannercreator')->__('Are you sure?')
			));

			return $this;
		}
			
		static public function getOptionArray2(){
            $data_array=array(); 
			$data_array['left'] = 'Esquerda';
			$data_array['right'] = 'Direita';

            return($data_array);
		}

		static public function getValueArray2(){
            $data_array = array();

			foreach(DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getOptionArray2() as $k => $v) $data_array[] = array('value'=>$k,'label'=>$v);		

            return($data_array);

		}
			
		static public function getOptionArray3(){
            $data_array=array(); 
			$data_array['left'] = 'Esquerda';
			$data_array['right'] = 'Direita';
			$data_array['center'] = 'Centralizada';

            return($data_array);
		}

		static public function getValueArray3(){
            $data_array = array();

			foreach(DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getOptionArray3() as $k => $v) $data_array[] = array('value'=>$k,'label'=>$v);		

            return($data_array);

		}
			
		static public function getOptionArray4(){
            $data_array=array(); 
			$data_array[0] = 'Desabilitado';
			$data_array[1] = 'Habilitado';

            return($data_array);
		}

		static public function getValueArray4(){
            $data_array = array();

			foreach(DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getOptionArray4() as $k => $v) $data_array[] = array('value'=>$k,'label'=>$v);		

            return($data_array);

		}
		

}