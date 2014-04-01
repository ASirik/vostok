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

class JElementTag extends JElement
{

	var $_name = 'Tag';

	function fetchElement($name, $value, & $node, $control_name)
	{
	
		global $mainframe;
	
		$db = & JFactory::getDBO();
		$doc = & JFactory::getDocument();
		$fieldName = $control_name.'['.$name.']';
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');
		$tag = & JTable::getInstance('K2Tag', 'Table');
	
		if ($value) {
			
			$db = &JFactory::getDBO();
			$query = "SELECT * FROM #__k2_tags WHERE name=".$db->Quote($value);
			$db->setQuery( $query );
			$tag = $db->loadObject();

		}
		else {
			$tag->name = JText::_('Select a tag');
		}
	
		$js = "
		function jSelectTag(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			document.getElementById('sbox-window').close();
		}
		";
		
		$doc->addScriptDeclaration($js);
	
		$link = 'index.php?option=com_k2&amp;view=tags&amp;task=element&amp;tmpl=component&amp;object='.$name;
	
		JHTML::_('behavior.modal', 'a.modal');
	
		$html = '
		<div style="float:left;">
			<input style="background:#fff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($tag->name, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />
		</div>
		<div class="button2-left">
			<div class="blank">
				<a class="modal" title="'.JText::_('Select a tag').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 700, y: 450}}">'.JText::_('Select').'</a>
			</div>
		</div>
		<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.$value.'" />
		';
	
		return $html;
	}

}

