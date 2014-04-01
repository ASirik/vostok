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

// OpenID stuff (do not edit)
if(JPluginHelper::isEnabled('authentication', 'openid')){
	$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
	$langScript = '
		var JLanguage = {};
		JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';
		JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';
		JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';
		var modlogin = 1;
	';
	$document = &JFactory::getDocument();
	$document->addScriptDeclaration( $langScript );
	JHTML::_('script', 'openid.js');
}

// Get user stuff (do not edit)
$usersConfig = &JComponentHelper::getParams( 'com_users' );

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2LoginBlock <?php echo $params->get('moduleclass_sfx'); ?>">
	<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	
		<?php if($params->get('pretext')): ?>
		<p class="preText"><?php echo $params->get('pretext'); ?></p>
	  <?php endif; ?>
	  
	  <fieldset class="input">
	    <p id="form-login-username">
	      <input id="modlgn_username" type="text" name="username" class="inputbox png" alt="username" size="18" value="<?php echo JText::_('Username') ?>" onblur="if(this.value == '') this.value=this.defaultValue" onfocus="if(this.value == this.defaultValue) this.value=''" />
	    </p>
	    <p id="form-login-password">
	      <input id="modlgn_passwd" type="password" name="passwd" class="inputbox png" size="18" alt="password" value="........." onblur="if(this.value == '') this.value=this.defaultValue" onfocus="if(this.value == this.defaultValue) this.value=''" />
	    </p>
	    <?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	    <p id="form-login-remember">
	      <label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
	      <input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
	    </p>
	    <?php endif; ?>
	    <p id="form-login-submit">
			<input type="submit" name="Submit" class="button png" value="<?php echo JText::_('LOGIN') ?>" />
		</p>
	  </fieldset>
	  
	  <ul>
	    <li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>"><?php echo JText::_('Forgot your password?'); ?></a></li>
	    <li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>"><?php echo JText::_('Forgot your username?'); ?></a></li>
	    <?php if ($usersConfig->get('allowUserRegistration')): ?>
	    <li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>"><?php echo JText::_('Create an account'); ?></a></li>
	    <?php endif; ?>
	  </ul>
	  
	  <?php if($params->get('posttext')): ?>
	  <p class="postText"><?php echo $params->get('posttext'); ?></p>
	  <?php endif; ?>
	  
	  <input type="hidden" name="option" value="com_user" />
	  <input type="hidden" name="task" value="login" />
	  <input type="hidden" name="return" value="<?php echo $return; ?>" />
	  <?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
