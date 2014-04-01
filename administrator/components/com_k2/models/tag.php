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

class K2ModelTag extends JModel
{

	function getData() {
	
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('K2Tag', 'Table');
		$row->load($cid);
		return $row;
	}

	function save() {
	
		global $mainframe;
		$row = & JTable::getInstance('K2Tag', 'Table');
	
		if (!$row->bind(JRequest::get('post'))) {
			$mainframe->redirect('index.php?option=com_k2&view=tags', $row->getError(), 'error');
		}
	
		if (!$row->check()) {
			$mainframe->redirect('index.php?option=com_k2&view=tag&cid='.$row->id, $row->getError(), 'error');
		}
	
		if (!$row->store()) {
			$mainframe->redirect('index.php?option=com_k2&view=tags', $row->getError(), 'error');
		}

		$cache = & JFactory::getCache('com_k2');
		$cache->clean();

		switch(JRequest::getCmd('task')) {
			case 'apply':
			$msg = JText::_('Changes to Tag saved');
			$link = 'index.php?option=com_k2&view=tag&cid='.$row->id;
			break;
			case 'save':
			default:
			$msg = JText::_('Tag Saved');
			$link = 'index.php?option=com_k2&view=tags';
			break;
		}
		$mainframe->redirect($link, $msg);
	}
	
	function addTag(){
		
		global $mainframe;
		$tag=JRequest::getString('tag');
		
		$response = new JObject;
		$response->set('name',$tag);
		
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'JSON.php');
		$json=new Services_JSON;
		
		if (empty($tag)){
			$response->set('msg',JText::_('You need to enter a tag!',true));
			echo $json->encode($response);
			$mainframe->close();
		}
		
		$db = & JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_tags WHERE name=".$db->Quote($tag);
		$db->setQuery($query);
		$result = $db->loadResult();

		if ($result>0){
			$response->set('msg',JText::_('Tag already exists!',true));
			echo $json->encode($response);
			$mainframe->close();
		}
		
		$row = & JTable::getInstance('K2Tag', 'Table');
		$row->name=$tag;
		$row->published=1;
		$row->store();
		
		$cache = & JFactory::getCache('com_k2');
		$cache->clean();
		
		$response->set('id', $row->id);
		$response->set('status','success');
		$response->set('msg', JText::_('Tag added!',true));
		echo $json->encode($response);
		
		$mainframe->close();

	}

}
