<?php
class DDE_Bannercreator_Block_Adminhtml_Bannercreator_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("bannercreator_form", array("legend"=>Mage::helper("bannercreator")->__("Informações Gerais")));
				
				$fieldset->addField("title", "text", array(
					"label" => Mage::helper("bannercreator")->__("Título"),
					"class" => "required-entry",
					"required" => true,
					"name" => "title",
				));
							
				$fieldset->addField('image_filepath', 'image', array(
					'label' => Mage::helper('bannercreator')->__('Imagem'),
					'name' => 'image_filepath',
					'note' => '(*.jpg, *.png, *.gif) / <strong style="color: #F00">Altura máxima: 437px</strong>',
				));

				$fieldset->addField('image_position', 'select', array(
					'label'     => Mage::helper('bannercreator')->__('Posição da Imagem'),
					'values'   => DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getValueArray2(),
					'name' => 'image_position',
				));

				/*$fieldset->addField("content", "textarea", array(
					"label" => Mage::helper("bannercreator")->__("Texto"),
					"name" => "content",
				));*/
			
				$fieldset->addField("url", "text", array(
					"label" => Mage::helper("bannercreator")->__("URL"),
					"name" => "url",
				));
			
				$fieldset->addField("sort_order", "text", array(
					"label" => Mage::helper("bannercreator")->__("Ordenação"),
					"name" => "sort_order",
				));

				$fieldset->addField('status', 'select', array(
					'label'     => Mage::helper('bannercreator')->__('Status'),
					'values'   => DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getValueArray4(),
					'name' => 'status',
				));

				// Content
				$row1Fieldset = $form->addFieldset("bannercreator_form_row_1", array("legend"=>Mage::helper("bannercreator")->__("Texto Linha 1")));
				
				$row1Fieldset->addField("row_1_text", "text", array(
					"label" => Mage::helper("bannercreator")->__("Texto"),
					"name" => "row_1_text",
				));

				$row1Fieldset->addField("row_1_font_size", "text", array(
					"label" => Mage::helper("bannercreator")->__("Tamanho da Fonte"),
					"name" => "row_1_font_size",
				));

				$row1Fieldset->addField("row_1_color", "text", array(
					"label" => Mage::helper("bannercreator")->__("Cor da Fonte"),
					"name" => "row_1_color"
				));

				$row1Fieldset->addField('row_1_image_filepath', 'image', array(
					'label' => Mage::helper('bannercreator')->__('Imagem'),
					'name' => 'row_1_image_filepath',
					'note' => '(*.jpg, *.png, *.gif) / <strong style="color: #F00">Altura máxima: 200px</strong>',
				));

				$row1Fieldset->addField('row_1_image_position', 'select', array(
					'label'     => Mage::helper('bannercreator')->__('Posição da Imagem'),
					'values'   => DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getValueArray3(),
					'name' => 'row_1_image_position',
				));

				// Content
				$row2Fieldset = $form->addFieldset("bannercreator_form_row_2", array("legend"=>Mage::helper("bannercreator")->__("Texto Linha 2")));
				
				$row2Fieldset->addField("row_2_text", "text", array(
					"label" => Mage::helper("bannercreator")->__("Texto"),
					"name" => "row_2_text",
				));

				$row2Fieldset->addField("row_2_font_size", "text", array(
					"label" => Mage::helper("bannercreator")->__("Tamanho da Fonte"),
					"name" => "row_2_font_size",
				));

				$row2Fieldset->addField("row_2_color", "text", array(
					"label" => Mage::helper("bannercreator")->__("Cor da Fonte"),
					"name" => "row_2_color",
				));

				$row2Fieldset->addField('row_2_image_filepath', 'image', array(
					'label' => Mage::helper('bannercreator')->__('Imagem'),
					'name' => 'row_2_image_filepath',
					'note' => '(*.jpg, *.png, *.gif) / <strong style="color: #F00">Altura máxima: 200px</strong>',
				));

				$row2Fieldset->addField('row_2_image_position', 'select', array(
					'label'     => Mage::helper('bannercreator')->__('Posição da Imagem'),
					'values'   => DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getValueArray3(),
					'name' => 'row_2_image_position',
				));

				// Content
				$row3Fieldset = $form->addFieldset("bannercreator_form_row_3", array("legend"=>Mage::helper("bannercreator")->__("Texto Linha 3")));
				
				$row3Fieldset->addField("row_3_text", "text", array(
					"label" => Mage::helper("bannercreator")->__("Texto"),
					"name" => "row_3_text",
				));

				$row3Fieldset->addField("row_3_font_size", "text", array(
					"label" => Mage::helper("bannercreator")->__("Tamanho da Fonte"),
					"name" => "row_3_font_size",
				));

				$row3Fieldset->addField("row_3_color", "text", array(
					"label" => Mage::helper("bannercreator")->__("Cor da Fonte"),
					"name" => "row_3_color",
				));

				$row3Fieldset->addField('row_3_image_filepath', 'image', array(
					'label' => Mage::helper('bannercreator')->__('Imagem'),
					'name' => 'row_3_image_filepath',
					'note' => '(*.jpg, *.png, *.gif) / <strong style="color: #F00">Altura máxima: 200px</strong>',
				));

				$rowField = $row3Fieldset->addField('row_3_image_position', 'select', array(
					'label'     => Mage::helper('bannercreator')->__('Posição da Imagem'),
					'values'   => DDE_Bannercreator_Block_Adminhtml_Bannercreator_Grid::getValueArray3(),
					'name' => 'row_3_image_position',
				));

				$rowField->setAfterElementHtml('
					<script type="text/javascript" src="'.Mage::getBaseUrl(TRUE).'js/bannercreator/jquery/jquery-1.11.3.min.js"></script>
					<script type="text/javascript" src="'.Mage::getBaseUrl(TRUE).'js/bannercreator/jquery/jquery-migrate-1.2.1.min.js"></script>
					<script type="text/javascript" src="'.Mage::getBaseUrl(TRUE).'js/bannercreator/bootstrap-colorpicker-master/dist/js/bootstrap-colorpicker.min.js"></script>
					<link rel="stylesheet" type="text/css" href="'.Mage::getBaseUrl(TRUE).'js/bannercreator/bootstrap-colorpicker-master/dist/css/bootstrap-colorpicker.min.css" media="all" />
					<script type="text/javascript">
						jQuery.noConflict();
						jQuery("#row_1_color").colorpicker({
							container: jQuery("#row_1_color").parents(".value").first()
						});

						jQuery("#row_2_color").colorpicker({
							container: jQuery("#row_2_color").parents(".value").first()
						});

						jQuery("#row_3_color").colorpicker({
							container: jQuery("#row_3_color").parents(".value").first()
						});
					</script>
				');

				if (Mage::getSingleton("adminhtml/session")->getBannercreatorData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getBannercreatorData());
					Mage::getSingleton("adminhtml/session")->setBannercreatorData(null);
				} 
				elseif(Mage::registry("bannercreator_data")) {
				    $form->setValues(Mage::registry("bannercreator_data")->getData());
				}
				return parent::_prepareForm();
		}
}
