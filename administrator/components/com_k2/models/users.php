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

class K2ModelUsers extends JModel
{

	function getData() {
	
		global $mainframe;
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = & JFactory::getDBO();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', '', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$filter_status = $mainframe->getUserStateFromRequest($option.$view.'filter_status', 'filter_status', -1, 'int');
		$filter_group = $mainframe->getUserStateFromRequest($option.$view.'filter_group', 'filter_group', '', 'string');
		$filter_group_k2 = $mainframe->getUserStateFromRequest($option.$view.'filter_group_k2', 'filter_group_k2', '', 'string');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
	
		$query = "SELECT juser.*, k2user.group, k2group.name as groupname FROM #__users as juser ".
		"LEFT JOIN #__k2_users as k2user ON juser.id=k2user.userID ".
		"LEFT JOIN #__k2_user_groups as k2group ON k2user.group=k2group.id ".
		" WHERE juser.id>0";
		
		if ($filter_status > -1) {
			$query .= " AND juser.block = {$filter_status}";
		}
	
		if ($filter_group) {
			switch($filter_group)
			{
				case 'Public Frontend':
				$query .= " AND juser.usertype IN ('Registered', 'Author', 'Editor', 'Publisher')";
				break;
			
				case 'Public Backend':
				$query .= " AND juser.usertype IN ('Manager', 'Administrator', 'Super Administrator')";
				break;
			
				default:
				$filter_group=strtolower(trim($filter_group));
				$query .= " AND juser.usertype = '{$filter_group}'";
			}
		}
		
		if ($filter_group_k2) {
			$query .= " AND k2user.group = '{$filter_group_k2}'";
		}
		
		if ($search) {
			$query .= " AND LOWER( juser.name ) LIKE ".$db->Quote('%'.$search.'%');
		}
	
		if (!$filter_order) {
			$filter_order = "juser.name";
		}
	
		$query .= " ORDER BY {$filter_order} {$filter_order_Dir}";
	
		$db->setQuery($query, $limitstart, $limit);
		$rows = $db->loadObjectList();
		return $rows;
	}

	function getTotal() {
	
		global $mainframe;
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = & JFactory::getDBO();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
		$filter_status = $mainframe->getUserStateFromRequest($option.$view.'filter_status', 'filter_status', -1, 'int');
		$filter_group = $mainframe->getUserStateFromRequest($option.$view.'filter_group', 'filter_group', '', 'string');
		$filter_group_k2 = $mainframe->getUserStateFromRequest($option.$view.'filter_group_k2', 'filter_group_k2', '', 'string');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
	
		$query = "SELECT COUNT(juser.id) FROM #__users as juser ".
		"LEFT JOIN #__k2_users as k2user ON juser.id=k2user.userID ".
		"LEFT JOIN #__k2_user_groups as k2group ON k2user.group=k2group.id ".
		" WHERE juser.id>0";
	
		if ($filter_status > -1) {
			$query .= " AND juser.block = {$filter_status}";
		}
	
		if ($filter_group) {
			switch($filter_group)
			{
				case 'Public Frontend':
				$query .= " AND juser.usertype IN ('Registered', 'Author', 'Editor', 'Publisher')";
				break;
			
				case 'Public Backend':
				$query .= " AND juser.usertype IN ('Manager', 'Administrator', 'Super Administrator')";
				break;
			
				default:
				$filter_group=strtolower(trim($filter_group));
				$query .= " AND juser.usertype = '{$filter_group}'";
			}
		}
		
		if ($filter_group_k2) {
			$query .= " AND k2user.group = '{$filter_group_k2}'";
		}
		
		if ($search) {
			$query .= " AND LOWER( juser.name ) LIKE ".$db->Quote('%'.$search.'%');
		}
		
		$query.= " GROUP BY juser.id";
		
		$db->setQuery($query);
		$total = $db->loadResult();
		return $total;
	}

	function remove() {
	
		global $mainframe;
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('K2User', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->delete($id);
		}
		$cache = & JFactory::getCache('com_k2');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_k2&view=users', JText::_('User profile deleted'));
	}
	
	function getUserGroups($type='joomla'){
		
		$db = & JFactory::getDBO();
		
		if ($type=='joomla'){
			
			$query = 'SELECT (lft - 3) AS lft, name AS value, name AS text'
				. ' FROM #__core_acl_aro_groups'
				. ' WHERE name != "ROOT"'
				. ' AND name != "USERS"'
				. ' ORDER BY `lft` ASC'
			;
			$db->setQuery( $query );
			$groups = $db->loadObjectList();
			$userGroups = array();
	
			foreach ($groups as $group) {
				if ($group->lft >= 10) $group->lft = (int)$group->lft - 10;
				$group->text = $this->indent($group->lft).$group->text;
				array_push($userGroups,$group);
			}
			
		}
		else {
			$query = "SELECT * FROM #__k2_user_groups";
			$db->setQuery($query);
			$userGroups = $db->loadObjectList();
			
		}

		return $userGroups;
	}
	
	function indent($times, $char = '&nbsp;&nbsp;&nbsp;&nbsp;', $start_char = '', $end_char = '') {
		$return = $start_char;
		for ($i = 0; $i < $times; $i++) $return .= $char;
		$return .= $end_char;
		return $return;
	}
	
	function checkLogin($id){
		
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(s.userid) FROM #__session AS s WHERE s.userid = {$id}";
		$db->setQuery( $query );
		$result = $db->loadResult();
		return $result;
	}
	
	function hasProfile($id){
		
		$db = & JFactory::getDBO();
		$query = "SELECT id FROM #__k2_users WHERE userID = {$id}";
		$db->setQuery( $query );
		$result = $db->loadResult();
		return $result;
	}

}
