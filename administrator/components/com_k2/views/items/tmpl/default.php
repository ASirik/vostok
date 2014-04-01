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
$db = &JFactory::getDBO(); $nullDate = $db->getNullDate();
$ordering = ( ($this->lists['order'] == 'i.ordering' || $this->lists['order'] == 'category' || $this->filter_featured) && (!$this->filter_trash));
?>

<form action="index.php" method="post" name="adminForm">
  <table width="100%">
    <tr>
      <td align="left"><?php echo JText::_('Filter:'); ?>
        <input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_('Filter by title'); ?>"/>
        <button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_category').value='0';this.form.getElementById('filter_trash').value='0';this.form.getElementById('filter_author').value='0';this.form.getElementById('filter_state').value='-1';this.form.getElementById('filter_featured').value='-1';this.form.submit();"><?php echo JText::_('Reset'); ?></button></td>
      <td align="right">
      <?php echo "{$this->lists['trash']}&nbsp;{$this->lists['featured']}&nbsp;&nbsp;|&nbsp;&nbsp;{$this->lists['categories']}&nbsp;{$this->lists['authors']}&nbsp;{$this->lists['state']}"; ?>
      </td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="5">#</th>
        <th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
        <th class="title"><?php echo JHTML::_('grid.sort', JText::_('Title'), 'i.title', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Featured'), 'i.featured', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Published'), 'i.published', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th width="100" nowrap="nowrap">
        	<?php if ($this->filter_featured=='1'):?>
          <?php echo JHTML::_('grid.sort', JText::_('Featured Order'), 'i.featured_ordering', @$this->lists['order_Dir'], @$this->lists['order']); ?>
          <?php if ($ordering) echo JHTML::_('grid.order',  $this->rows, 'filesave.png','savefeaturedorder' ); ?>
          <?php else: ?>
          <?php echo JHTML::_('grid.sort', JText::_('Order'), 'i.ordering', @$this->lists['order_Dir'], @$this->lists['order']); ?>
          <?php if ($ordering) echo JHTML::_('grid.order',  $this->rows ); ?>
          <?php endif; ?>
        </th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Category'), 'category', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Author'), 'author', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('Last modified by'), 'moderator', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Access Level'), 'i.access', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Created'), 'i.created', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Modified'), 'i.modified', @$this->lists['order_Dir'], @$this->lists['order']); ?> </th>
        <th><?php echo JHTML::_('grid.sort', JText::_('hits'), 'i.hits', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', JText::_('ID'), 'i.id', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="14"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <?php
      $k = 0; $i = 0;	$n = count( $this->rows );
			$user=& JFactory::getUser();
			foreach ($this->rows as $row) :
				$checked 	= JHTML::_('grid.checkedout', $row, $i );
				$published = JHTML::_('grid.published', $row, $i );
				$access = JHTML::_('grid.access', $row, $i );
				$link = JRoute::_('index.php?option=com_k2&view=item&cid='.$row->id);
			?>
      <tr class="<?php echo "row$k"; ?>">
        <td><?php echo $i+1; ?></td>
        <td align="center"><?php echo $checked; ?></td>
        <td><?php if (JTable::isCheckedOut($user->get('id'), $row->checked_out )):?>
          <?php echo $row->title;?>
          <?php else: ?>
          <?php if(!$this->filter_trash):?>
          <a href="<?php echo $link; ?>">
          <?php endif; ?>
          <?php echo $row->title;?>
          <?php if(!$this->filter_trash):?>
          </a>
          <?php endif; ?>
          <?php endif;?></td>
        <td align="center"><?php if(!$this->filter_trash):?>
          <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','featured')" title="<?php echo ( $row->featured ) ? JText::_( 'Remove featured flag' ) : JText::_( 'Flag as featured' );?>">
          <?php endif; ?>
          <img src="images/<?php echo ( $row->featured ) ? 'tick.png' :  'publish_x.png' ;?>" width="16" height="16" border="0" alt="<?php echo ( $row->featured ) ? JText::_( 'Remove featured flag' ) : JText::_( 'Flag as featured' );?>" />
          <?php if(!$this->filter_trash):?>
          </a>
          <?php endif; ?></td>
        <td align="center"><?php echo ($this->filter_trash)?strip_tags($published,'<img>'):$published;?></td>
        <td class="order"><?php if ($this->filter_featured=='1'):?>
          <span><?php echo $this->page->orderUpIcon($i, true, 'featuredorderup', 'Move Up', $ordering); ?></span> <span><?php echo $this->page->orderDownIcon($i, $n, true, 'featuredorderdown', 'Move Down', $ordering); ?></span>
          <?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
          <input type="text" name="order[]" size="5" value="<?php echo $row->featured_ordering; ?>" <?php echo $disabled ?>	class="text_area" style="text-align: center" />
          <?php else: ?>
          <span><?php echo $this->page->orderUpIcon($i, ($row->catid == @$this->rows[$i-1]->catid), 'orderup', 'Move Up', $ordering); ?></span> <span><?php echo $this->page->orderDownIcon($i, $n, ($row->catid == @$this->rows[$i+1]->catid), 'orderdown', 'Move Down', $ordering); ?></span>
          <?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
          <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?>	class="text_area" style="text-align: center" />
          <?php endif; ?></td>
        <td><a href="<?php echo JRoute::_('index.php?option=com_k2&view=category&cid='.$row->catid);?>"><?php echo $row->category; ?></a></td>
        <td>
        	<?php if($user->gid>23):?>
        	<a href="<?php echo JRoute::_('index.php?option=com_users&task=edit&cid[]='.$row->created_by);?>"><?php echo $row->author; ?></a>
			<?php else:?>
			<?php echo $row->author; ?>
			<?php endif; ?>
		</td>
        <td>
        	<?php if($user->gid>23):?>
        	<a href="<?php echo JRoute::_('index.php?option=com_users&task=edit&cid[]='.$row->modified_by);?>"><?php echo $row->moderator; ?></a>
			<?php else:?>
			<?php echo $row->moderator; ?>
			<?php endif; ?>
		</td>
        <td align="center"><?php echo ($this->filter_trash)?strip_tags($access):$access;?></td>
        <td nowrap="nowrap"><?php echo JHTML::_('date', $row->created , JText::_('DATE_FORMAT_LC2')); ?></td>
        <td nowrap="nowrap"><?php if ($row->modified == $nullDate) echo JText::_('Never'); else echo JHTML::_('date', $row->modified , JText::_('DATE_FORMAT_LC2')); ?></td>
        <td nowrap="nowrap" align="center"><?php echo $row->hits ?></td>
        <td align="center"><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
  <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php	echo JHTML::_('form.token'); ?>
</form>
