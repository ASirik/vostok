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

class K2ModelCpanel extends JModel
{

	function getLatestItems() {
	
		global $mainframe;
		$db = & JFactory::getDBO();
		$query = "SELECT i.*, g.name AS groupname, c.name AS category, v.name AS author, w.name as moderator, u.name AS editor FROM #__k2_items as i";
		$query .= " LEFT JOIN #__k2_categories AS c ON c.id = i.catid".
		" LEFT JOIN #__groups AS g ON g.id = i.access".
		" LEFT JOIN #__users AS u ON u.id = i.checked_out".
		" LEFT JOIN #__users AS v ON v.id = i.created_by".
		" LEFT JOIN #__users AS w ON w.id = i.modified_by";
	
		$query .= " ORDER BY i.created DESC";
		
		$db->setQuery($query, 0, 10);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	function getLatestComments() {
	
		global $mainframe;
		$db = & JFactory::getDBO();
		$query = "SELECT * FROM #__k2_comments ORDER BY commentDate DESC";
		$db->setQuery($query, 0, 10);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	function countItems(){
		
		global $mainframe;
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_items";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function countTrashedItems(){
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_items WHERE trash=1";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function countFeaturedItems(){
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_items WHERE featured=1";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function countComments(){
		
		global $mainframe;
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_comments";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	function countCategories(){
		
		global $mainframe;
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_categories";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function countTrashedCategories(){
		
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_categories WHERE trash=1";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function countUsers(){
		
		global $mainframe;
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_users";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	
	function countUserGroups(){
		
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_user_groups";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function countTags(){
		
		global $mainframe;
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_tags";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

}
