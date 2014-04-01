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

require_once (JPATH_COMPONENT.DS.'helpers'.DS.'route'.'.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'permissions'.'.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'utilities'.'.php');

K2HelperPermissions::setPermissions();
K2HelperPermissions::checkPermissions();

$controller = JRequest::getWord('view', 'itemlist');

jimport('joomla.filesystem.file');

if (JFile::exists(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php')) {
    require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
    $classname = 'K2Controller'.$controller;
    $controller = new $classname();
    $controller->execute(JRequest::getWord('task'));
    $controller->redirect();
}

echo "\n<!-- JoomlaWorks \"K2\" (v2.1) | Learn more about K2 at http://k2.joomlaworks.gr -->\n\n";
