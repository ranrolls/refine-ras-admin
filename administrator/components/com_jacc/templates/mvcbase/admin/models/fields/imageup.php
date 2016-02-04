<?php
/**
 * @version		$Id: imageup.php 187 2014-03-05 15:20:43Z michel $
 * @copyright	Copyright (C) ##year##, ##author##. All rights reserved.
 * @license ###license## 
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Administrator
 * @subpackage	    com_##component##
*/
class JFormFieldImageup extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	public $type = 'Imageup';

	protected function getInput()
	{
		// Initialize some field attributes.
		$config 	= JComponentHelper::getParams( 'com_##component##' );
		$width = $config->get('imgwidth3',60);
		$height = $config->get('imgheight3',80);
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$this->folder		= $this->element['folder'] ? $this->element['folder'] : 'com_##component##';
		$src = $this->value ? JURI::root(true).$this->value : JUri::root().'/administrator/components/com_##component##/assets/noimage.png';
		$id="";
		$html = array();
		$html[] = 	'<div><button class="btn" type="button" name="btn-upload" id="'.$this->id.'uploader_button">'.JText::_('COM_##COMPONENT##_IMAGE_UPLOAD').'</button>';
		$html[] = 	'<input type="hidden" value="'.$this->value.'" name="'.$this->name.'" id="'.$this->id.'" />';
		$html[] = 	'<img style="margin-left:50px;" src="'.$src.'" id="'.$this->id.'previewimage" />';
		$html[] = 	'<button class="btn" type="button" name="btn-delete" onclick="JSOneClickImageDelete(\''.$this->id.'\')" id="'.$this->id.'delete_button">&times;</button>';
		$html[] = 	'</div>';

		$this->_addJs($this->id);

		return implode('',$html);
	}

	private function _addJs($id)
	{
		static $deletefunction;
		
		$js = "new JSOneClickUpload('".$this->id."uploader_button',{
					url: '".JUri::base()."',
					uploadFieldName: 'image',
					id: '".$this->id."',
					postvars: {
								option:'com_##component##',
								task:'imageup.upload',
								folder:'".$this->folder."'
							},
					token:'".JSession::getFormToken()."',
					onComplete: function(response){
						if(response == 'error' || response == 'noimage' || response == 'nofile') {
							return;
						}
						var x = eval('(' + response + ')');
						document.getElementById('".$id."previewimage').src = '".JURI::root(true)."' + x.thumbs.uri;
						document.getElementById('".$id."').value = x.thumbs.uri;
					}
				});";
		if(empty($deletefunction)) {
			$deletefunction = "
				function JSOneClickImageDelete(id) {
        			var noImage = '".JUri::root().'/administrator/components/com_##component##/assets/noimage.png'."';
        			document.getElementById(id + 'previewimage').src = noImage;
        			document.getElementById(id).value = '';
        		}";
		 
			$js .= $deletefunction;
		}		
		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true).'/administrator/components/com_##component##/assets/oneclickupload.js');
		$document->addScriptDeclaration($js);
	}
}