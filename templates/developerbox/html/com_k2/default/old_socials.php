<?php
/**
 * @version		$Id: user.php 1248 2011-10-19 17:15:18Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

	            <div class="pre25SocialLinks">
	                 
	                  <ul class="preSocialLinks">
					  		<li>
								 <span class="preSocialsTitle"><?php echo JText::_('Social sharing:'); ?></span>
							</li>
	                        <li>
	                              <a class="facebook" title="<?php echo JText::_('K2_ADD_TO_FACEBOOK'); ?>" href="http://www.facebook.com/sharer.php?u=<?php echo $this->item->socialLink; ?>&amp;t=<?php echo urlencode($this->item->title); ?>" target="_blank"><span><?php echo JText::_('K2_ADD_TO_FACEBOOK'); ?></span></a>
	                        </li>
	                        <li>
	                              <a class="delicious" title="<?php echo JText::_('K2_ADD_TO_DELICIOUS'); ?>" href="http://del.icio.us/post?url=<?php echo $this->item->socialLink; ?>&amp;title=<?php echo urlencode($this->item->title); ?>" target="_blank"><span><?php echo JText::_('K2_ADD_TO_DELICIOUS'); ?></span></a>
	                        </li>
	                        <li>
	                              <a class="digg" title="<?php echo JText::_('K2_DIGG_THIS'); ?>" href="http://digg.com/submit?url=<?php echo $this->item->socialLink; ?>&amp;title=<?php echo urlencode($this->item->title); ?>" target="_blank"><span><?php echo JText::_('K2_DIGG_THIS'); ?></span></a>
	                        </li>
	                        </li>
	                        <li>
	                              <a class="stumble" title="<?php echo JText::_('K2_ADD_TO_STUMBLEUPON'); ?>" href="http://www.stumbleupon.com/submit?url=<?php echo $this->item->socialLink; ?>&amp;title=<?php echo urlencode($this->item->title); ?>" target="_blank"><span><?php echo JText::_('K2_ADD_TO_STUMBLEUPON'); ?></span></a>
	                        </li>
	                        <li>
	                              <a class="technorati" title="<?php echo JText::_('K2_ADD_TO_TECHNORATI'); ?>" href="http://www.technorati.com/faves?add=<?php echo $this->item->socialLink; ?>" target="_blank"><span><?php echo JText::_('K2_ADD_TO_TECHNORATI'); ?></span></a>
	                        </li>
	                        <li>
	                              <a class="reddit" title="<?php echo JText::_('K2_ADD_TO_REDDIT'); ?>" href="http://reddit.com/submit?url=<?php echo $this->item->socialLink; ?>&amp;title=<?php echo urlencode($this->item->title); ?>" target="_blank"><span><?php echo JText::_('K2_ADD_TO_REDDIT'); ?></span></a>

	                        <li>
	                              <a class="myspace" title="<?php echo JText::_('K2_ADD_TO_MYSPACE'); ?>" href="http://www.myspace.com/Modules/PostTo/Pages/?l=3&amp;u=<?php echo $this->item->socialLink; ?>&amp;t=<?php echo urlencode($this->item->title); ?>" target="_blank"><span><?php echo JText::_('K2_ADD_TO_MYSPACE'); ?></span></a>
	                        </li>
<li>
	                  <a  class="twitter" title="<?php echo JText::_('K2_LIKE_THIS_TWEET_IT_TO_YOUR_FOLLOWERS'); ?>" href="<?php echo $this->item->twitterURL; ?>" target="_blank">
	                  <?php echo JText::_('K2_LIKE_THIS_TWEET_IT_TO_YOUR_FOLLOWERS'); ?>
	                  </a>
</li>
	                        <li class="clr"></li>
	                  </ul>
	                  <div class="clr"></div>
	            </div>