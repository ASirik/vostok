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

class K2ViewItemlist extends JView {

    function display($tpl = null) {
    
        global $mainframe;
        $params = &JComponentHelper::getParams('com_k2');
        $model = &$this->getModel('itemlist');
        $limitstart = JRequest::getInt('limitstart');
        $view = JRequest::getWord('view');
        $task = JRequest::getWord('task');
        
        //Add link
        if (K2HelperPermissions::canAddItem())
            $addLink = JRoute::_('index.php?option=com_k2&view=item&task=add&tmpl=component');
        $this->assignRef('addLink', $addLink);
        
        //Get data depending on task
        switch ($task) {
        
            case 'category':
                //Get category
                $id = JRequest::getInt('id');
                JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
                $category = &JTable::getInstance('K2Category', 'Table');
                $category->load($id);
                
                //Access check
                $user = &JFactory::getUser();
                if ($category->access > $user->get('aid', 0)) {
                    JError::raiseError(403, JText::_("ALERTNOTAUTH"));
                }
                if (!$category->published || $category->trash) {
                    JError::raiseError(404, JText::_("Category not found"));
                }
                
                //Merge params
                $cparams = new JParameter($category->params);
                if ($cparams->get('inheritFrom')) {
                    $masterCategory = &JTable::getInstance('K2Category', 'Table');
                    $masterCategory->load($cparams->get('inheritFrom'));
                    $cparams = new JParameter($masterCategory->params);
                }
                $params->merge($cparams);
                
                //Category link
                $category->link = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($category->id.':'.urlencode($category->alias))));
                
                //Category image
                if (! empty($category->image)) {
                    $category->image = JURI::root().'media/k2/categories/'.$category->image;
                } else {
                    if ($params->get('catImageDefault')) {
                        $category->image = JURI::root().'components/com_k2/images/placeholder/category.png';
                    }
                }
                
                //Category K2 plugins
                $category->event->K2CategoryDisplay = '';
                $dispatcher = &JDispatcher::getInstance();
                JPluginHelper::importPlugin('k2');
                $results = $dispatcher->trigger('onK2CategoryDisplay', array(&$category, $params, $limitstart));
                $category->event->K2CategoryDisplay = trim(implode("\n", $results));
                
                $this->assignRef('category', $category);
                $this->assignRef('user', $user);
                
                //Category childs
                $ordering = $params->get('subCatOrdering');
                $childs = $model->getCategoryFirstChilds($id, $ordering);
                if (count($childs)) {
                    foreach ($childs as $child) {
                        if ($params->get('subCatTitleItemCounter'))
                            $child->numOfItems = $model->countCategoryItems($child->id);
                            
                        if (! empty($child->image)) {
                            $child->image = JURI::root().'media/k2/categories/'.$child->image;
                        } else {
                            if ($params->get('catImageDefault')) {
                                $child->image = JURI::root().'components/com_k2/images/placeholder/category.png';
                            }
                        }
                        
                        $child->link = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($child->id.':'.urlencode($child->alias))));
                        $subCategories[] = $child;
                        
                    }
                    $this->assignRef('subCategories', $subCategories);
                }
                
                //Set limit
                $limit = $params->get('num_leading_items') + $params->get('num_primary_items') + $params->get('num_secondary_items') + $params->get('num_links');
                
                //Set featured flag
                JRequest::setVar('featured', $params->get('catFeaturedItems'));
                
                //Set layout
                $this->setLayout('category');
                
                //Set title
                $title = $category->name;
                break;
                
            case 'user':
                //Get user
                $id = JRequest::getInt('id');
                $user = &JFactory::getUser($id);
                
                //Check user status
                if ($user->block) {
                    JError::raiseError(404, JText::_('User not found'));
                }
                
                //Get K2 user profile
                $user->profile = $model->getUserProfile();
                
                //User image
                $user->avatar = K2HelperUtilities::getAvatar($user->id, $user->email, $params->get('userImageWidth'));
                
                //User K2 plugins
                $user->event->K2UserDisplay = '';
                if (is_object($user->profile) && $user->profile->id > 0) {
                
                    $dispatcher = &JDispatcher::getInstance();
                    JPluginHelper::importPlugin('k2');
                    $results = $dispatcher->trigger('onK2UserDisplay', array(&$user->profile, $params, $limitstart));
                    $user->event->K2UserDisplay = trim(implode("\n", $results));
                    
                }

                
                $this->assignRef('user', $user);
                
                //Set layout
                $this->setLayout('user');
                
                //Set limit
                $limit = $params->get('userItemCount');
                
                //Set title
                $title = $user->name;
                
                break;
                
            case 'tag':
                //Set layout
                $this->setLayout('generic');
                
                //Set limit
                $limit = $params->get('genericItemCount');
                
                //set title
                $title = JText::_('Displaying items by tag:').' '.JRequest::getVar('tag');
                break;
                
            case 'search':
                //Set layout
                $this->setLayout('generic');
                
                //Set limit
                $limit = $params->get('genericItemCount');
                
                //Set title
                $title = JText::_('Search results for:').' '.JRequest::getVar('searchword');
                break;
                
            case 'date':
                //Set layout
                $this->setLayout('generic');
                
                //Set limit
                $limit = $params->get('genericItemCount');
                
                //Set title
                if (JRequest::getInt('day')) {
                    $date = strtotime(JRequest::getInt('year').'-'.JRequest::getInt('month').'-'.JRequest::getInt('day'));
                    $title = JText::_('Items filtered by date:').' '.JHTML::_('date', $date, '%A, %d %B %Y');
                } else {
                    $date = strtotime(JRequest::getInt('year').'-'.JRequest::getInt('month'));
                    $title = JText::_('Items filtered by date:').' '.JHTML::_('date', $date, '%B %Y');
                }
                break;
                
            default:
                //Set layout
                $this->setLayout('category');
                $user = &JFactory::getUser();
                $this->assignRef('user', $user);
                
                //Set limit
                $limit = $params->get('num_leading_items') + $params->get('num_primary_items') + $params->get('num_secondary_items') + $params->get('num_links');
                //Set featured flag
                JRequest::setVar('featured', $params->get('catFeaturedItems'));
                
                //Set title
                $title = $params->get('page_title');
                
                break;
                
        }
        
        //Set limit for model
        JRequest::setVar('limit', $limit);
        
        //Get ordering
        $ordering = $params->get('catOrdering');
        
        //Get items
        $items = $model->getData($ordering);
        
        //Pagination
        jimport('joomla.html.pagination');
        $total = $model->getTotal();
        $pagination = new JPagination($total, $limitstart, $limit);
        
        //Prepare items
		$user = &JFactory::getUser();
        $cache = &JFactory::getCache('com_k2_extended');
        $model = &$this->getModel('item');
        for ($i = 0; $i < sizeof($items); $i++) {
        
            //Item group
            if ($task == "category" || $task == "") {
                if ($i < ($params->get('num_links') + $params->get('num_leading_items') + $params->get('num_primary_items') + $params->get('num_secondary_items')))
                    $items[$i]->itemGroup = 'links';
                if ($i < ($params->get('num_secondary_items') + $params->get('num_leading_items') + $params->get('num_primary_items')))
                    $items[$i]->itemGroup = 'secondary';
                if ($i < ($params->get('num_primary_items') + $params->get('num_leading_items')))
                    $items[$i]->itemGroup = 'primary';
                if ($i < $params->get('num_leading_items'))
                    $items[$i]->itemGroup = 'leading';
            }
            if ($user->guest){
	            $hits = $items[$i]->hits;
	            $items[$i]->hits = 0;
	            $items[$i] = $cache->call(array('K2ModelItem', 'prepareItem'), $items[$i], $view, $task);
	            $items[$i]->hits = $hits;
            }
			else {
				$items[$i] = $model->prepareItem($items[$i], $view, $task);
			}

            
        }
        
        //Pathway
        $pathway = &$mainframe->getPathWay();
        $pathway->addItem($title);
        
        //Set title
        $document = &JFactory::getDocument();
        $menus = &JSite::getMenu();
        $menu = $menus->getActive();
        if (is_object($menu)) {
            $menu_params = new JParameter($menu->params);
            if (!$menu_params->get('page_title'))
                $params->set('page_title', $title);
        } else {
            $params->set('page_title', $title);
        }
        $document->setTitle($params->get('page_title'));
        
        //Feed link
		$config =& JFactory::getConfig();
		$menu = &JSite::getMenu();
		$default = $menu->getDefault();
		$active =  $menu->getActive();
		if (!is_null($active) && $active->id==$default->id && $config->getValue('config.sef')){
			$link = '&Itemid='.$active->id.'&format=feed&limitstart=';
		}
		else {
			$link = '&format=feed&limitstart=';
		}
        
        $feed = JRoute::_($link);
        $this->assignRef('feed', $feed);
        $attribs = array('type'=>'application/rss+xml', 'title'=>'RSS 2.0');
        $document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
        $attribs = array('type'=>'application/atom+xml', 'title'=>'Atom 1.0');
        $document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
        
        //Assign data
        if ($task == "category" || $task == "") {
            $leading = array_slice($items, 0, $params->get('num_leading_items'));
            $primary = array_slice($items, $params->get('num_leading_items'), $params->get('num_primary_items'));
            $secondary = array_slice($items, $params->get('num_leading_items') + $params->get('num_primary_items'), $params->get('num_secondary_items'));
            $links = array_slice($items, $params->get('num_leading_items') + $params->get('num_primary_items') + $params->get('num_secondary_items'), $params->get('num_links'));
            $this->assignRef('leading', $leading);
            $this->assignRef('primary', $primary);
            $this->assignRef('secondary', $secondary);
            $this->assignRef('links', $links);
        } else {
            $this->assignRef('items', $items);
        }
		
		//Set default values to avoid division by zero
		if ($params->get('num_leading_columns')==0)
       		$params->set('num_leading_columns',1);
		if ($params->get('num_primary_columns')==0)
       		$params->set('num_primary_columns',1);
		if ($params->get('num_secondary_columns')==0)
       		$params->set('num_secondary_columns',1);
		if ($params->get('num_links_columns')==0)
       		$params->set('num_links_columns',1);
			
        $this->assignRef('params', $params);
        $this->assignRef('pagination', $pagination);
        
        //Set template paths
        $this->_addPath('template', JPATH_COMPONENT.DS.'templates');
        $this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2'.DS.'templates');
        $this->_addPath('template', JPATH_COMPONENT.DS.'templates'.DS.'default');
        $this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2'.DS.'templates'.DS.'default');
        if ($params->get('theme')) {
            $this->_addPath('template', JPATH_COMPONENT.DS.'templates'.DS.$params->get('theme'));
            $this->_addPath('template', JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'com_k2'.DS.'templates'.DS.$params->get('theme'));
        }
        
        parent::display($tpl);
    }
    
}
