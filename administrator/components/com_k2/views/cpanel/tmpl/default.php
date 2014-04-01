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

// Get user group ID
$user= &JFactory::getUser();

?>
<script type="text/javascript">
	window.addEvent('domready', function(){
		$$('#toolbar-Link a').addEvent('click', function(e){
			var answer = confirm('<?php echo JText::_('WARNING: You are about to import all articles from Joomla!\'s core content component (com_content)! If you have executed this operation before, duplicate content may be produced!', true); ?>')
			if (!answer){
				new Event(e).stop();
			}
		})
	});
</script>

<div id="cpanel" style="float:left;width:54%;">

  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=item">
		    <img alt="<?php echo JText::_('Add new item'); ?>" src="components/com_k2/images/dashboard/item-new.png" />
		    <span><?php echo JText::_('Add new item'); ?></span>
	    </a>
    </div>
  </div>
  
  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=items&amp;filter_trash=0">
		    <img alt="<?php echo JText::_('Items'); ?>" src="components/com_k2/images/dashboard/items.png" />
		    <span><?php echo JText::_('Items'); ?></span>
	    </a>
    </div>
  </div>
  
	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=items&amp;filter_featured=1">
		    <img alt="<?php echo JText::_('Featured items'); ?>" src="components/com_k2/images/dashboard/items-featured.png" />
		    <span><?php echo JText::_('Featured items'); ?></span>
	    </a>
    </div>
  </div>
  
  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=items&amp;filter_trash=1">
		    <img alt="<?php echo JText::_('Trashed items'); ?>" src="components/com_k2/images/dashboard/items-trashed.png" />
		    <span><?php echo JText::_('Trashed items'); ?></span>
	    </a>
    </div>
  </div>
	
	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=categories&amp;filter_trash=0">
		    <img alt="<?php echo JText::_('Categories'); ?>" src="components/com_k2/images/dashboard/categories.png" />
		    <span><?php echo JText::_('Categories'); ?></span>
	    </a>
    </div>
  </div>
	
	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=categories&amp;filter_trash=1">
		    <img alt="<?php echo JText::_('Trashed categories'); ?>" src="components/com_k2/images/dashboard/categories-trashed.png" />
		    <span><?php echo JText::_('Trashed categories'); ?></span>
	    </a>
    </div>
  </div>
	
	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=tags">
		    <img alt="<?php echo JText::_('Tags'); ?>" src="components/com_k2/images/dashboard/tags.png" />
		    <span><?php echo JText::_('Tags'); ?></span>
	    </a>
    </div>
  </div>
	
	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=comments">
		    <img alt="<?php echo JText::_('Comments'); ?>" src="components/com_k2/images/dashboard/comments.png" />
		    <span><?php echo JText::_('Comments'); ?></span>
	    </a>
    </div>
  </div>

  <?php if ($user->gid>23): ?>
  <!--
  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&view=users">
		    <img src="components/com_k2/images/dashboard/users.png" alt="<?php echo JText::_('Users'); ?>" />
		    <span><?php echo JText::_('Users'); ?></span>
	    </a>
    </div>
  </div>
  
  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&view=userGroups">
		    <img src="components/com_k2/images/dashboard/user-groups.png" alt="<?php echo JText::_('User groups'); ?>" />
		    <span><?php echo JText::_('User groups'); ?></span>
	    </a>
    </div>
  </div>
  -->
  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=extraFields">
		    <img alt="<?php echo JText::_('Extra fields'); ?>" src="components/com_k2/images/dashboard/extra-fields.png" />
		    <span><?php echo JText::_('Extra fields'); ?></span>
	    </a>
    </div>
  </div>

	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=extraFieldsGroups">
		    <img alt="<?php echo JText::_('Extra field groups'); ?>" src="components/com_k2/images/dashboard/extra-field-groups.png" />
		    <span><?php echo JText::_('Extra field groups'); ?></span>
	    </a>
    </div>
  </div>
  <?php endif; ?>
  
	<div style="float:left;">
    <div class="icon">
	    <a href="#" onclick="window.open('http://www.splashup.com/splashup/','splashupEditor','height=700,width=990,toolbar=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes'); return false;">
		    <img alt="<?php echo JText::_('Edit images (with SplashUp)'); ?>" src="components/com_k2/images/dashboard/image-editing.png" />
		    <span><?php echo JText::_('Edit images<br />(with SplashUp)'); ?></span>
	    </a>
    </div>
  </div>
  
  <div style="float:left;">
    <div class="icon">
    	<a target="_blank" href="http://k2.joomlaworks.gr/documentation/">
    		<img alt="<?php echo JText::_('K2 Documentation'); ?>" src="components/com_k2/images/dashboard/documentation.png" />
    		<span><?php echo JText::_('K2 Documentation'); ?></span>
    	</a>
    </div>
  </div>
  
  <?php if ($user->gid>23): ?>
  <div style="float:left;">
    <div class="icon">
    	<a target="_blank" href="http://k2community.joomlaworks.gr">
    		<img alt="<?php echo JText::_('K2 Community'); ?>" src="components/com_k2/images/dashboard/help.png" />
    		<span><?php echo JText::_('K2 Community'); ?></span>
    	</a>
    </div>
  </div>
  
  <div style="float:left;">
    <div class="icon">
	    <a class="modal" rel="{handler: 'iframe', size: {x: 1040, y: 600}}" href="http://start.joomlaworks.gr">
		    <img alt="<?php echo JText::_('Joomla!Sphere (by JoomlaWorks)'); ?>" src="components/com_k2/images/dashboard/joomlasphere.png" />
		    <span><?php echo JText::_('Joomla!Sphere<br />(by JoomlaWorks)'); ?></span>
	    </a>
    </div>
  </div>
  
  <div class="clr"></div>
  
  <?php endif; ?>
  
</div>

<div id="stats">
  <?php
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('Tabs');
		echo $pane->startPane('myPane');
		
		echo $pane->startPanel(JText::_('About'), 'abouttab');
		echo JText::_('About K2');
		echo $pane->endPanel();
		?>
  <?php echo $pane->startPanel(JText::_('Latest items'), 'itemstab');?>
  <table class="adminlist" style="width:100%;">
    <thead>
      <tr>
        <td class="title"><?php echo JText::_('Title');?></td>
        <td class="title"><?php echo JText::_('Created');?></td>
        <td class="title"><?php echo JText::_('Author');?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach($this->latestItems as $latest):?>
      <tr>
        <td><?php echo $latest->title;?></td>
        <td><?php echo $latest->created;?></td>
        <td><?php echo $latest->author;?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php echo $pane->endPanel();?> <?php echo $pane->startPanel(JText::_('Latest comments'), 'commentstab');?>
  <table class="adminlist" style="width:100%;">
    <thead>
      <tr>
        <td class="title"><?php echo JText::_('Comment');?></td>
        <td class="title"><?php echo JText::_('Submited by');?></td>
        <td class="title"><?php echo JText::_('Date');?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach($this->latestComments as $latest):?>
      <tr>
        <td><?php echo $latest->commentText;?></td>
        <td><?php echo $latest->userName;?></td>
        <td><?php echo $latest->commentDate;?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php echo $pane->endPanel();?> <?php echo $pane->startPanel(JText::_('Statistics'), 'statstab');?>
  <table class="adminlist" style="width:100%;">
    <thead>
      <tr>
        <td class="title"><?php echo JText::_('Data type');?></td>
        <td class="title"><?php echo JText::_('Number');?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo JText::_('Items');?></td>
        <td><?php echo $this->numOfItems;?> (<?php echo $this->numOfFeaturedItems.' '.JText::_('featured').' - '.$this->numOfTrashedItems.' '.JText::_('trashed');?>)</td>
      </tr>
      <tr>
        <td><?php echo JText::_('Categories');?></td>
        <td><?php echo $this->numOfCategories;?> (<?php echo $this->numOfTrashedCategories.' '.JText::_('trashed');?>)</td>
      </tr>
      <tr>
        <td><?php echo JText::_('Tags');?></td>
        <td><?php echo $this->numOfTags;?></td>
      </tr>
      <tr>
        <td><?php echo JText::_('Comments');?></td>
        <td><?php echo $this->numOfComments;?></td>
      </tr>
      <tr>
        <td><?php echo JText::_('Users');?></td>
        <td><?php echo $this->numOfUsers;?></td>
      </tr>
      <tr>
        <td><?php echo JText::_('User groups');?></td>
        <td><?php echo $this->numOfUserGroups;?></td>
      </tr>
    </tbody>
  </table>
  <?php echo $pane->endPanel();?>
  <?php echo $pane->endPane();?>
</div>

<div class="clr"></div>
