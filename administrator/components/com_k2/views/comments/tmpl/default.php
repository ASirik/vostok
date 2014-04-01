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
	window.addEvent('domready', function(){
		$$('#toolbar-Link a').addEvent('click', function(e){
			var answer = confirm('<?php echo JText::_('This will permanently delete all unpublished comments. Are you sure?', true); ?>')
			if (!answer){
				new Event(e).stop();
			}
		})
	});
</script>
<form action="index.php" method="post" name="adminForm">
  <table width="100%">
    <tr>
      <td align="left"><?php echo JText::_('Filter:'); ?>
        <input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_('Filter by comment'); ?>"/>
        <button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='-1';this.form.submit();"><?php echo JText::_('Reset'); ?></button></td>
      <td align="right"><?php echo $this->lists['state']; ?></td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="20"> # </th>
        <th width="20"> <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
        <th class="title"> <?php echo JHTML::_('grid.sort',   JText::_('Comment'), 'c.commentText', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
		<th> <?php echo JHTML::_('grid.sort',   JText::_('Item'), 'i.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th> <?php echo JHTML::_('grid.sort',   JText::_('Name'), 'c.userName', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th> <?php echo JHTML::_('grid.sort',   JText::_('Email'), 'c.commentEmail', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th> <?php echo JHTML::_('grid.sort',   JText::_('URL'), 'c.commentURL', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th> <?php echo JHTML::_('grid.sort', JText::_('Published'), 'c.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th> <?php echo JHTML::_('grid.sort', JText::_('Date'), 'c.commentDate', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th> <?php echo JHTML::_('grid.sort', JText::_('ID'), 'c.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
      </tr>
    </thead>
    <tbody>
  <?php
  $k = 0; $i = 0;
	foreach ($this->rows as $row) :
		$row->checked_out=0;
		$checked 	= JHTML::_('grid.checkedout', $row, $i );
		$published = JHTML::_('grid.published', $row, $i );
		?>
      <tr class="<?php echo "row$k"; ?>">
        <td width="20" align="center"><?php echo $i+1; ?></td>
        <td width="20" align="center"><?php echo $checked; ?></td>
        <td><?php echo $row->commentText;?></td>
		<td><a class="modal" rel="{handler: 'iframe', size: {x: 1000, y: 600}}" href="<?php echo JURI::root().K2HelperRoute::getItemRoute($row->itemID.':'.urlencode($row->itemAlias),$row->catid.':'.urlencode($row->catAlias));?>"><?php echo $row->title;?></a> </td>
        <td><?php echo $row->userName;?></td>
        <td><?php echo $row->commentEmail;?></td>
        <td><?php echo $row->commentURL;?></td>
        <td align="center"><?php echo $published;?></td>
        <td><?php echo JHTML::_('date', $row->commentDate , JText::_('DATE_FORMAT_LC2')); ?></td>
        <td align="center"><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="11"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="comments" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php echo JHTML::_( 'form.token' );?>
</form>
