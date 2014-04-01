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
<?php $db = &JFactory::getDBO(); $nullDate = $db->getNullDate(); ?>
<script type="text/javascript">
<!--
function submitbutton(pressbutton) {
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	if (trim( document.adminForm.title.value ) == "") {
		alert( '<?php echo JText::_('Item must have a title', true); ?>' );
	} else if (trim( document.adminForm.catid.value ) == "0") {
		alert( '<?php echo JText::_('Please select a category', true); ?>' );
	} else {
		var tags = document.getElementById("selectedTags");
		for(i=0; i<tags.options.length; i++)
		tags.options[i].selected = true;
		submitform( pressbutton );
	}
}

function addAttachment(){
	var div = new Element('div',{'style':'border-top: 1px dotted #ccc; margin: 4px; padding: 10px;'}).injectInside($('itemAttachments'));
	var input = new Element('input',{'name':'attachment_file[]','type':'file'}).injectInside(div);
	var input = new Element('input',{'value':'<?php echo JText::_('Remove',true); ?>','type':'button',events:{ click: function(){this.getParent().remove();} } }).injectInside(div);
	var br = new Element('br').injectInside(div);
	var label = new Element('label').setHTML('<?php echo JText::_('Link title (optional)', true); ?>').injectInside(div);
	var input = new Element('input',{'name':'attachment_title[]','type':'text', 'class':'linkTitle'}).injectInside(div);
	var br = new Element('br').injectInside(div);
	var label = new Element('label').setHTML('<?php echo JText::_('Link title attribute (optional)', true); ?>').injectInside(div);
	var textarea = new Element('textarea',{'name':'attachment_title_attribute[]','cols':'30', 'rows':'3'}).injectInside(div);
}

window.addEvent('domready', function(){
	$$('.simpleTabsNavigation a').addEvent('click', function(e){
		new Event(e).stop();
        var current = this.getProperty('rel');
        $$('.simpleTabsNavigation a').removeClass('current');
        this.addClass('current');
        $$('.simpleTabsContent').setStyle('display', 'none');
        $('simpleTabsContent' + current).setStyle('display', 'block');
  });
	
	$$('.deleteAttachmentButton').addEvent('click', function(e){
		e = new Event(e).stop();
		if (confirm('<?php echo JText::_('Are you sure?', true); ?>')) {
			var element = this.getParent().getParent();
			var deleteAnimation = new Fx.Style(element, 'opacity', {duration:500});
			var url = this.getProperty('href');
			new Ajax(url, {
				method: 'get',
			 	onComplete: function(){
					deleteAnimation.start(100, 0).chain(function(){element.remove();});;
			 	}
			}).request();
		}
	});
	 
	$('newTagButton').addEvent('click', function(){
		var log = $('tagsLog');
		log.empty().addClass('loading');
		var tag=$('tag').getProperty('value');
		var url = 'index.php?option=com_k2&view=item&task=tag&tag='+tag;
		new Ajax(url, {
			method: 'get',
			onComplete: function(res){
				var response=Json.evaluate(res);
				if (response.status=="success"){
					var option = new Element('option',{'value':response.id}).setHTML(response.name).injectInside($('tags'));		
				}
				log.setHTML(response.msg);
				log.removeClass('loading');
			}
		}).request();
	});

	$('addTagButton').addEvent('click', function(){
		$$('#tags option').each(function(el) {
			if (el.selected){
				el.injectInside($('selectedTags'));
			}
		});
	}); 
	 
	$('removeTagButton').addEvent('click', function(){
		$$('#selectedTags option').each(function(el) {
			if (el.selected){
				el.injectInside($('tags'));
			}
		});
	}); 
	
	$('catid').addEvent('change', function(){
		var selectedValue = this.value;
		var url = 'index.php?option=com_k2&view=item&task=extraFields&cid='+selectedValue;
		<?php if ($this->row->id): ?>
		url+='&id=<?php echo $this->row->id?>';
		<?php endif; ?>
		new Fx.Style($('extraFieldsContainer'), 'opacity', {
			duration: 700
		}).start(0).chain(function(){
			new Ajax(url, {
				method: 'get',
				update: $('extraFieldsContainer'),
			 	onComplete: function(){
					new Fx.Style($('extraFieldsContainer'), 'opacity', {
		            	duration: 700
					}).start(1);
				}
			}).request();
		})
	});

});
//-->
</script>

<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm">
  <table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tbody>
      <tr>
        <td valign="top">
        	<!-- Tabs start here -->
          <div class="simpleTabs">
            <ul class="simpleTabsNavigation">
              <li><a href="#" rel="1" class="current"><?php echo JText::_('Content'); ?></a></li>
              <li><a href="#" rel="2"><?php echo JText::_('Image'); ?></a></li>
              <li><a href="#" rel="3"><?php echo JText::_('Image gallery'); ?></a></li>
              <li><a href="#" rel="4"><?php echo JText::_('Video'); ?></a></li>
              <li><a href="#" rel="5"><?php echo JText::_('Extra fields'); ?></a></li>
              <li><a href="#" rel="6"><?php echo JText::_('Attachments'); ?></a></li>
              <?php if (count($this->K2PluginsItemOther)): ?>
              <li><a href="#" rel="7"><?php echo JText::_('Plugins'); ?></a></li>
              <?php endif; ?>
            </ul>
            
            <div class="simpleTabsContent" id="simpleTabsContent1">

							<h3><?php echo JText::_('Title'); ?></h3>
							<input class="text_area" type="text" size="50" name="title" id="title" maxlength="250" value="<?php echo $this->row->title; ?>" />
							<h3><?php echo JText::_('Title alias'); ?></h3>
							<input class="text_area" type="text" size="50" name="alias" maxlength="250" value="<?php echo $this->row->alias; ?>" />
							<?php if ($this->params->get('mergeEditors')): ?>
							<h3><?php echo JText::_('Text'); ?></h3>
							<div class="k2ItemFormEditor"><?php echo $this->text; ?></div>
							<?php else : ?>
							<h3><?php echo JText::_('Intro text'); ?></h3>
							<div class="k2ItemFormEditor"><?php echo $this->introtext; ?></div>
							<h3><?php echo JText::_('Full text'); ?></h3>
							<div class="k2ItemFormEditor"><?php echo $this->fulltext; ?></div>
							<?php endif; ?>

              <?php if (count($this->K2PluginsItemContent)): ?>
              <div class="itemPlugins">
                <?php foreach ($this->K2PluginsItemContent as $K2Plugin) : ?>
                <?php if(!is_null($K2Plugin)): ?>
                <fieldset>
                  <legend><?php echo $K2Plugin->name; ?></legend>
                  <?php echo $K2Plugin->fields; ?>
                </fieldset>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
              
              <div class="clr"></div>
              
            </div>
            <div class="simpleTabsContent" id="simpleTabsContent2" style="display:none;">
              <table class="admintable">
                <tr>
                  <td align="right" class="key"><?php echo JText::_('Item image'); ?></td>
                  <td><input type="file" name="image" class="text_area" />
                    <?php if (!empty($this->row->image)): ?>
                    <a class="modal" href="<?php echo $this->row->image; ?>" title="<?php echo JText::_('Click on image to preview in original size'); ?>">
                    	<img alt="<?php echo $this->row->title; ?>" src="<?php echo $this->row->thumb; ?>" class="k2AdminImage"/>
                    </a>
                    <input type="checkbox" name="del_image" id="del_image" />
                    <label for="del_image"><?php echo JText::_('Upload new image to replace the existing or check this box to delete current image'); ?></label>
                    <?php endif; ?></td>
                </tr>
                <tr>
                  <td align="right" class="key"><?php echo JText::_('Item image caption'); ?></td>
                  <td><input type="text" name="image_caption" size="30" class="text_area" value="<?php echo $this->row->image_caption; ?>" /></td>
                </tr>
                <tr>
                  <td align="right" class="key"><?php echo JText::_('Item image credits'); ?></td>
                  <td><input type="text" name="image_credits" size="30" class="text_area" value="<?php echo $this->row->image_credits; ?>" /></td>
                </tr>
              </table>
              <?php if (count($this->K2PluginsItemImage)): ?>
              <div class="itemPlugins">
                <?php foreach ($this->K2PluginsItemImage as $K2Plugin) : ?>
                <?php if(!is_null($K2Plugin)): ?>
                <fieldset>
                  <legend><?php echo $K2Plugin->name; ?></legend>
                  <?php echo $K2Plugin->fields; ?>
                </fieldset>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
            <div class="simpleTabsContent" id="simpleTabsContent3" style="display:none;">
              <?php if ($this->lists['checkSIG']): ?>
              <table class="admintable" id="item_gallery_content">
                <tr>
                  <td align="right" valign="top" class="key"><?php echo JText::_('Upload a zip file with images'); ?></td>
                  <td valign="top">
                  	<input type="file" name="gallery" class="text_area" />
                  	<?php if (!empty($this->row->gallery)): ?>
                    <div id="itemGallery">
                      <?php echo $this->row->gallery; ?>
                      <input type="checkbox" name="del_gallery" id="del_gallery"/>
                      <label for="del_gallery"><?php echo JText::_('Upload new gallery to replace the existing one or check this box to delete current gallery'); ?></label>
                    </div>
                    <?php endif; ?>
                	</td>
                </tr>
              </table>
              <?php else: ?>
              <dl id="system-message">
                <dt class="notice"><?php echo JText::_('Notice'); ?></dt>
                <dd class="notice message fade">
                  <ul>
                    <li><?php echo JText::_('Notice: Please install JoomlaWorks Simple Image Gallery (Free/Pro) plugin if you want to use the image gallery features of K2!'); ?></li>
                  </ul>
                </dd>
              </dl>
              <?php endif; ?>
              <?php if (count($this->K2PluginsItemGallery)): ?>
              <div class="itemPlugins">
                <?php foreach ($this->K2PluginsItemGallery as $K2Plugin) : ?>
                <?php if(!is_null($K2Plugin)): ?>
                <fieldset>
                  <legend><?php echo $K2Plugin->name; ?></legend>
                  <?php echo $K2Plugin->fields; ?>
                </fieldset>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
            <div class="simpleTabsContent" id="simpleTabsContent4" style="display:none;">
              <?php if ($this->lists['checkAllVideos']): ?>
              <table class="admintable" id="item_video_content">
                <tr>
                  <td align="right" class="key"><?php echo JText::_('Video source'); ?></td>
                  <td>
                  	<?php $pane = & JPane::getInstance('Tabs',$this->options); echo $pane->startPane('myPane'); ?>
                    <?php echo $pane->startPanel(JText::_('Upload video'), 'vidtab1'); ?>
                    <div class="panel" id="Upload_video">
                      <input type="file" name="video" class="text_area" />
                    </div>
                    <?php echo $pane->endPanel(); ?>
                    <?php echo $pane->startPanel(JText::_('Use remote video'), 'vidtab2');	?>
                    <div class="panel" id="Remote_video"><?php echo JText::_('Remote video URL:'); ?>
                      <input type="text" size="40" name="remoteVideo" value="<?php echo $this->lists['remoteVideo'] ?>" />
                    </div>
                    <?php echo $pane->endPanel(); ?>
                    <?php echo $pane->startPanel(JText::_('Video from provider'), 'vidtab3'); ?>
                    <div class="panel" id="Video_from_provider">
                    	<?php echo JText::_('Select video provider'); ?> <?php echo $this->lists['providers']; ?> <?php echo JText::_('and enter video ID:'); ?> <input type="text" name="videoID" value="<?php echo $this->lists['providerVideo'] ?>" />
                    	<br /><br />
                    	<a class="modal" rel="{handler: 'iframe', size: {x: 990, y: 600}}" href="http://www.joomlaworks.gr/content/view/35/41/"><?php echo JText::_('Read the AllVideos documentation for more...'); ?></a>
                    </div>
                    <?php echo $pane->endPanel(); ?>
                    <?php echo $pane->endPane(); ?>
                	</td>
                </tr>
                <tr>
                  <td align="right" class="key"><?php echo JText::_('Video caption'); ?></td>
                  <td><input type="text" name="video_caption" size="50" class="text_area" value="<?php echo $this->row->video_caption; ?>" /></td>
                </tr>
                <tr>
                  <td align="right" class="key"><?php echo JText::_('Video credits'); ?></td>
                  <td><input type="text" name="video_credits" size="50" class="text_area" value="<?php echo $this->row->video_credits; ?>" /></td>
                </tr>
                <?php if (!empty($this->row->video)): ?>
                <tr>
                  <td align="right" class="key"><?php echo JText::_('Video preview'); ?></td>
                  <td>
                    <?php echo $this->row->video; ?>
                    <input type="checkbox" name="del_video" id="del_video" />
                    <label for="del_video"><?php echo JText::_('Use the form above to replace the existing video or check this box to delete current video'); ?></label>
                    </td>
                </tr>
                <?php endif; ?>
              </table>
              <?php else: ?>
              <dl id="system-message">
                <dt class="notice"><?php echo JText::_('Notice'); ?></dt>
                <dd class="notice message fade">
                  <ul>
                    <li><?php echo JText::_('Notice: Please install JoomlaWorks AllVideos plugin if you want to use the video features of K2!'); ?></li>
                  </ul>
                </dd>
              </dl>
              <?php endif; ?>
              
              <?php if (count($this->K2PluginsItemVideo)): ?>
              <div class="itemPlugins">
                <?php foreach ($this->K2PluginsItemVideo as $K2Plugin) : ?>
                <?php if(!is_null($K2Plugin)): ?>
                <fieldset>
                  <legend><?php echo $K2Plugin->name; ?></legend>
                  <?php echo $K2Plugin->fields; ?>
                </fieldset>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
            <div class="simpleTabsContent" id="simpleTabsContent5" style="display:none;">
              <div id="extraFieldsContainer">
              	<?php if (count($this->extraFields)): ?>
                <table class="admintable" id="extraFields">
                  <?php foreach ($this->extraFields as $extraField): ?>
                  <tr>
                    <td align="right" class="key"><?php echo $extraField->name; ?></td>
                    <td><?php echo $extraField->element; ?></td>
                  </tr>
                  <?php endforeach; ?>
                </table>
                <?php else: ?>
	              <dl id="system-message">
	                <dt class="notice"><?php echo JText::_('Notice'); ?></dt>
	                <dd class="notice message fade">
	                  <ul>
	                    <li><?php echo JText::_('Please select a category first to retrieve its related "Extra Fields"...'); ?></li>
	                  </ul>
	                </dd>
	              </dl>
                <?php endif; ?>
          		</div>
              
              <?php if (count($this->K2PluginsItemExtraFields)): ?>
              <div class="itemPlugins">
                <?php foreach ($this->K2PluginsItemExtraFields as $K2Plugin) : ?>
                <?php if(!is_null($K2Plugin)): ?>
                <fieldset>
                  <legend><?php echo $K2Plugin->name; ?></legend>
                  <?php echo $K2Plugin->fields; ?>
                </fieldset>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
            <div class="simpleTabsContent" id="simpleTabsContent6" style="display:none;">
              <div class="itemAttachments">
                <?php if (count($this->row->attachments)): ?>
                <table class="adminlist">
                  <tr>
                    <th><?php echo JText::_('Filename'); ?></th>
                    <th><?php echo JText::_('Title'); ?></th>
                    <th><?php echo JText::_('Title attribute'); ?></th>
                    <th><?php echo JText::_('Downloads'); ?></th>
                    <th><?php echo JText::_('Operations'); ?></th>
                  </tr>
                  <?php foreach ($this->row->attachments as $attachment) : ?>
                  <tr>
                    <td class="attachment_entry"><?php echo $attachment->filename; ?></td>
                    <td><?php echo $attachment->title; ?></td>
                    <td><?php echo $attachment->titleAttribute; ?></td>
                    <td><?php echo $attachment->hits; ?></td>
                    <td><a href="index.php?option=com_k2&amp;view=item&amp;task=download&amp;id=<?php echo $attachment->id ?>"><?php echo JText::_('Download'); ?></a> <a class="deleteAttachmentButton" href="index.php?option=com_k2&amp;view=item&amp;task=deleteAttachment&amp;id=<?php echo $attachment->id?>&amp;cid=<?php echo $this->row->id; ?>"><?php echo JText::_('Delete'); ?></a></td>
                  </tr>
                  <?php endforeach; ?>
                </table>
                <?php endif; ?>
              </div>
              
              <div style="padding:0 16px;">
              	<input type="button" value="<?php echo JText::_('Add attachment field'); ?>" onclick="addAttachment();" />
              </div>

              <div id="itemAttachments"></div>
              
              <?php if (count($this->K2PluginsItemAttachments)): ?>
              <div class="itemPlugins">
                <?php foreach ($this->K2PluginsItemAttachments as $K2Plugin) : ?>
                <?php if(!is_null($K2Plugin)): ?>
                <fieldset>
                  <legend><?php echo $K2Plugin->name; ?></legend>
                  <?php echo $K2Plugin->fields; ?>
                </fieldset>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
            <?php if (count($this->K2PluginsItemOther)): ?>
            <div class="simpleTabsContent" id="simpleTabsContent7" style="display:none;">
              <div class="itemPlugins">
                <?php foreach ($this->K2PluginsItemOther as $K2Plugin) : ?>
                <?php if(!is_null($K2Plugin)): ?>
                <fieldset>
                  <legend><?php echo $K2Plugin->name; ?></legend>
                  <?php echo $K2Plugin->fields; ?>
                </fieldset>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
          <!-- Tabs end here -->
          <input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
          <input type="hidden" name="option" value="<?php echo $option; ?>" />
          <input type="hidden" name="view" value="item" />
          <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
          <?php echo JHTML::_('form.token'); ?></td>
        <td valign="top" width="300" style="padding-left:5px;">
        	<table width="100%" style="border:1px dashed #ccc;padding:4px;margin-bottom:10px;">
            <?php if ($this->row->id): ?>
            <tr>
              <td><strong><?php echo JText::_('Item ID'); ?></strong></td>
              <td><?php echo $this->row->id; ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('State'); ?></strong></td>
              <td><?php echo ($this->row->published > 0) ? JText::_('Published') : JText::_('Unpublished'); ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Featured'); ?></strong></td>
              <td><?php echo ($this->row->featured > 0) ? JText::_('Featured'):	JText::_('Not featured'); ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Created by'); ?></strong></td>
              <td><?php echo $this->row->author; ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Modified by'); ?></strong></td>
              <td><?php echo $this->row->moderator; ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Created'); ?></strong></td>
              <td><?php if ( $this->row->created == $nullDate ) {	echo JText::_('New document');} 
			else { echo JHTML::_('date', $this->row->created, JText::_('DATE_FORMAT_LC2'));} ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Modified'); ?></strong></td>
              <td><?php if ( $this->row->modified == $nullDate ) {	echo JText::_('Never');} 
			else { echo JHTML::_('date', $this->row->modified, JText::_('DATE_FORMAT_LC2'));} ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Hits'); ?></strong></td>
              <td><?php	echo $this->row->hits; ?></td>
            </tr>
            <?php endif; ?>
            <tr>
              <td><strong><?php echo JText::_('Max upload size'); ?></strong></td>
              <td><?php echo ini_get('upload_max_filesize'); ?></td>
            </tr>
          </table>
          <?php $pane = & JPane::getInstance('sliders', array('allowAllClose' => true)); echo $pane->startPane('myPane2'); ?>
          <?php echo $pane->startPanel(JText::_('Details'), 'details'); ?>
          <table class="admintable">
            <tr>
              <td align="right" class="key"><?php echo JText::_('Published'); ?></td>
              <td><?php echo $this->lists['published']; ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Featured'); ?></td>
              <td><input type="checkbox" name="featured" <?php echo $this->row->featured?'checked="checked"':''; ?> value="1" /></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Category'); ?></td>
              <td><?php echo $this->lists['categories']; ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Author'); ?></td>
              <td><?php echo $this->lists['authors']; ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Author alias'); ?></td>
              <td><input class="text_area" type="text" name="created_by_alias" maxlength="250" value="<?php echo $this->row->created_by_alias; ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Access level'); ?></td>
              <td><?php echo $this->lists['access']; ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Creation date'); ?></td>
              <td class="k2ItemFormDateField"><?php echo JHTML::_( 'calendar',$this->row->created, 'created', 'created', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Start publishing'); ?></td>
              <td class="k2ItemFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Finish publishing'); ?></td>
              <td class="k2ItemFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
          </table>
          <?php	echo $pane->endPanel(); ?>
          <?php	echo $pane->startPanel(JText::_('Item tags'), 'item_tags');	?>
          <table class="admintable" width="100%">
            <tr>
              <td><div style="float:left">
                  <input type="text" name="tag" id="tag" />
                  <input type="button" id="newTagButton" value="<?php echo JText::_('Add'); ?>" />
                </div>
                <div style="margin-left:5px;float:left; min-height:16px; min-width:16px;" id="tagsLog"></div>
                <div class="clr"></div>
                <div style="margin-top:5px;"><?php echo JText::_('Note: New tags are appended at the bottom'); ?></div></td>
            </tr>
            <tr>
              <td><table width="100%">
                  <tr>
                    <td align="center" width="40%"><?php echo $this->lists['tags'];	?></td>
                    <td align="center" width="20%"><input type="button" id="addTagButton" value="&raquo;" />
                      <input type="button" id="removeTagButton" value="&laquo;" /></td>
                    <td align="center" width="40%"><?php echo $this->lists['selectedTags']; ?></td>
                  </tr>
                </table></td>
            </tr>
          </table>
          <?php echo $pane->endPanel(); ?> <?php echo $pane->startPanel(JText::_('Metadata information'), "metadata-page"); ?>
          <table class="admintable">
            <tr>
              <td align="right" class="key"><?php echo JText::_('Description'); ?></td>
              <td><textarea rows="5" name="metadesc" cols="30"><?php echo $this->row->metadesc; ?></textarea></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Keywords'); ?></td>
              <td><textarea rows="5" name="metakey" cols="30"><?php echo $this->row->metakey; ?></textarea></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Robots'); ?></td>
              <td><input type="text" name="meta[robots]" size="30" value="<?php echo $this->lists['metadata']->get('robots'); ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Author'); ?></td>
              <td><input type="text" name="meta[author]" size="30" value="<?php echo $this->lists['metadata']->get('author'); ?>" /></td>
            </tr>
          </table>
          <?php	echo $pane->endPanel(); ?>
          <?php echo $pane->startPanel(JText::_('Item view options in category listings'), "item-view-options-listings"); ?> <?php echo $this->form->render('params','item-view-options-listings'); ?> <?php echo $pane->endPanel(); ?> <?php echo $pane->startPanel(JText::_('Item view options'), "item-view-options"); ?> <?php echo $this->form->render('params','item-view-options'); ?> <?php echo $pane->endPanel(); ?> <?php echo $pane->endPane(); ?></td>
      </tr>
    </tbody>
  </table>
  <div class="clr"></div>
</form>
