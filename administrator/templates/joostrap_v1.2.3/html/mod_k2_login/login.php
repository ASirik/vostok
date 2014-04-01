<?php
/**
 * @version		$Id: login.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2LoginBlock<?php if($params->get('moduleclass_sfx')) echo ' '.$params->get('moduleclass_sfx'); ?>">
	<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
		<?php if($params->get('pretext')): ?>
		<p class="preText"><?php echo $params->get('pretext'); ?></p>
	  <?php endif; ?>
	  
	  <fieldset class="input">
	    <div class="input-prepend">
	      <span class="add-on"><i class="icon-user"></i></span><input id="modlgn_username" type="text" name="username" class="inputbox" size="18" />
	    </div>
	    <div class="input-prepend">
	      <span class="add-on"><i class="icon-asterisk"></i></span><input id="modlgn_passwd" type="password" name="<?php echo (K2_JVERSION=='16') ? 'password':'passwd'?>" class="inputbox" size="18" />
	    </div>
	    <?php if(JPluginHelper::isEnabled('system', 'remember')): ?>
	    <p id="form-login-remember">
	      <label for="modlgn_remember"><?php echo JText::_('K2_REMEMBER_ME') ?></label>
	      <input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" />
	    </p>
	    <?php endif; ?>
	    
	    <input type="submit" name="Submit" class="button btn btn-small btn-primary" value="<?php echo JText::_('K2_LOGIN') ?>" />
	  </fieldset>
	  
	  <ul>
	    <li><a href="<?php echo JRoute::_((K2_JVERSION=='16')?'index.php?option=com_users&view=reset':'index.php?option=com_user&view=reset'); ?>"><i class="icon-question-sign"></i> <?php echo JText::_('K2_FORGOT_YOUR_PASSWORD'); ?></a></li>
	    <li><a href="<?php echo JRoute::_((K2_JVERSION=='16')?'index.php?option=com_users&view=remind':'index.php?option=com_user&view=remind'); ?>"><i class="icon-question-sign"></i> <?php echo JText::_('K2_FORGOT_YOUR_USERNAME'); ?></a></li>
	    <?php if ($usersConfig->get('allowUserRegistration')): ?>
	    <li><a href="<?php echo JRoute::_((K2_JVERSION=='16')?'index.php?option=com_users&view=registration':'index.php?option=com_user&view=register'); ?>"><i class="icon-question-sign"></i> <?php echo JText::_('K2_CREATE_AN_ACCOUNT'); ?></a></li>
	    <?php endif; ?>
	  </ul>
	  
	  <?php if($params->get('posttext')): ?>
	  <p class="postText"><?php echo $params->get('posttext'); ?></p>
	  <?php endif; ?>
	  
	  <input type="hidden" name="option" value="<?php echo (K2_JVERSION=='16')?'com_users':'com_user'?>" />
	  <input type="hidden" name="task" value="<?php echo (K2_JVERSION=='16')?'user.login':'login'?>" />
	  <input type="hidden" name="return" value="<?php echo $return; ?>" />
	  <?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
