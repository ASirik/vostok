<?php
/*
// "K2 Login" Module by JoomlaWorks for Joomla! 1.5.x - Version 2.1
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr and http://k2.joomlaworks.gr
// Designed and developed by the JoomlaWorks team
// *** Last update: September 9th, 2009 ***
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
$user = &JFactory::getUser();
?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2UserBlock <?php echo $params->get('moduleclass_sfx'); ?>">
	<p>
	  <?php if($params->get('userAvatar')): ?>
	  <img src="<?php echo K2HelperUtilities::getAvatar($user->id, $user->email);?>" alt="<?php echo $user->name; ?>"/>
	  <?php endif; ?>
	  <span class="ubName">
	  	<?php if ($params->get('greeting')) echo JText::_('Welcome').' '; ?>
	  	<b><?php echo $user->name; ?></b>
	  </span>
	</p>

  <ul>
    <?php if(is_object($user->profile) &&  isset($user->profile->addLink)):?>
    <li>
	    <a class="modal" rel="{handler:'iframe',size:{x:990,y:650}}" href="<?php echo $user->profile->addLink; ?>"><?php echo JText::_('Add new item'); ?></a>
    </li>
    <?php endif ; ?>
    
    <li>
	    <a href="<?php echo JRoute::_(K2HelperRoute::getUserRoute($user->id)); ?>"><?php echo JText::_('My page'); ?></a>
    </li>
    
    <li>
	    <a href="<?php echo JRoute::_('index.php?option=com_user&view=user&task=edit'); ?>"><?php echo JText::_('My account'); ?></a>
    </li>
  </ul>
  
  <p class="ubCommentsCount">
  	<?php echo JText::_('You have'); ?> <b><?php echo $user->numOfComments; ?></b> <?php if($user->numOfComments==1) echo JText::_('published comment'); else echo JText::_('published comments'); ?>.
  </p>
  
  <form action="index.php" method="post">
    <input type="submit" name="Submit" class="button ubLogout" value="<?php echo JText::_( 'Logout'); ?>" />
    <input type="hidden" name="option" value="com_user" />
    <input type="hidden" name="task" value="logout" />
    <input type="hidden" name="return" value="<?php echo $return; ?>" />
  </form>
</div>
