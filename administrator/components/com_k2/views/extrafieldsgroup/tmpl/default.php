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
<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	if (trim( document.adminForm.name.value ) == "") {
		alert( '<?php echo JText::_('Group name cannot be empty', true);?>' );
	} else {
		submitform( pressbutton );
	}
}
//-->
</script>
<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('Details');?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><?php echo JText::_('Group Name'); ?></td>
				<td><input class="text_area" type="text" name="name" id="name" value="<?php echo $this->row->name; ?>" size="50" maxlength="250" /></td>
			</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="view" value="extraFieldsGroup" />
		<input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
		<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
		<?php echo JHTML::_('form.token'); ?>
	</fieldset>
</form>
