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
	//<![CDATA[
	function submitbutton(pressbutton) {
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		if (trim( document.adminForm.name.value ) == "") {
			alert( '<?php echo JText::_('A category must at least have a title!', true);?>' );
		} else {
			submitform( pressbutton );
		}
	}
	//]]>
</script>

<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm">
	<table class="admintable" width="100%">
		<tbody>
			<tr>
				<td valign="top">
					<fieldset class="adminform">
						<legend><?php echo JText::_('Details');?></legend>
						<table class="admintable">
							<tr>
								<td class="key"><?php	echo JText::_('Published');	?></td>
								<td><?php echo $this->lists['published']; ?></td>
							</tr>
							<tr>
								<td class="key"><?php echo JText::_('Title'); ?></td>
								<td><input class="text_area" type="text" name="name" id="name" value="<?php echo $this->row->name; ?>" size="50" maxlength="250" /></td>
							</tr>
							<tr>
								<td class="key"><?php echo JText::_('Title alias'); ?></td>
								<td><input class="text_area" type="text" name="alias" value="<?php echo $this->row->alias; ?>" size="50" maxlength="250" /></td>
							</tr>
							<tr>
								<td class="key"><?php	echo JText::_('Parent category'); ?></td>
								<td><?php echo $this->lists['parent']; ?></td>
							</tr>
							<tr>
								<td class="key"><?php	echo JText::_('Associated "Extra Fields Group"');	?></td>
								<td><?php echo $this->lists['extraFieldsGroup']; ?></td>
							</tr>
							<tr>
								<td class="key"><?php echo JText::_('Inherit parameter options from category'); ?></td>
								<td><?php echo $this->lists['inheritFrom']; ?><span class="hasTip k2Notice" title="<?php echo JText::_('Inherit parameter options from category'); ?>::<?php echo JText::_('Setting this option will make this category inherit all parameters from another category, thus you don\'t have to re-set all options in this one if they are the same with another category\'s. This setting is very useful when you are creating child categories which share the same parameters with their parent category, e.g. in the case of a catalog or a news portal/magazine.'); ?>"><?php echo JText::_('What\'s this?'); ?></span></td>
							</tr>
							<tr>
								<td class="key"><?php echo JText::_('Access level'); ?></td>
								<td><?php echo $this->lists['access']; ?></td>
							</tr>
							<tr>
								<td class="key"><?php	echo JText::_('Description'); ?></td>
								<td><?php echo $this->editor; ?></td>
							</tr>
							<tr>
								<td class="key"><?php	echo JText::_('Image'); ?></td>
								<td>
									<input type="file" name="image" class="text_area" />
									<?php if (!empty($this->row->image)):?>
									<img alt="<?php echo $this->row->name;?>" src="<?php echo JURI::root();?>media/k2/categories/<?php echo $this->row->image; ?>" class="k2AdminImage"/>
									<input type="checkbox" name="del_image" id="del_image" />
									<label for="del_image"><?php echo JText::_('Check this box to delete current image');?></label>
									<?php endif;?>
								</td>
							</tr>
						</table>
												
						<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
						<input type="hidden" name="option" value="<?php echo $option;?>" />
						<input type="hidden" name="view" value="category" />
						<input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
						<?php echo JHTML::_('form.token'); ?>
					</fieldset>
					
					<?php if (count($this->K2Plugins)):?>
					<?php foreach ($this->K2Plugins as $K2Plugin) : ?>
					<?php if(!is_null($K2Plugin)):?>
					<fieldset class="adminform">
						<legend><?php echo $K2Plugin->name;?></legend>
						<?php echo $K2Plugin->fields;?>
					</fieldset>
					<?php endif; ?>
					<?php endforeach; ?>
					<?php endif;?>
				</td>
				<td valign="top" width="320">
					<fieldset>
						<legend><?php echo JText::_('Parameters');?></legend>
						<?php
							jimport('joomla.html.pane');
							$pane = & JPane::getInstance('sliders', array('allowAllClose' => true));
							echo $pane->startPane( 'content-pane' );
							
							echo $pane->startPanel( JText::_( 'Category item layout' ), 'category-item-layout' );
							echo $this->form->render('params','category-item-layout');
							echo $pane->endPanel();
							
							echo $pane->startPanel( JText::_( 'Category view options' ), 'category-view-options' );
							echo $this->form->render('params','category-view-options');
							echo $pane->endPanel();
							
							echo $pane->startPanel( JText::_( 'Item image options' ), 'image-options' );
							echo $this->form->render('params','item-image-options');
							echo $pane->endPanel();
							
							echo $pane->startPanel( JText::_( 'Item view options in category listings' ), 'item-view-options-listings' );
							echo $this->form->render('params','item-view-options-listings');
							echo $pane->endPanel();
							
							echo $pane->startPanel( JText::_( 'Item view options' ), 'item-view-options' );
							echo $this->form->render('params','item-view-options');
							echo $pane->endPanel();
							
							echo $pane->endPane();
						?>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
</form>
