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

<form action="index.php" method="post" name="adminForm">
  <table width="100%">
    <tr>
      <td align="left" width="40%"><?php echo JText::_('Filter:'); ?>
        <input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_('Filter by name'); ?>"/>
        <button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='-1';this.form.getElementById('filter_group').value='0';this.form.getElementById('filter_group_k2').value='0';this.form.submit();"><?php echo JText::_('Reset'); ?></button></td>
      <td align="right" width="60%"><?php echo $this->lists['filter_group_k2']; ?>&nbsp;<?php echo $this->lists['filter_group']; ?>&nbsp;<?php echo $this->lists['status']; ?></td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="5" align="center"><?php echo JText::_( 'Num' ); ?></th>
        <th class="title"> <?php echo JHTML::_('grid.sort',   JText::_('Name'), 'juser.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th class="title"> <?php echo JHTML::_('grid.sort',   JText::_('User Name'), 'juser.username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th width="5%" nowrap="nowrap"> <?php echo JHTML::_('grid.sort', JText::_('Enabled'), 'juser.block', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th class="title"> <?php echo JHTML::_('grid.sort',   JText::_('Group'), 'juser.usertype', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th class="title"> <?php echo JHTML::_('grid.sort',   JText::_('K2 Group'), 'groupname', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th width="5%" nowrap="nowrap"> <?php echo JHTML::_('grid.sort', JText::_('ID'), 'juser.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="7"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <?php
      $k = 0; $i = 0;	$n = count( $this->rows );
			foreach ($this->rows as $row):
			?>
      <tr class="<?php echo "row$k"; ?>">
        <td width="20" align="center"><?php echo $i+1; ?></td>
        <td><a style="cursor:pointer" onclick="window.parent.jSelectUser('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""),$row->name); ?>', 'id');"><?php echo $row->name; ?></a></td>
        <td><?php echo $row->username;?></td>
        <td align="center"><?php echo $row->block ? '<img src="images/publish_x.png" width="16" height="16" border="0" alt="" />': '<img src="images/tick.png" width="16" height="16" border="0" alt="" />'; ?></td>
        <td><?php echo $row->usertype;?></td>
        <td><?php echo $row->groupname;?></td>
        <td align="center"><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="users" />
  <input type="hidden" name="task" value="element" />
  <input type="hidden" name="tmpl" value="component" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
