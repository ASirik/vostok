<?php
/**
 * @package        Joomla.Site
 * @subpackage    Templates.atomic
 * @copyright    Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die;
JHtml::_( 'behavior.keepalive' );
?>
<?php if ( $type == 'logout' ) { ?>
<ul class="nav pull-right">
    <li class="divider-vertical"></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-user icon-white"></i>&nbsp;&nbsp;
            <?php if($params->get('name') == 0) {
						echo JText::sprintf('MOD_LOGIN_LOGGED', $user->get('name'));
				   }
				   else {
						echo JText::sprintf('MOD_LOGIN_LOGGED', $user->get('username'));
				   }
			?>
            <b class="caret"></b>
        </a>
        <div class="dropdown-menu" id="login-dropdown">
            <form action="<?php echo JRoute::_( 'index.php', true, $params->get( 'usesecure' ) ); ?>" method="post" id="form-login">
                <div class="control-group">
                    <div class="controls">
                        <div class="input-prepend">
                            <input type="submit" name="Submit" class="btn btn-danger" value="<?php echo JText::_('JLOGOUT'); ?>" />
					      <input type="hidden" name="option" value="com_users" />
					      <input type="hidden" name="task" value="user.logout" />
					      <input type="hidden" name="return" value="<?php echo $return; ?>" />
					      <?php echo JHtml::_('form.token'); ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
</ul>
<?php } else { ?>
<ul class="nav pull-right">
    <li class="divider-vertical"></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-user icon-white"></i>&nbsp;&nbsp;<?php echo JText::_( 'JLOGIN' ) ?> <b class="caret"></b>
        </a>
        <div class="dropdown-menu" id="login-dropdown">
            <form action="<?php echo JRoute::_( 'index.php', true, $params->get( 'usesecure' ) ); ?>" method="post" id="form-login">
                <div class="control-group">
                    <label class="control-label" for="prependedInput"><?php echo $params->get( 'pretext' ); ?></label>

                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-user"></i></span>
                            <input class="span2" name="username" size="16" type="text">
                        </div>
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-asterisk"></i></span>
                            <input class="span2" name="password" size="16" type="password">
                            <?php if ( JPluginHelper::isEnabled( 'system', 'remember' ) ) : ?>
                            <div class="help-block login-remember">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" value="yes"/> <?php echo JText::_( 'MOD_LOGIN_REMEMBER_ME' ) ?>
                                </label>
                                <input type="submit" name="Submit" class="btn" value="<?php echo JText::_( 'JLOGIN' ) ?>"/>
                            </div>
                            <?php endif; ?>
                            <input type="hidden" name="option" value="com_users"/>
                            <input type="hidden" name="task" value="user.login"/>
                            <input type="hidden" name="return" value="<?php echo $return; ?>"/>
                            <?php echo JHtml::_( 'form.token' ); ?>
                        </div>
                  		<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
                    </div>
                </div>
            </form>
        </div>
</ul>
<?php } ?>
