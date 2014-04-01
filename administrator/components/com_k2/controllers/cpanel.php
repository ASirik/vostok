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

jimport('joomla.application.component.controller');

class K2ControllerCpanel extends JController {

    function display() {
        JRequest::setVar('view', 'cpanel');
        parent::display();
    }
    
    function upgrade() {
    
        global $mainframe;
        $db = &JFactory::getDBO();
        $fields = $db->getTableFields('#__k2_items');
        if (array_key_exists('featured_ordering', $fields['#__k2_items'])) {
            $mainframe->redirect('index.php?option=com_k2', JText::_('Nothing to update! Database structure is correct.'));
        } else {
            $query = "ALTER TABLE #__k2_items ADD `featured_ordering` INT(11) NOT NULL default '0' AFTER `featured`";
            $db->setQuery($query);
            if ($db->query()) {
                $mainframe->redirect('index.php?option=com_k2', JText::_('Update completed successfully!'));
            } else {
                $mainframe->redirect('index.php?option=com_k2', JText::_('Update error').': '.$db->getErrorMsg());
            }
        }
        
    }
    
}
