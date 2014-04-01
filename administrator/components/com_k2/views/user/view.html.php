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

class K2ViewUser extends JView
{

	function display($tpl = null) {
	
		JRequest::setVar('hidemainmenu', 1);
		$model = & $this->getModel();
		$user = $model->getData();
	
		if (JRequest::getInt('userID')) {
			$joomlaUser = & JUser::getInstance(JRequest::getInt('userID'));
		}
		else {
			$joomlaUser = & JUser::getInstance($user->userID);
		}
	
		$user->name = $joomlaUser->name;
		$user->userID = $joomlaUser->id;
		$this->assignRef('row', $user);
	
		$wysiwyg = & JFactory::getEditor();
		$editor = $wysiwyg->display('description', $user->description, '100%', '250', '40', '5', false);
		$this->assignRef('editor', $editor);
	
		$lists = array ();
		$genderOptions[] = JHTML::_('select.option', 'm', JText::_('Male'));
		$genderOptions[] = JHTML::_('select.option', 'f', JText::_('Female'));
		$lists['gender'] = JHTML::_('select.radiolist', $genderOptions, 'gender','','value','text',$user->gender);
		
		$userGroupOptions=$model->getUserGroups();
		$lists['userGroup']=JHTML::_('select.genericlist', $userGroupOptions, 'group', 'class="inputbox"', 'id', 'name', $user->group);
		
		$this->assignRef('lists', $lists);
	
		$params = & JComponentHelper::getParams('com_k2');
		$this->assignRef('params', $params);
		
		JPluginHelper::importPlugin ( 'k2' );
		$dispatcher = &JDispatcher::getInstance ();
		$K2Plugins=$dispatcher->trigger('onRenderAdminForm', array ($user, 'user' ) );
		$this->assignRef('K2Plugins', $K2Plugins);
	
		JToolBarHelper::title(JText::_('User'));
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		$toolbar=JToolBar::getInstance('toolbar');
		$toolbar->prependButton('Link', 'edit', JText::_('Edit Joomla User'), JURI::base().'index.php?option=com_users&view=user&task=edit&cid[]='.$user->userID);
	
		parent::display($tpl);
	}

}
