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

class K2ViewCpanel extends JView
{

	function display($tpl = null) {
	
		$model = &$this->getModel();
		
		$latestItems = $model->getLatestItems();
		$this->assignRef('latestItems',$latestItems);
		
		$latestComments = $model->getLatestComments();
		$this->assignRef('latestComments',$latestComments);
		
		$numOfItems = $model->countItems();
		$this->assignRef('numOfItems',$numOfItems);
		
		$numOfTrashedItems = $model->countTrashedItems();
		$this->assignRef('numOfTrashedItems',$numOfTrashedItems);
		
		$numOfFeaturedItems = $model->countFeaturedItems();
		$this->assignRef('numOfFeaturedItems',$numOfFeaturedItems);
	
		$numOfComments = $model->countComments();
		$this->assignRef('numOfComments',$numOfComments);
		
		$numOfCategories = $model->countCategories();
		$this->assignRef('numOfCategories',$numOfCategories);
		
		$numOfTrashedCategories = $model->countTrashedCategories();
		$this->assignRef('numOfTrashedCategories',$numOfTrashedCategories);
		
		$numOfUsers = $model->countUsers();
		$this->assignRef('numOfUsers',$numOfUsers);
		
		$numOfUserGroups = $model->countUserGroups();
		$this->assignRef('numOfUserGroups',$numOfUserGroups);
	
		$numOfTags = $model->countTags();
		$this->assignRef('numOfTags',$numOfTags);
	
		$user = & JFactory::getUser();
	
		JToolBarHelper::title(JText::_('Dashboard'));
		JToolBarHelper::preferences('com_k2', '500', '600');
		
		if ($user->gid > 23){
			$toolbar=&JToolBar::getInstance('toolbar');
			$toolbar->prependButton('Link', 'archive', JText::_('Import Joomla! articles'), JURI::base().'index.php?option=com_k2&amp;view=items&amp;task=import');
		}
	
		JSubMenuHelper::addEntry(JText::_('Dashboard'), 'index.php?option=com_k2', true);
		JSubMenuHelper::addEntry(JText::_('Items'), 'index.php?option=com_k2&view=items');
		JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_k2&view=categories');
		JSubMenuHelper::addEntry(JText::_('Tags'), 'index.php?option=com_k2&view=tags');
		JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_k2&view=comments');

		if ($user->gid > 23) {
			JSubMenuHelper::addEntry(JText::_('Users'), 'index.php?option=com_k2&view=users');
			JSubMenuHelper::addEntry(JText::_('User Groups'), 'index.php?option=com_k2&view=userGroups');
			JSubMenuHelper::addEntry(JText::_('Extra Fields'), 'index.php?option=com_k2&view=extraFields');
			JSubMenuHelper::addEntry(JText::_('Extra Field Groups'), 'index.php?option=com_k2&view=extraFieldsGroups');
			JSubMenuHelper::addEntry(JText::_('Information'), 'index.php?option=com_k2&view=info');
		}
		
		parent::display($tpl);
	}

}
