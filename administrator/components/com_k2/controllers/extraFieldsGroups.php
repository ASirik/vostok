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

class K2ControllerExtraFieldsGroups extends JController
{

	function display() {
		JRequest::setVar('view', 'extraFieldsGroups');
		$model = & $this->getModel('extraFields');
		$view = & $this->getView('extraFieldsGroups', 'html');
		$view->setModel($model, true);
		parent::display();
	}
	
	function add() {
		global $mainframe;
		$mainframe->redirect('index.php?option=com_k2&view=extraFieldsGroup');
	}

	function edit() {
		global $mainframe;
		$cid = JRequest::getVar('cid');
		$mainframe->redirect('index.php?option=com_k2&view=extraFieldsGroup&cid='.$cid[0]);
	}
	
	function remove() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('extraFields');
		$model->removeGroups();
	}

}
