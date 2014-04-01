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

class TableK2User extends JTable
{

	var $id = null;
	var $userID = null;
	var $userName = null;
	var $gender = null;
	var $description = null;
	var $image = null;
	var $url = null;
	var $group = null;
	var $plugins = null;

	function __construct( & $db) {
	
		parent::__construct('#__k2_users', 'id', $db);
	}
	
	function check() {
	
		if (trim($this->url) != '' && substr($this->url, 0, 7) != 'http://')
		$this->url = 'http://'.$this->url;
		return true;
	}
	
	function bind($array, $ignore = '')	{
	
		if (key_exists('plugins', $array) && is_array($array['plugins'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['plugins']);
			$array['plugins'] = $registry->toString();
		}
		
		return parent::bind($array, $ignore);
	}
	
}
