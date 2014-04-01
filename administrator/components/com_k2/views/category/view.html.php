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

class K2ViewCategory extends JView
{

	function display($tpl = null)
	{
	
		JRequest::setVar('hidemainmenu', 1);
		$model = & $this->getModel();
		$category = $model->getData();
		if(!$category->id)
			$category->published=1;
		$this->assignRef('row', $category);
		$wysiwyg = & JFactory::getEditor();
		$editor = $wysiwyg->display('description', $category->description, '100%', '250', '40', '5', array('pagebreak', 'readmore'));
		$this->assignRef('editor', $editor);
		$lists = array ();
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $category->published);
		$lists['access'] = JHTML::_('list.accesslevel', $category);
		$query = 'SELECT ordering AS value, name AS text FROM #__k2_categories ORDER BY ordering';
		$lists['ordering'] = JHTML::_('list.specificordering', $category, $category->id, $query);
		$categories[] = JHTML::_('select.option', '0', JText::_('Top'));
		
		require_once (JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel = new K2ModelCategories;
		$tree=$categoriesModel->categoriesTree($category);
		$categories = array_merge($categories,$tree);
		$lists['parent'] = JHTML::_('select.genericlist', $categories, 'parent', 'class="inputbox"', 'value', 'text', $category->parent);
		
		require_once (JPATH_COMPONENT.DS.'models'.DS.'extrafields.php');
		$extraFieldsModel = new K2ModelExtraFields;
		$groups = $extraFieldsModel->getGroups();
		$group [] = JHTML::_ ( 'select.option', '0', JText::_ ( 'No Extra fields' ), 'id', 'name' );
		$group = array_merge ( $group, $groups );
		$lists['extraFieldsGroup'] = JHTML::_ ( 'select.genericlist', $group, 'extraFieldsGroup', 'class="inputbox" size="1" ', 'id', 'name', $category->extraFieldsGroup );
	
	
		JPluginHelper::importPlugin ( 'k2' );
		$dispatcher = &JDispatcher::getInstance ();
		$K2Plugins=$dispatcher->trigger('onRenderAdminForm', array ($category, 'category' ) );
		$this->assignRef('K2Plugins', $K2Plugins);
	
	
		$params = & JComponentHelper::getParams('com_k2');
		$this->assignRef('params', $params);
		
		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'category.xml');
		$form->loadINI($category->params);
		$this->assignRef('form', $form);
		
		$categories[0] = JHTML::_('select.option', '0', JText::_('None'));
		$lists['inheritFrom'] = JHTML::_('select.genericlist', $categories, 'params[inheritFrom]', 'class="inputbox"', 'value', 'text', $form->get('inheritFrom'));
		
		$this->assignRef('lists', $lists);
		
		JToolBarHelper::title(JText::_('Add/Edit Category'));
		JToolBarHelper::save();
		JToolBarHelper::custom('saveAndNew','save.png','save_f2.png',JText::_('Save &amp; New'), false);
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	
		parent::display($tpl);
	}

}
