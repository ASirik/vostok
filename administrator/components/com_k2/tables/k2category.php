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

class TableK2Category extends JTable
{

	var $id = null;
	var $name = null;
	var $alias = null;
	var $description = null;
	var $parent = null;
	var $extraFieldsGroup = null;
	var $published = null;
	var $image = null;
	var $access = null;
	var $ordering = null;
	var $params = null;
	var $trash = null;
	var $plugins = null;

	function __construct( & $db) {
	
		parent::__construct('#__k2_categories', 'id', $db);
	}

	function check() {
	
		if (trim($this->name) == '') {
			$this->setError(JText::_('Category must have a name'));
			return false;
		}
		if ( empty($this->alias)) {
			$this->alias = $this->name;
		}
		
		mb_internal_encoding("UTF-8");
		mb_regex_encoding("UTF-8");
		$this->alias = trim(mb_strtolower($this->alias));
        $this->alias = str_replace('-', ' ', $this->alias);
        $this->alias = mb_ereg_replace('[[:space:]]+', ' ', $this->alias);
        $this->alias = trim(str_replace(' ', '-', $this->alias));
        $this->alias = str_replace('.', '', $this->alias);

       
        $stripthese = ',|~|!|@|%|^|(|)|<|>|:|;|{|}|[|]|&|`|â€ž|â€¹|â€™|â€˜|â€œ|â€�|â€¢|â€º|Â«|Â´|Â»|Â°|«|»|…';
        $strips = explode('|', $stripthese);
        foreach ($strips as $strip) {
            $this->alias = str_replace($strip, '', $this->alias);
        }

        
        $params = &JComponentHelper::getParams('com_k2');
        $SEFReplacements = array();
        $items = explode(',', $params->get('SEFReplacements'));
        foreach ($items as $item) {
            if (! empty($item)) {
                @list($src, $dst) = explode('|', trim($item));
                $SEFReplacements[trim($src)] = trim($dst);
            }
        }

        
        foreach ($SEFReplacements as $key=>$value) {
            $this->alias = str_replace($key, $value, $this->alias);
        }

        $this->alias = trim($this->alias, '-.');

        if (trim(str_replace('-', '', $this->alias)) == '') {
            $datenow = &JFactory::getDate();
            $this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
        }
        
        return true;

	}

	function bind($array, $ignore = '')	{
	
		if (key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		
		if (key_exists('plugins', $array) && is_array($array['plugins']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['plugins']);
			$array['plugins'] = $registry->toString();
		}
		
		return parent::bind($array, $ignore);
	}

}
