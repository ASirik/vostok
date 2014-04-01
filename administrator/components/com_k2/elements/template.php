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

class JElementTemplate extends JElement
{

	var $_name = 'template';

	function fetchElement($name, $value, & $node, $control_name) {
		
		jimport('joomla.filesystem.folder');
		$componentPath = JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'templates';
		$componentFolders = JFolder::folders($componentPath);
		$db =& JFactory::getDBO();
		$query = "SELECT template FROM #__templates_menu WHERE client_id = 0 AND menuid = 0";
		$db->setQuery($query);
		$defaultemplate = $db->loadResult();
		$templatePath = JPATH_SITE.DS.'templates'.DS.$defaultemplate.DS.'html'.DS.'com_k2'.DS.'templates';
		if (JFolder::exists($templatePath)){
			$templateFolders = JFolder::folders($templatePath);
			$folders = @array_merge($templateFolders, $componentFolders);
			$folders = @array_unique($folders);
		}
		else {
			$folders = $componentFolders;
		}

		$exclude = 'default';
		$options = array ();
		foreach ($folders as $folder) {
			if (preg_match(chr(1).$exclude.chr(1), $folder)) {	
				continue ;
			}
			$options[] = JHTML::_('select.option', $folder, $folder);
		}
		
		array_unshift($options, JHTML::_('select.option', '', '-- '.JText::_('Use default').' --'));
		
		return JHTML::_('select.genericlist', $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name);
	
	}

}
