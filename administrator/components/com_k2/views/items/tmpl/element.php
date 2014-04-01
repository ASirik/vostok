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
      <td align="left" width="50%"><?php echo JText::_('Filter:'); ?>
        <input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_('Filter by title'); ?>"/>
        <button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_category').value='0';this.form.getElementById('filter_trash').value='0';this.form.getElementById('filter_author').value='0';this.form.getElementById('filter_state').value='-1';this.form.getElementById('filter_featured').value='-1';this.form.submit();"><?php echo JText::_('Reset'); ?></button></td>
      <td align="right" width="50%"><?php echo "{$this->lists['trash']}&nbsp;{$this->lists['featured']}&nbsp;&nbsp; | &nbsp;&nbsp;{$this->lists['categories']}&nbsp;{$this->lists['authors']}&nbsp;{$this->lists['state']}"; ?></td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="5" align="center"><?php echo JText::_( 'Num' ); ?></th>
        <th class="title"><?php echo JHTML::_('grid.sort', JText::_('Title'), 'i.title', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th class="title" width="15%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('Category'), 'category', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th class="title" width="15%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('Author'), 'author', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th width="7%"><?php echo JHTML::_('grid.sort', JText::_('Access Level'), 'i.access', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th align="center" width="10"><?php echo JHTML::_('grid.sort', JText::_('Created'), 'i.created', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th width="1%" class="title"><?php echo JHTML::_('grid.sort', JText::_('ID'), 'i.id', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="7"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <?php $k = 0; $i = 0;	$n = count( $this->rows );
			foreach ($this->rows as $row):
			?>
      <tr class="<?php echo "row$k"; ?>">
        <td align="center"><?php echo $i+1; ?></td>
        <td><a style="cursor:pointer" onclick="window.parent.jSelectItem('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""),$row->title); ?>', 'id');"><?php echo $row->title; ?></a></td>
        <td><?php echo $row->category; ?></td>
        <td><?php echo $row->author; ?></td>
        <td align="center"><?php echo $row->groupname;?></td>
        <td nowrap="nowrap"><?php echo $row->created; ?></td>
        <td align="center"><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="items" />
  <input type="hidden" name="task" value="element" />
  <input type="hidden" name="tmpl" value="component" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
