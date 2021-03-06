<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_random_image
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<div class="random-image">
	<?php if ($link) : ?>
	<a href="<?php echo $link; ?>">
	<?php endif; ?>
	<?php echo JHtml::_('image', $image->folder.'/'.$image->name, $image->name); ?>
	<?php if ($link) : ?>
	</a>
	<?php endif; ?>
</div>
