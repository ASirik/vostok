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

jimport( 'joomla.html.parameter' );

/**
 * Parameter handler
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */
class K2Parameter extends JParameter
{
	
	/**
	* optional namespace
	*
	* @access	private
	* @var		array
	* @since	1.5
	*/
	var $namespace = null;
	
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	string The raw parms text
	 * @param	string Path to the xml setup file
	 * @param	string Namespace to the xml setup file
	 * @since	1.5
	 */
	function __construct($data, $path = '', $namespace)
	{
		parent::__construct('_default');

		// Set base path
		$this->_elementPath[] = JPATH_COMPONENT_ADMINISTRATOR.DS.'elements';

		if (trim( $data )) {
			$this->loadINI($data);
		}

		if ($path) {
			$this->loadSetupFile($path);
		}
		
		if ($namespace) {
			$this->namespace = $namespace;
		}

		$this->_raw = $data;
	}

	/**
	 * Get a value
	 *
	 * @access	public
	 * @param	string The name of the param
	 * @param	mixed The default value if not found
	 * @return	string
	 * @since	1.5
	 */
	function get($key, $default = '', $group = '_default')
	{
		$value = $this->getValue($group.'.'.$this->namespace.$key);
		$result = (empty($value) && ($value !== 0) && ($value !== '0')) ? $default : $value;
		//if($group != '_default') { echo ($group); }
		return $result;
	}

	/**
	 * Render a parameter type
	 *
	 * @param	object	A param tag node
	 * @param	string	The control name
	 * @return	array	Any array of the label, the form element and the tooltip
	 * @since	1.5
	 */
	function getParam(&$node, $control_name = 'params', $group = '_default')
	{
		//get the type of the parameter
		$type = $node->attributes('type');

		//remove any occurance of a mos_ prefix
		$type = str_replace('mos_', '', $type);

		$element =& $this->loadElement($type);

		// error happened
		if ($element === false)
		{
			$result = array();
			$result[0] = $node->attributes('name');
			$result[1] = JText::_('Element not defined for type').' = '.$type;
			$result[5] = $result[0];
			return $result;
		}

		//get value
		$value	= $this->get($node->attributes('name'), $node->attributes('default'), $group);

		//set name
		$node->_attributes['name'] = $this->namespace.$node->_attributes['name'];

		return $element->render($node, $value, $control_name);
	}
}