<?php
/*======================================================================*\
|| #################################################################### ||
|| # Package - Joomla Template based on YJSimpleGrid Framework          ||
|| # Copyright (C) 2010  Youjoomla.com. All Rights Reserved.            ||
|| # Authors - Dragan Todorovic and Constantin Boiangiu                 ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla.com                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a text element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */
 

class JFormFieldYJSGversion extends JFormField
{
	

	
	public $type = 'YJSGversion';
	
	public function getInput(){
		

		
		 $document =& JFactory::getDocument();
		 jimport('joomla.filesystem.file');
 		 jimport( 'joomla.filesystem.folder' );
		 $params_obj = $this->form->getValue('params');
		 $params 	= new JRegistry();
		 $params->loadObject($params_obj);

$comp_dis  ='<div id="option-resut">';
if($params->get('component_switch')){
	$comp_dis .= JText::_('YJSG_COMPONENT_DISABLED');
}
$comp_dis .='</div>';
		 $less_compiler_on		= $params->get('less_compiler_on');
		 $document->addScriptDeclaration("var comp_dis ='".JText::_('YJSG_COMPONENT_DISABLED')."';var lesscom_on_txt ='".JText::_('COMP_ON_ELEMENT_DESC')."';var lesscom_off_txt ='".JText::_('COMP_OFF_ELEMENT_DESC')."';");
		 if($less_compiler_on ==1){
			 
			 $less_on_msg = '<span class="lesscom_check ison">'.JText::_('COMP_ON_ELEMENT_DESC').'</span>';
		 }else{
			 $less_on_msg ='<span class="lesscom_check">'.JText::_('COMP_OFF_ELEMENT_DESC').'</span>';
		 }
		 
		 $template_folder		= basename(dirname(dirname(__FILE__)));
		 $template_path 		= JPATH_ROOT."/templates/".$template_folder;


		// Get the template XML.
		$client			= JApplicationHelper::getClientInfo(0);
		$path			= JPath::clean($client->path.'/templates/'. $template_folder.'/templateDetails.xml');
		$tpl_default 	= array();
		$skip_types		= array('yjsgversion','yjsgtextblank','yjsgparamtitle','yjsgtime','yjsgmultitext','yjsglogo','menuitem');
		
		if (file_exists($path)) {
			$xml = simplexml_load_file($path);
		} else {
			$xml = null;
		}
		if($xml){
			$fieldset_no = count($xml->config->fields->fieldset);
			for($i=0;$i<=$fieldset_no;$i++){
				if($xml->config->fields->fieldset[$i]){
					foreach ($xml->config->fields->fieldset[$i] as $attribut){
						if(!in_array($attribut['type'],$skip_types)){
							$field_name 						= $attribut['name'];
							$default 							= $attribut['default'];
							$tpl_default[(string)$field_name] 	= (string)$default;
						}
					}					
				}
			}
		}
		$document->addScriptDeclaration('var tplDefaults='.json_encode($tpl_default).';');	



		// Output	
$document->addCustomTag('
<!--[if IE 7]>
<style type="text/css">
.elSelect .option{
	margin-top:-1px;
}
.selectsContainer .overDiv{
position:static;
}
</style>
<![endif]--> 
<style type="text/css">
.yj_system_check{
	overflow:hidden;
	margin:0;
	padding:0;
}
.yj_system_check .yjmm_installed,
.yj_system_check .yjmm_published{
	color:green;
	font-weight:bold;
	margin:5px 0 0 0;
	padding:0 10px 0 0;
}
.yj_system_check .yjmm_installed_no,
.yj_system_check .yjmm_published_no{
	color:red;
	font-weight:bold;
	margin:5px 0 0 0;
	padding:0 10px 0 0;
}
.getyjmplugin{
	color:green;
	font-weight:bold;
	margin:5px 0 0 0;
}
.yj_system_check h2{
	color:#0B55C4;
	margin:0;
	line-height:17px;
}
</style>
');
echo $less_on_msg;
echo $comp_dis;
if (!JFile::exists(JPATH_SITE.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'YJMegaMenu/YJMegaMenu.php')){
$plug_installed ="_no";
$installed_word ="not";
$download='<li class="getyjmplugin"><a href="http://www.youjoomla.com/joomla-templates/yougrids-free-joomla-template-yjsg-powered.html?opendowns#files_holder" target="_blank">Download YJ Mega Menu Plugin</a></li>';
}else{
$plug_installed ="";
$installed_word ="";
$download='';
}

if(!JPluginHelper::getPlugin('system', 'YJMegaMenu') || !JFile::exists(JPATH_SITE.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'YJMegaMenu/YJMegaMenu.php')){
$plug_publihsed ="_no";
$publihsed_word ="not";
}else{
$plug_publihsed ="";
$publihsed_word ="";
}

echo '
<div class="yj_system_check">
<a href="'.JURI::root().'templates/'.$template_folder.'/yjsgcore/yjsgversion.php" class="modal" rel="{handler: \'iframe\', size: {x: 350, y: 200}}">Click to Check YJSG Version</a>
	<ul>
		<li class="yjmm_installed'.$plug_installed.'">YJ Mega Menu plugin is '.$installed_word.' installed</li>
		<li class="yjmm_published'.$plug_publihsed.'">YJ Mega Menu plugin is '.$publihsed_word.'  published</li>
		'.$download.'
	</ul>
</div>
';
		
	}

	public function getLabel() {
		return false;
	}
}

?>