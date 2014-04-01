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

class K2HelperUtilities {

		// Get user avatar
    function getAvatar($userID, $email = NULL, $width = 50) {
    
        $params = &JComponentHelper::getParams('com_k2');
        
        if ($userID == 'alias')
            $avatar = JURI::root().'components/com_k2/images/placeholder/user.png';
            
        else if ($userID == 0) {
            if ($params->get('gravatar') && !is_null($email)) {
                $avatar = 'http://www.gravatar.com/avatar/'.md5($email).'?s='.$width.'&amp;default='.urlencode(JURI::root().'components/com_k2/images/placeholder/user.png');
            } else {
                $avatar = JURI::root().'components/com_k2/images/placeholder/user.png';
            }
        } else if (is_numeric($userID) && $userID > 0) {
        
            $db = &JFactory::getDBO();
            $query = "SELECT image FROM #__k2_users WHERE userID={$userID}";
            $db->setQuery($query);
            $avatar = $db->loadResult();
            if ( empty($avatar)) {
                if ($params->get('gravatar') && !is_null($email)) {
                    $avatar = 'http://www.gravatar.com/avatar/'.md5($email).'?s='.$width.'&amp;default='.urlencode(JURI::root().'components/com_k2/images/placeholder/user.png');
                } else {
                    $avatar = JURI::root().'components/com_k2/images/placeholder/user.png';
                }
            }
			else {
				$avatar = JURI::root().'media/k2/users/'.$avatar;
			}
            
        }

        return $avatar;
    }

		// Word limit
		function wordLimit($str, $limit = 100, $end_char = '&#8230;') {
			if (trim($str) == '')
				return $str;
			// always strip tags for text
			$str = strip_tags($str);
			preg_match('/\s*(?:\S*\s*){'.(int)$limit.'}/', $str, $matches);
			if (strlen($matches[0]) == strlen($str))
				$end_char = '';
			return rtrim($matches[0]).$end_char;
		}
		
		// Gender
		function writtenBy($gender){
			
			if (is_null($gender))
				return JText::_('WRITTEN_BY');
				
			if ($gender=='m')
				return JText::_('WRITTEN_BY_MALE');
				
			if ($gender=='f')
				return JText::_('WRITTEN_BY_FEMALE');
		
		}
		
} // End
