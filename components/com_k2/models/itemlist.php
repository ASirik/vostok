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

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class K2ModelItemlist extends JModel {

    function getData($ordering = NULL) {
    
        $user = &JFactory::getUser();
        $aid = $user->get('aid');
        $db = &JFactory::getDBO();
        $params = &JComponentHelper::getParams('com_k2');
        $limitstart = JRequest::getInt('limitstart');
        $limit = JRequest::getInt('limit');
        $task = JRequest::getCmd('task');
        
        $jnow = &JFactory::getDate();
        $now = $jnow->toMySQL();
        $nullDate = $db->getNullDate();
        
        if (JRequest::getWord('format') == 'feed')
            $limit = $params->get('feedLimit');
            
        $query = "SELECT i.*, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams FROM #__k2_items as i"." LEFT JOIN #__k2_categories AS c ON c.id = i.catid";
        
        if ($task == 'tag')
            $query .= " LEFT JOIN #__k2_tags_xref AS tags_xref ON tags_xref.itemID = i.id LEFT JOIN #__k2_tags AS tags ON tags.id = tags_xref.tagID";
            
        $query .= " WHERE i.published = 1"." AND i.access <= {$aid}"." AND i.trash = 0"." AND c.published = 1"." AND c.access <= {$aid}"." AND c.trash = 0";
        
        $query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )";
        $query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";
        
        //Build query depending on task
        switch ($task) {
        
            case 'category':
                $id = JRequest::getInt('id');
                
                $category = &JTable::getInstance('K2Category', 'Table');
                $category->load($id);
                $cparams = new JParameter($category->params);
                
                if ($cparams->get('inheritFrom')) {
                
                    $parent = &JTable::getInstance('K2Category', 'Table');
                    $parent->load($cparams->get('inheritFrom'));
                    $cparams = new JParameter($parent->params);
                }
                
                if ($cparams->get('catCatalogMode')) {
                    $query .= " AND c.id={$id} ";
                } else {
                    $categories = K2ModelItemlist::getCategoryChilds($id);
                    $categories[] = $id;
                    $categories = @array_unique($categories);
                    $sql = @implode(',', $categories);
                    $query .= " AND c.id IN ({$sql})";
                }

                
                break;
                
            case 'user':
                $id = JRequest::getInt('id');
                $query .= " AND i.created_by={$id} AND i.created_by_alias=''";
                break;
                
            case 'search':
                $badchars = array('#', '>', '<', '\\');
                $search = trim(str_replace($badchars, '', JRequest::getString('searchword', null)));
                if (! empty($search)) {
                    $sql = K2ModelItemlist::prepareSearch($search);
                    if (! empty($sql)) {
                        $query .= $sql;
                    } else {
                        $rows = array();
                        return $rows;
                    }
                }
                break;
                
            case 'date':
                if ((JRequest::getInt('month')) && (JRequest::getInt('year'))) {
                    $month = JRequest::getInt('month');
                    $year = JRequest::getInt('year');
                    $query .= " AND MONTH(i.created) = {$month} AND YEAR(i.created)={$year} ";
                    if (JRequest::getInt('day')) {
                        $day = JRequest::getInt('day');
                        $query .= " AND DAY(i.created) = {$day}";
                    }
					
                    if (JRequest::getInt('catid')) {
                        $catid = JRequest::getInt('catid');
                        $query .= " AND i.catid={$catid}";
                    }
					
                }
                break;
                
            case 'tag':
                $tag = JRequest::getString('tag');
                jimport('joomla.filesystem.file');
                if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish') && $task == 'tag') {
                
                    $registry = &JFactory::getConfig();
                    $lang = $registry->getValue("config.jflang");
                    
                    $sql = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
                    $sql .= " WHERE jfc.value = ".$db->Quote($tag);
                    $sql .= " AND jfc.reference_table = 'k2_tags'";
                    $sql .= " AND jfc.reference_field = 'name' AND jfc.published=1";
                    
                    $db->setQuery($sql, 0, 1);
                    $result = $db->loadResult();
                    
                }
                
                if (isset($result) && $result > 0) {
                    $query .= " AND (tags.id) = {$result}";
                } else {
                    $query .= " AND (tags.name) = ".$db->Quote($tag);
                }
                
                $categories = $params->get('categoriesFilter', NULL);
                if (is_array($categories))
                    $query .= " AND i.catid IN(".implode(',', $categories).")";
                if (is_string($categories))
                    $query .= " AND i.catid = {$categories}";
                break;
                
            default:
                $searchIDs = $params->get('categories');
                
                if (is_array($searchIDs) && count($searchIDs)) {
                
                    if ($params->get('catCatalogMode')) {
                        $sql = @implode(',', $searchIDs);
                        $query .= " AND i.catid IN ({$sql})";
                    } else {
                        foreach ($searchIDs as $catid) {
                            $categories = K2ModelItemlist::getCategoryChilds($catid);
                            foreach ($categories as $child) {
                                $childIDs[] = $child;
                            }
                        }
                        
                        $allIDs = @array_merge($searchIDs, $childIDs);
                        $result = @array_unique($allIDs);
                        if (! empty($result)) {
                            $sql = @implode(',', $result);
                            $query .= " AND i.catid IN ({$sql})";
                        }
                    }
                }
                
                break;
        }
        
        //Set featured flag
        if ($task == 'category' || empty($task)) {
            if (JRequest::getInt('featured') == '0') {
                $query .= " AND i.featured != 1";
            } else if (JRequest::getInt('featured') == '2') {
                $query .= " AND i.featured = 1";
            }
        }
        
        //Remove duplicates
        $query .= " GROUP BY i.id";
        
        //Set ordering
        switch ($ordering) {
        
            case 'date':
                $orderby = 'i.created ASC';
                break;
                
            case 'rdate':
                $orderby = 'i.created DESC';
                break;
                
            case 'alpha':
                $orderby = 'i.title';
                break;
                
            case 'ralpha':
                $orderby = 'i.title DESC';
                break;
                
            case 'order':
                if (JRequest::getInt('featured') == '2')
                    $orderby = 'i.featured_ordering';
                else
                    $orderby = 'i.ordering';
                break;
                
            default:
                $orderby = 'i.id DESC';
                break;
        }
        
        $query .= " ORDER BY ".$orderby;
        $db->setQuery($query, $limitstart, $limit);
        $rows = $db->loadObjectList();
        return $rows;
    }
    
    function getTotal() {
    
        $user = &JFactory::getUser();
        $aid = $user->get('aid');
        $db = &JFactory::getDBO();
        $params = &JComponentHelper::getParams('com_k2');
        $task = JRequest::getCmd('task');
        
        $jnow = &JFactory::getDate();
        $now = $jnow->toMySQL();
        $nullDate = $db->getNullDate();
        
        $query = "SELECT COUNT(*) FROM #__k2_items as i"." LEFT JOIN #__k2_categories c ON c.id = i.catid";
        
        if ($task == 'tag')
            $query .= " LEFT JOIN #__k2_tags_xref tags_xref ON tags_xref.itemID = i.id LEFT JOIN #__k2_tags tags ON tags.id = tags_xref.tagID";
            
        $query .= " WHERE i.published = 1"." AND i.access <= {$aid}"." AND i.trash = 0"." AND c.published = 1"." AND c.access <= {$aid}"." AND c.trash = 0";
        
        $query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )";
        $query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";
        
        //Build query depending on task
        switch ($task) {
        
            case 'category':
                $id = JRequest::getInt('id');
                
                $category = &JTable::getInstance('K2Category', 'Table');
                $category->load($id);
                $cparams = new JParameter($category->params);
                
                if ($cparams->get('inheritFrom')) {
                
                    $parent = &JTable::getInstance('K2Category', 'Table');
                    $parent->load($cparams->get('inheritFrom'));
                    $cparams = new JParameter($parent->params);
                }
                
                if ($cparams->get('catCatalogMode')) {
                    $query .= " AND c.id={$id} ";
                } else {
                    $categories = K2ModelItemlist::getCategoryChilds($id);
                    $categories[] = $id;
                    $categories = @array_unique($categories);
                    $sql = @implode(',', $categories);
                    $query .= " AND c.id IN ({$sql})";
                }

                
                break;

                
            
            case 'user':
                $id = JRequest::getInt('id');
                $query .= " AND i.created_by={$id} AND i.created_by_alias=''";
                break;
                
            case 'search':
                $badchars = array('#', '>', '<', '\\');
                $search = trim(str_replace($badchars, '', JRequest::getString('searchword', null)));
                if (! empty($search)) {
                    $sql = K2ModelItemlist::prepareSearch($search);
                    if (! empty($sql)) {
                        $query .= $sql;
                    } else {
                        $result = 0;
                        return $result;
                    }
                }
                break;
                
            case 'date':
                if ((JRequest::getInt('month')) && (JRequest::getInt('year'))) {
                    $month = JRequest::getInt('month');
                    $year = JRequest::getInt('year');
                    $query .= " AND MONTH(i.created) = {$month} AND YEAR(i.created)={$year} ";
                    if (JRequest::getInt('day')) {
                        $day = JRequest::getInt('day');
                        $query .= " AND DAY(i.created) = {$day}";
                    }
					
                    if (JRequest::getInt('catid')) {
                        $catid = JRequest::getInt('catid');
                        $query .= " AND i.catid={$catid}";
                    }
					
                }
                break;
                
            case 'tag':
                $tag = JRequest::getString('tag');
                jimport('joomla.filesystem.file');
                if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish') && $task == 'tag') {
                
                    $registry = &JFactory::getConfig();
                    $lang = $registry->getValue("config.jflang");
                    
                    $sql = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
                    $sql .= " WHERE jfc.value = ".$db->Quote($tag);
                    $sql .= " AND jfc.reference_table = 'k2_tags'";
                    $sql .= " AND jfc.reference_field = 'name' AND jfc.published=1";
                    
                    $db->setQuery($sql, 0, 1);
                    $result = $db->loadResult();
                    
                }
                
                if (isset($result) && $result > 0) {
                    $query .= " AND (tags.id) = {$result}";
                } else {
                    $query .= " AND (tags.name) = ".$db->Quote($tag);
                }
                $categories = $params->get('categoriesFilter', NULL);
                if (is_array($categories))
                    $query .= " AND i.catid IN(".implode(',', $categories).")";
                if (is_string($categories))
                    $query .= " AND i.catid = {$categories}";
                break;
                
            default:
                $searchIDs = $params->get('categories');
                
                if (is_array($searchIDs) && count($searchIDs)) {
                
                    if ($params->get('catCatalogMode')) {
                        $sql = @implode(',', $searchIDs);
                        $query .= " AND i.catid IN ({$sql})";
                    } else {
                        foreach ($searchIDs as $catid) {
                            $categories = K2ModelItemlist::getCategoryChilds($catid);
                            foreach ($categories as $child) {
                                $childIDs[] = $child;
                            }
                        }
                        
                        $allIDs = @array_merge($searchIDs, $childIDs);
                        $result = @array_unique($allIDs);
                        if (! empty($result)) {
                            $sql = @implode(',', $result);
                            $query .= " AND i.catid IN ({$sql})";
                        }
                    }
                }
                
                break;
        }
        
        //Set featured flag
        if ($task == 'category' || empty($task)) {
            if (JRequest::getVar('featured') == '0') {
                $query .= " AND i.featured != 1";
            } else if (JRequest::getVar('featured') == '2') {
                $query .= " AND i.featured = 1";
            }
        }
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }
    
    function getCategoryChilds($catid) {
    
        static $array = array();
        $user = &JFactory::getUser();
        $aid = $user->get('aid');
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0 AND access<={$aid} ORDER BY ordering ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        foreach ($rows as $row) {
            array_push($array, $row->id);
            if (K2ModelItemlist::hasChilds($row->id)) {
                K2ModelItemlist::getCategoryChilds($row->id);
            }
        }
        return $array;
    }
    
    function hasChilds($id) {
    
        $user = &JFactory::getUser();
        $aid = $user->get('aid');
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM #__k2_categories WHERE parent={$id} AND published=1 AND trash=0 AND access<={$aid} ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        if (count($rows)) {
            return true;
        } else {
            return false;
        }
    }
    
    function getCategoryFirstChilds($catid, $ordering = NULL) {
    
        $user = &JFactory::getUser();
        $aid = $user->get('aid');
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0 AND access<={$aid} ";
        
        switch ($ordering) {
        
            case 'order':
                $order = " ordering ASC";
                break;
                
            case 'alpha':
                $order = " name ASC";
                break;
                
            case 'ralpha':
                $order = " name DESC";
                break;
                
            case 'reversedefault':
                $order = " id DESC";
                break;
                
            default:
                $order = " id ASC";
                break;
                
        }
        
        $query .= " ORDER BY {$order}";
        
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        
        return $rows;
    }
    
    function countCategoryItems($id) {
    
        $user = &JFactory::getUser();
        $aid = $user->get('aid');
        $db = &JFactory::getDBO();
        
        $jnow = &JFactory::getDate();
        $now = $jnow->toMySQL();
        $nullDate = $db->getNullDate();
        
        $catIDs[] = $id;
        $categories = K2ModelItemlist::getCategoryChilds($id);
        foreach ($categories as $child) {
            $catIDs[] = $child;
        }
        $total = 0;
        foreach ($catIDs as $catid) {
            $query = "SELECT COUNT(*) FROM #__k2_items WHERE catid={$catid} AND published=1 AND trash=0 AND access<=".$aid;
            $query .= " AND ( publish_up = ".$db->Quote($nullDate)." OR publish_up <= ".$db->Quote($now)." )";
            $query .= " AND ( publish_down = ".$db->Quote($nullDate)." OR publish_down >= ".$db->Quote($now)." )";
            $db->setQuery($query);
            $total += $db->loadResult();
            
        }
        return $total;
    }
    
    function getUserProfile($id = NULL) {
    
        $db = &JFactory::getDBO();
        if (is_null($id))
            $id = JRequest::getInt('id');
        $query = "SELECT id, gender, description, image, url, `group`, plugins FROM #__k2_users WHERE userID={$id}";
        $db->setQuery($query);
        $row = $db->loadObject();
        return $row;
    }
    
    function getAuthorLatest($itemID, $limit, $userID) {
    
        $user = &JFactory::getUser();
        $aid = $user->get('aid');
        $db = &JFactory::getDBO();
        
        $jnow = &JFactory::getDate();
        $now = $jnow->toMySQL();
        $nullDate = $db->getNullDate();
        
        $query = "SELECT i.*, c.alias as categoryalias FROM #__k2_items as i"." LEFT JOIN #__k2_categories c ON c.id = i.catid"." WHERE i.id != {$itemID}"." AND i.published = 1"." AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )"." AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )"." AND i.access <= {$aid}"." AND i.trash = 0"." AND i.created_by = {$userID}"." AND i.created_by_alias=''"." AND c.published = 1"." AND c.access <= {$aid}"." AND c.trash = 0"." ORDER BY i.created DESC";
        
        $db->setQuery($query, 0, $limit);
        $rows = $db->loadObjectList();
        return $rows;
    }
    
    function getRelatedItems($itemID, $tags, $limit) {
    
        foreach ($tags as $tag) {
            $tagIDs[] = $tag->id;
        }
        $sql = implode(',', $tagIDs);
        
        $user = &JFactory::getUser();
        $aid = $user->get('aid');
        $db = &JFactory::getDBO();
        
        $jnow = &JFactory::getDate();
        $now = $jnow->toMySQL();
        $nullDate = $db->getNullDate();
        
        $query = "SELECT i.*, c.alias as categoryalias FROM #__k2_items as i"." LEFT JOIN #__k2_categories c ON c.id = i.catid"." LEFT JOIN #__k2_tags_xref tags_xref ON tags_xref.itemID = i.id"." WHERE i.id != {$itemID}"." AND i.published = 1"." AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )"." AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )"." AND i.access <= {$aid}"." AND i.trash = 0"." AND c.published = 1"." AND c.access <= {$aid}"." AND c.trash = 0"." AND (tags_xref.tagID) IN ({$sql})"." GROUP BY i.id"." ORDER BY i.created DESC";
        
        $db->setQuery($query, 0, $limit);
        $rows = $db->loadObjectList();
        return $rows;
    }
    
    function prepareSearch($search) {
    
        jimport('joomla.filesystem.file');
        $db = &JFactory::getDBO();
        $language = &JFactory::getLanguage();
        $defaultLang = $language->getDefault();
        $currentLang = $language->getTag();
        $conditions = array();
        $search_ignore = array();
        $sql = '';
        
        $ignoreFile = $language->getLanguagePath().DS.$currentLang.DS.$currentLang.'.ignore.php';
        
        if (JFile::exists($ignoreFile)) {
            include $ignoreFile;
        }
        $length = JString::strlen($search);
        
        if (JString::substr($search, 0, 1) == '"' && JString::substr($search, $length - 1, 1) == '"') {
        
            $word = JString::substr($search, 1, $length - 2);
            
            if (JString::strlen($word) > 3 && !in_array($word, $search_ignore)) {
                $word = $db->Quote('%'.$db->getEscaped($word, true).'%', false);
                
                if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish') && $currentLang != $defaultLang) {
                
                    $jfQuery = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
                    $jfQuery .= " WHERE jfc.reference_table = 'k2_items'";
                    $jfQuery .= " AND jfl.code=".$db->Quote($currentLang);
                    $jfQuery .= " AND jfc.published=1";
                    $jfQuery .= " AND jfc.value LIKE ".$word;
                    $jfQuery .= " AND (jfc.reference_field = 'title' 
								OR jfc.reference_field = 'introtext'
								OR jfc.reference_field = 'fulltext'
								OR jfc.reference_field = 'image_caption'
								OR jfc.reference_field = 'image_credits'
								OR jfc.reference_field = 'video_caption'
								OR jfc.reference_field = 'video_credits'
								OR jfc.reference_field = 'extra_fields_search'
								OR jfc.reference_field = 'metadesc'
								OR jfc.reference_field = 'metakey'
					)";
                    $db->setQuery($jfQuery);
                    $result = $db->loadResultArray();
                    $result = @array_unique($result);
                    if (count($result)) {
                        $conditions[] = "i.id IN(".implode(',', $result).")";
                    }
                    
                } else {
                    $conditions[] = 'i.title LIKE '.$word;
                    $conditions[] = 'i.introtext LIKE '.$word;
                    $conditions[] = 'i.fulltext LIKE '.$word;
                    $conditions[] = 'i.image_caption LIKE '.$word;
                    $conditions[] = 'i.image_credits LIKE '.$word;
                    $conditions[] = 'i.video_caption LIKE '.$word;
                    $conditions[] = 'i.video_credits LIKE '.$word;
                    $conditions[] = 'i.extra_fields_search LIKE '.$word;
                    $conditions[] = 'i.metadesc LIKE '.$word;
                    $conditions[] = 'i.metakey LIKE '.$word;
                }
                
            }
            
        } else {
            $search = explode(' ', JString::strtolower($search));
            foreach ($search as $searchword) {
            
                if (JString::strlen($searchword) > 3 && !in_array($searchword, $search_ignore)) {
                
                    $word = $db->Quote('%'.$db->getEscaped($searchword, true).'%', false);
                    
                    if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish') && $currentLang != $defaultLang) {
                    
                        $jfQuery = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
                        $jfQuery .= " WHERE jfc.reference_table = 'k2_items'";
                        $jfQuery .= " AND jfl.code=".$db->Quote($currentLang);
                        $jfQuery .= " AND jfc.published=1";
                        $jfQuery .= " AND jfc.value LIKE ".$word;
                        $jfQuery .= " AND (jfc.reference_field = 'title' 
									OR jfc.reference_field = 'introtext'
									OR jfc.reference_field = 'fulltext'
									OR jfc.reference_field = 'image_caption'
									OR jfc.reference_field = 'image_credits'
									OR jfc.reference_field = 'video_caption'
									OR jfc.reference_field = 'video_credits'
									OR jfc.reference_field = 'extra_fields_search'
									OR jfc.reference_field = 'metadesc'
									OR jfc.reference_field = 'metakey'
						)";
                        $db->setQuery($jfQuery);
                        $result = $db->loadResultArray();
                        $result = @array_unique($result);
                        foreach ($result as $id) {
                            $allIDs[] = $id;
                        }

                        
                    } else {
                    
                        $conditions[] = 'i.title LIKE '.$word;
                        $conditions[] = 'i.introtext LIKE '.$word;
                        $conditions[] = 'i.fulltext LIKE '.$word;
                        $conditions[] = 'i.image_caption LIKE '.$word;
                        $conditions[] = 'i.image_credits LIKE '.$word;
                        $conditions[] = 'i.video_caption LIKE '.$word;
                        $conditions[] = 'i.video_credits LIKE '.$word;
                        $conditions[] = 'i.extra_fields_search LIKE '.$word;
                        $conditions[] = 'i.metadesc LIKE '.$word;
                        $conditions[] = 'i.metakey LIKE '.$word;
                    }
                    
                    if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish') && $currentLang != $defaultLang) {
                    
                        if (isset($allIDs) && count($allIDs)) {
                            $conditions[] = "i.id IN(".implode(',', $allIDs).")";
                        }
                        
                    }

                    
                }
                
            }

            
        }
        
        if (count($conditions)) {
            $sql = " AND (".implode(" OR ", $conditions).")";
        }
        return $sql;
    }
    
}
