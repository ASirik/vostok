<?php
/*
// "K2" Component by JoomlaWorks for Joomla! 1.5.x - Version 2.1
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr and http://k2.joomlaworks.gr
// Designed and developed by the JoomlaWorks team
// *** Last update: September 9th, 2009 ***
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerItem extends JController
{

	function display() {

		$model=&$this->getModel('itemlist');
		$document =& JFactory::getDocument();
		$viewType = $document->getType();
		$view = &$this->getView('item', $viewType);
		$view->setModel($model);
		JRequest::setVar('view', 'item');
		$user = &JFactory::getUser();
		if ($user->guest){
			parent::display(true);
		}
		else {
			parent::display(false);
		}
	}

	function edit() {
	
		$view = & $this->getView('item', 'html');
		$view->setLayout('form');
		$view->edit();
	}
	
	function add() {
	
		$view = & $this->getView('item', 'html');
		$view->setLayout('form');
		$view->edit();
	}
	
	function save() {
		global $mainframe;
		JRequest::checkToken() or jexit('Invalid Token');
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'item.php');
		$model= new K2ModelItem;
		$model->save(true);
		$mainframe->close();
		
	}
	
	function deleteAttachment() {
	
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'item.php');
		$model= new K2ModelItem;
		$model->deleteAttachment();
	}
	
	function tag() {
	
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'tag.php');
		$model= new K2ModelTag;
		$model->addTag();
	}
	
	function download(){
	
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'item.php');
		$model= new K2ModelItem;
		$model->download(true);
	}
	
	function extraFields(){
		
		global $mainframe;
		$itemID=JRequest::getInt('cid',NULL);

		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
		$catid = JRequest::getVar('id');
		$category = & JTable::getInstance('K2Category', 'Table');
		$category->load($catid);
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'extrafield.php');
		$extraFieldModel= new K2ModelExtraField;
		
		$extraFields = $extraFieldModel->getExtraFieldsByGroup($category->extraFieldsGroup);
		
		$output='<table class="admintable" id="extraFields">';
		$counter=0;
		if (count($extraFields)){
			foreach ($extraFields as $extraField){
				$output.='<tr><td align="right" class="key">'.$extraField->name.'</td>';
				$output.='<td>'.$extraFieldModel->renderExtraField($extraField,$itemID).'</td></tr>';
				$counter++;
			}
		}
		$output.='</table>';
		
		if ($counter==0) $output=JText::_("This category doesn't have assigned extra fields");
					
		echo $output;
		$mainframe->close();
	}

	function checkin(){
		
		$model = & $this->getModel('item');
		$model->checkin();
	}
	
	function vote()	{
		
		$model = & $this->getModel('item');
		$model->vote();
	}
	
	function getVotesNum()	{
		
		$model = & $this->getModel('item');
		$model->getVotesNum();
	}
	
	function getVotesPercentage()	{
		
		$model = & $this->getModel('item');
		$model->getVotesPercentage();
	}
	
	function comment(){
	
		$model = & $this->getModel('item');
		$model->comment();
	}

}
