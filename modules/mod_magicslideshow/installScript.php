<?php

/*------------------------------------------------------------------------
# mod_magicslideshow - Magic Slideshow for Joomla
# ------------------------------------------------------------------------
# Magic Toolbox
# Copyright 2011 MagicToolbox.com. All Rights Reserved.
# @license - http://www.opensource.org/licenses/artistic-license-2.0  Artistic License 2.0 (GPL compatible)
# Website: http://www.magictoolbox.com/magicslideshow/modules/joomla/
# Technical Support: http://www.magictoolbox.com/contact/
/*-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access.');


class mod_magicslideshowInstallerScript {
    /**
        * method to install the component
        *
        * @return void
        */
    function install($parent) {
        // $parent is the class calling this method
        $db = JFactory::getDBO();
        $query = "UPDATE `#__modules` SET `position`='position-7' WHERE `module` = 'mod_magicslideshow'";
        $db->setQuery($query);
        if (!$db->query()) {
            JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
            return false;
        }
        $query = "SELECT `id` FROM `#__modules` WHERE `module` = 'mod_magicslideshow'";
        $db->setQuery($query);
        if (!$db->query()) {
            JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
            return false;
        }
        $modId = $db->loadResult();
        if($modId) {
            $query = "INSERT INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES (".$modId.", 0)";
            $db->setQuery($query);
            if (!$db->query()) {
                JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
                return false;
            }
        }
        $this->sendStat('install');
        return true;
    }

    /**
        * method to uninstall the component
        *
        * @return void
        */
    function uninstall($parent) {
            // $parent is the class calling this method
            $this->sendStat('uninstall');
            return true;
    }

    /**
        * method to update the component
        *
        * @return void
        */
    function update($parent) {
            // $parent is the class calling this method
            return true;
    }

    /**
        * method to run before an install/update/uninstall method
        *
        * @return void
        */
    function preflight($type, $parent) {
            // $parent is the class calling this method
            // $type is the type of change (install, update or discover_install)
            return true;
    }

    /**
        * method to run after an install/update/uninstall method
        *
        * @return void
        */
    function postflight($type, $parent) {
            // $parent is the class calling this method
            // $type is the type of change (install, update or discover_install)
            return true;
    }

    function sendStat($action = '') {

        //NOTE: don't send from working copy
        if('working' == 'v2.20.5' || 'working' == 'v1.1.32') {
            return;
        }

        $hostname = 'www.magictoolbox.com';

        $url = $_SERVER['HTTP_HOST'].JURI::root(true);
        $url = urlencode(urldecode($url));

        if(class_exists('joomlaVersion')) {
            //old joomla, 1.0.x
            $versionObj = new joomlaVersion();
        } elseif(class_exists('JVersion')) {
            $versionObj = new JVersion();
        }
        $platformVersion = $versionObj->getShortVersion();

        $path = "api/stat/?action={$action}&tool_name=magicslideshow&license=trial&tool_version=v1.1.32&module_version=v2.20.5&platform_name=joomla16&platform_version={$platformVersion}&url={$url}";
        $handle = @fsockopen($hostname, 80, $errno, $errstr, 30);
        if($handle) {
            $headers  = "GET /{$path} HTTP/1.1\r\n";
            $headers .= "Host: {$hostname}\r\n";
            $headers .= "Connection: Close\r\n\r\n";
            fwrite($handle, $headers);
            fclose($handle);
        }

    }

}
