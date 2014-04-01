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
JHTML::_('behavior.tooltip');
$user = & JFactory::getUser();
$view = JRequest::getWord('view');
if (($user->gid <= 23) && ( 
		$view=='extraField' || 
		$view=='extraFields' || 
		$view=='extraFieldsGroup' || 
		$view=='extraFieldsGroups' || 
		$view=='user' || 
		$view=='users' || 
		$view=='userGroup' || 
		$view=='userGroups' ||
		$view=='info'
		)
	)
	{
		JError::raiseError( 403, JText::_("ALERTNOTAUTH") );	
	}

$document = & JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_k2/css/style.css');
$document->addCustomTag('
<!--[if IE 7]>
<link href="'.JURI::base().'components/com_k2/css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if lte IE 6]>
<link href="'.JURI::base().'components/com_k2/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
');

$controller = JRequest::getWord('view', 'cpanel');
require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
$classname = 'K2Controller'.$controller;
$controller = new $classname();
$controller->registerTask('saveAndNew', 'save');
$controller->execute(JRequest::getWord('task'));
$controller->redirect();

?>

<div id="k2AdminFooter">
	<?php echo JText::_('K2_COPYRIGHTS'); ?>
</div>
