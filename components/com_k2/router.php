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

function K2BuildRoute( & $query) {

	$segments = array ();
	
	$menu = & JSite::getMenu();
	if ( empty($query['Itemid'])) {
		$menuItem = & $menu->getActive();
	}
	else {
		$menuItem = & $menu->getItem($query['Itemid']);
	}
	$mView = ( empty($menuItem->query['view']))?null:$menuItem->query['view'];
	$mTask = ( empty($menuItem->query['task']))?null:$menuItem->query['task'];
	$mId = ( empty($menuItem->query['id']))?null:$menuItem->query['id'];
	$mTag = ( empty($menuItem->query['tag']))?null:$menuItem->query['tag'];

	if ( $mView == @$query['view'] && $mTask == @$query['task'] && $mId == @intval($query['id']) &&  @intval($query['id']) > 0 ) {
		unset ($query['view']);
		unset ($query['task']);
		unset ($query['id']);
	}
	
	if ( $mView == @$query['view'] && $mTask == @$query['task'] && $mTag == @$query['tag'] && isset($query['tag']) ) {
		unset ($query['view']);
		unset ($query['task']);
		unset ($query['tag']);
	}

	if ( isset ($query['view'])) {
		$view = $query['view'];
		$segments[] = $view;
		unset ($query['view']);
	}

	if (@ isset ($query['task'])) {
		$task = $query['task'];
		$segments[] = $task;
		unset ($query['task']);
	}

	if ( isset ($query['id'])) {
		$id = $query['id'];
		$segments[] = $id;
		unset ($query['id']);
	}

	if ( isset ($query['cid'])) {
		$cid = $query['cid'];
		$segments[] = $cid;
		unset ($query['cid']);
	}

	if ( isset ($query['tag'])) {
		$tag = $query['tag'];
		$segments[] = $tag;
		unset ($query['tag']);
	}

	if ( isset ($query['year'])) {
		$year = $query['year'];
		$segments[] = $year;
		unset ($query['year']);
	}

	if ( isset ($query['month'])) {
		$month = $query['month'];
		$segments[] = $month;
		unset ($query['month']);
	}

	if ( isset ($query['day'])) {
		$day = $query['day'];
		$segments[] = $day;
		unset ($query['day']);
	}
	
	if ( isset ($query['task'])) {
		$task = $query['task'];
		$segments[] = $task;
		unset ($query['task']);
	}
	
	return $segments;
}

function K2ParseRoute($segments) {
	$vars = array ();
	$vars['view'] = $segments[0];
	if (!isset($segments[1]))
		$segments[1]='';
	$vars['task'] = $segments[1];

	if ($segments[0] == 'itemlist') {
	
		switch($segments[1]) {
		
			case 'category':
			$vars['id'] = $segments[2];
			break;
		
			case 'tag':
			if (isset($segments[2]))
				$vars['tag'] = $segments[2];
			break;
			
			case 'user':
			if (isset($segments[2]))
				$vars['id'] = $segments[2];
			break;
		
			case 'date':
			if (isset($segments[2]))
				$vars['year'] = $segments[2];
			if (isset($segments[3]))
				$vars['month'] = $segments[3];
			if (isset($segments[4])) {
				$vars['day'] = $segments[4];
			}
			break;
			
		}
	
	}

	else if ($segments[0] == 'item') {
	
		switch($segments[1]) {
		
			case 'edit':
			$vars['cid'] = $segments[2];
			break;
		
			case 'download':
			$vars['id'] = $segments[2];
			break;
		
			default:
			$vars['id'] = $segments[1];
			break;
		
		}
	
	}
	
	return $vars;
}
