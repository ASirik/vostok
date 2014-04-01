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

class TableK2UserGroup extends JTable {

	var $id = null;
	var $name = null;
	var $permissions = null;

	function __construct( & $db) {
	
		parent::__construct('#__k2_user_groups', 'id', $db);
	}

	function check() {
	
		if (trim($this->name) == '') {
			$this->setError(JText::_('Group cannot be empty'));
			return false;
		}
		return true;
	}

	function bind($array, $ignore = '') {
		
		if (key_exists('params', $array) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			if(JRequest::getVar('categories')=='all' || JRequest::getVar('categories')=='none')
			$registry->setValue('categories',JRequest::getVar('categories'));
			$array['permissions'] = $registry->toString();
		}
		return parent::bind($array, $ignore);
	}

}
