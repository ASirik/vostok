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

?>
<script type="text/javascript">
function submitbutton(pressbutton) {
	if(pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	} else {
		submitform( pressbutton );
	}
}
</script>

<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm">
  <fieldset class="adminform">
    <legend><?php echo JText::_('Details'); ?></legend>
    <table class="admintable">
      <tr>
        <td class="key"><?php	echo JText::_('Name'); ?></td>
        <td><?php echo $this->row->name; ?></td>
      </tr>
      <tr>
        <td class="key"><?php	echo JText::_('Gender'); ?></td>
        <td><?php echo $this->lists['gender']; ?></td>
      </tr>
      <tr>
        <td class="key"><?php	echo JText::_('K2 user group'); ?></td>
        <td><?php echo $this->lists['userGroup']; ?></td>
      </tr>
      <tr>
        <td class="key"><?php echo JText::_('Description'); ?></td>
        <td><?php echo $this->editor; ?></td>
      </tr>
      <tr>
        <td class="key"><?php	echo JText::_('User avatar (image)'); ?></td>
        <td>
        	<input type="file" name="image" />
          <?php if($this->row->image):?>
          <img class="k2AdminImage" src="<?php echo JURI::root().'media/k2/users/'.$this->row->image; ?>" alt="<?php echo $this->row->name; ?>" />
          <input type="checkbox" name="del_image" id="del_image" />
          <label for="del_image"><?php echo JText::_('Upload new image to replace existing avatar or check this box to delete user avatar'); ?></label>
          <?php endif; ?></td>
      </tr>
      <tr>
        <td class="key"><?php	echo JText::_('URL'); ?></td>
        <td><input type="text" size="50" value="<?php echo $this->row->url; ?>" name="url" /></td>
      </tr>
    </table>
	</fieldset>

	<?php if(count($this->K2Plugins)):?>
	<?php foreach ($this->K2Plugins as $K2Plugin) : ?>
	<?php if(!is_null($K2Plugin)):?>
	<fieldset class="adminform">
		<legend><?php echo $K2Plugin->name; ?></legend>
		<?php echo $K2Plugin->fields; ?>
	</fieldset>
	<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>

  <input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="view" value="user" />
  <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
  <input type="hidden" name="userID" value="<?php echo $this->row->userID; ?>" />
  <?php echo JHTML::_('form.token'); ?>
</form>
