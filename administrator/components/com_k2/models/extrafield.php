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

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

class K2ModelExtraField extends JModel
{

	function getData() {
	
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('K2ExtraField', 'Table');
		$row->load($cid);
		return $row;
	}

	function save() {
	
		global $mainframe;
		$row = & JTable::getInstance('K2ExtraField', 'Table');
		if (!$row->bind(JRequest::get('post'))) {
			$mainframe->redirect('index.php?option=com_k2&view=extraFields', $row->getError(), 'error');
		}
	

		$isNewGroup = JRequest::getInt('isNew');
		
		if ($isNewGroup){
			
			$group = & JTable::getInstance('K2ExtraFieldsGroup', 'Table');
			$group->set('name', JRequest::getVar('group'));
			$group->store();
			$row->group = $group->id;
		}
	
		if(!$row->id){
			$row->ordering = $row->getNextOrder("`group` = {$row->group}");
		}
	
		$objects = array ();
		$values = JRequest::getVar('option_value');
		$names = JRequest::getVar('option_name');
		$target = JRequest::getVar('option_target');
		for ($i = 0; $i < sizeof($values); $i++) {
			$object = new JObject;
			$object->set('name',$names[$i]);
			
			if ($row->type=='select' || $row->type=='multipleSelect' || $row->type=='radio'){
				$object->set('value', $i+1);
			}
			elseif ($row->type=='link'){
				if (substr($values[$i], 0, 7) == 'http://'){$values[$i] = $values[$i];}
				else {$values[$i] = 'http://'.$values[$i];}
				$object->set('value', $values[$i]);
			}
			else {
				$object->set('value', $values[$i]);
			}
				
				
			$object->set('target', $target[$i]);
			unset($object->_errors);
			$objects[] = $object;
		}
		
		require_once(JPATH_COMPONENT.DS.'lib'.DS.'JSON.php');
		$json=new Services_JSON;
		$row->value=$json->encode($objects);

		if (!$row->check()) {
			$mainframe->redirect('index.php?option=com_k2&view=extraField&cid='.$row->id, $row->getError(), 'error');
		}
	
		if (!$row->store()) {
			$mainframe->redirect('index.php?option=com_k2&view=extraFields', $row->getError(), 'error');
		}
	
		$row->reorder("`group` = {$row->group}");
	
		$cache = & JFactory::getCache('com_k2');
		$cache->clean();
	
		switch(JRequest::getCmd('task')) {
			case 'apply':
			$msg = JText::_('Changes to Extra Field saved');
			$link = 'index.php?option=com_k2&view=extraField&cid='.$row->id;
			break;
			case 'save':
			default:
			$msg = JText::_('Extra Field Saved');
			$link = 'index.php?option=com_k2&view=extraFields';
			break;
		}
	
		$mainframe->redirect($link, $msg);
	}

	function getExtraFieldsByGroup($group){
		
		$db = & JFactory::getDBO();
		$query = "SELECT * FROM #__k2_extra_fields WHERE `group`={$group} AND published=1 ORDER BY ordering";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	function renderExtraField($extraField,$itemID=NULL){
		
		global $mainframe;
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'JSON.php');
		$json=new Services_JSON;
		
		if (!is_null($itemID)){
			$item = & JTable::getInstance('K2Item', 'Table');
			$item->load($itemID);
		}
		
		$defaultValues=$json->decode($extraField->value);
		
		foreach ($defaultValues as $value){
			if ($extraField->type=='textfield' || $extraField->type=='textarea')
			$active=$value->value;
			else if($extraField->type=='link'){
				$active[0]=$value->name;
				$active[1]=$value->value;
				$active[2]=$value->target;
			}
			else 
			$active='';
		}
		
		if (isset($item)){
			$currentValues=$json->decode($item->extra_fields);
			if (count($currentValues)){
				foreach ($currentValues as $value){
					if ($value->id==$extraField->id){
						$active=$value->value;
					}
					
				}
			}
			
		}
		
		switch ($extraField->type){
			
			case 'textfield':
			$output='<input type="text" name="K2ExtraField_'.$extraField->id.'" value="'.$active.'"/>';
			break;
			
			case 'textarea':
			$output='<textarea name="K2ExtraField_'.$extraField->id.'" rows="10" cols="40">'.$active.'</textarea>';
			break;
			
			case 'select':
			$output=JHTML::_('select.genericlist', $defaultValues, 'K2ExtraField_'.$extraField->id, '', 'value', 'name',$active);
			break;
			
			case 'multipleSelect':
			$output=JHTML::_('select.genericlist', $defaultValues, 'K2ExtraField_'.$extraField->id.'[]', 'multiple="multiple"', 'value', 'name',$active);
			break;
			
			case 'radio':
			$output=JHTML::_('select.radiolist', $defaultValues, 'K2ExtraField_'.$extraField->id, '', 'value', 'name',$active);
			break;
			
			case 'link':
			$output='<label>'.JText::_('Text').'</label>';
			$output.='<input type="text" name="K2ExtraField_'.$extraField->id.'[]" value="'.$active[0].'"/>';
			$output.='<label>'.JText::_('URL').'</label>';
			$output.='<input type="text" name="K2ExtraField_'.$extraField->id.'[]" value="'.$active[1].'"/>';
			$output.='<label for="K2ExtraField_'.$extraField->id.'">'.JText::_('Open in').'</label>';
			$targetOptions[]=JHTML::_('select.option', 'same', JText::_('Same window'));
			$targetOptions[]=JHTML::_('select.option', 'new', JText::_('New window'));
			$targetOptions[]=JHTML::_('select.option', 'popup', JText::_('Classic javascript popup'));
			$targetOptions[]=JHTML::_('select.option', 'lightbox', JText::_('Lightbox popup'));
			$output.=JHTML::_('select.genericlist', $targetOptions, 'K2ExtraField_'.$extraField->id.'[]', '', 'value', 'text', $active[2]);
			
			break;
			
		}

		return $output;

	}
	
	function getExtraFieldInfo($fieldID){
		
		$db = & JFactory::getDBO ();
		$query="SELECT * FROM #__k2_extra_fields WHERE published=1 AND fieldID = ".$db->Quote($fieldID);
		$db->setQuery ($query,0,1);
		$row = $db->loadObject ();
		return $row;
	}
	
	function getSearchValue($id, $currentValue){
		
		$row = & JTable::getInstance('K2ExtraField', 'Table');
		$row->load($id);
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'JSON.php');
		$json=new Services_JSON;
		$jsonObject=$json->decode($row->value);
		
		$value='';
		if ( $row->type=='textfield'|| $row->type=='textarea'){
			$value=$currentValue;
		}
		else if ($row->type=='multipleSelect'|| $row->type=='link'){
			foreach ($jsonObject as $option){
				if (in_array($option->value,$currentValue))
				$value.=$option->name.' ';
			}
		}
		else {
			foreach ($jsonObject as $option){
				if ($option->value==$currentValue)
				$value.=$option->name;
			}
		}
		return $value;
	}

}
