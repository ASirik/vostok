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

class K2ModelComments extends JModel {

  function getData() {
  
    global $mainframe;
    $option = JRequest::getCmd('option');
    $view = JRequest::getCmd('view');
    $db = &JFactory::getDBO();
    $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
    $limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
    $filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'c.id', 'cmd');
    $filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');
    $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
    $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
    $search = JString::strtolower($search);
    
    $query = "SELECT c.*, i.title , i.catid,  i.alias AS itemAlias, cat.alias AS catAlias FROM #__k2_comments AS c LEFT JOIN #__k2_items AS i ON c.itemID=i.id LEFT JOIN #__k2_categories AS cat ON cat.id=i.catid WHERE c.id>0";
    
    if ($filter_state > - 1) {
      $query .= " AND c.published={$filter_state}";
    }
    
    if ($search) {
      $query .= " AND LOWER( c.commentText ) LIKE ".$db->Quote('%'.$search.'%');
    }
    
    if (!$filter_order) {
      $filter_order = "c.commentDate";
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
    $db = &JFactory::getDBO();
    $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
    $limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
    $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', 1, 'int');
    $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
    $search = JString::strtolower($search);
    
    $query = "SELECT COUNT(*) FROM #__k2_comments WHERE id>0";
    
    if ($filter_state > - 1) {
      $query .= " AND published={$filter_state}";
    }
    
    if ($search) {
      $query .= " AND LOWER( commentText ) LIKE ".$db->Quote('%'.$search.'%');
    }
    
    $db->setQuery($query);
    $total = $db->loadresult();
    return $total;
  }
  
  function publish() {
  
    global $mainframe;
    $cid = JRequest::getVar('cid');
    $row = &JTable::getInstance('K2Comment', 'Table');
    foreach ($cid as $id) {
      $row->load($id);
      $row->publish($id, 1);
    }
    $cache = &JFactory::getCache('com_k2');
    $cache->clean();
    $mainframe->redirect('index.php?option=com_k2&view=comments');
  }
  
  function unpublish() {
  
    global $mainframe;
    $cid = JRequest::getVar('cid');
    $row = &JTable::getInstance('K2Comment', 'Table');
    foreach ($cid as $id) {
      $row->load($id);
      $row->publish($id, 0);
    }
    $cache = &JFactory::getCache('com_k2');
    $cache->clean();
    $mainframe->redirect('index.php?option=com_k2&view=comments');
  }
  
  function remove() {
  
    global $mainframe;
    $db = &JFactory::getDBO();
    $cid = JRequest::getVar('cid');
    $row = &JTable::getInstance('K2Comment', 'Table');
    foreach ($cid as $id) {
      $row->load($id);
      $row->delete($id);
    }
    $cache = &JFactory::getCache('com_k2');
    $cache->clean();
    $mainframe->redirect('index.php?option=com_k2&view=comments', JText::_('Delete Completed'));
  }
  
  function deleteUnpublished() {
  
    global $mainframe;
    $db = &JFactory::getDBO();
    $query = "DELETE FROM #__k2_comments WHERE published=0";
    $db->setQuery($query);
    $db->query();
    $cache = &JFactory::getCache('com_k2');
    $cache->clean();
    $mainframe->redirect('index.php?option=com_k2&view=comments', JText::_('Delete Completed'));
  }
  
}
