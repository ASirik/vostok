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

jimport('joomla.application.component.view');

class K2ViewExtraField extends JView
{

	function display($tpl = null)
	{
	
		JRequest::setVar('hidemainmenu', 1);
		$model = & $this->getModel();
		$extraField = $model->getData();
		if(!$extraField->id)
			$extraField->published=1;
		$this->assignRef('row', $extraField);
	
		$lists = array ();
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $extraField->published);
		
		
		$groups[] = JHTML::_('select.option', 0, JText::_('Create New Group'));
		
		require_once (JPATH_COMPONENT.DS.'models'.DS.'extrafields.php');
		$extraFieldModel= new K2ModelExtraFields;
		$uniqueGroups= $extraFieldModel->getGroups();
		foreach ($uniqueGroups as $group){
			$groups[] = JHTML::_('select.option', $group->id, $group->name);
		}
		
		$lists['group'] = JHTML::_('select.genericlist', $groups, 'groups', '', 'value', 'text', $extraField->group);
		
		$typeOptions[] = JHTML::_('select.option', 0, JText::_('-- Select Type --'));
		$typeOptions[] = JHTML::_('select.option', 'textfield', JText::_('Text Field'));
		$typeOptions[] = JHTML::_('select.option', 'textarea', JText::_('Textarea'));
		$typeOptions[] = JHTML::_('select.option', 'select', JText::_('Drop-down selection'));
		$typeOptions[] = JHTML::_('select.option', 'multipleSelect', JText::_('Multi-select list'));
		$typeOptions[] = JHTML::_('select.option', 'radio', JText::_('Radio buttons'));
		$typeOptions[] = JHTML::_('select.option', 'link', JText::_('Link'));
		$lists['type'] = JHTML::_('select.genericlist', $typeOptions, 'type', '', 'value', 'text', $extraField->type);
		
		$this->assignRef('lists', $lists);
	
		JToolBarHelper::title(JText::_('Add/Edit Extra Field'));
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	
		parent::display($tpl);
	}

}
