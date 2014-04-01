<?php
/*======================================================================*\
|| #################################################################### ||
|| # Youjoomla.com - YJ- Licence Number 74460-GX980604
|| # Licensed to - Lorenzo Vergel
|| # ---------------------------------------------------------------- # ||
|| # Copyright (C) 2010  Youjoomla.com. All Rights Reserved.            ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla.com                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php  if(isset($tw_json) && is_array($tw_json)){
		foreach($tw_json as $key => $tw_post): 
			$tweet_title = $tweet_limit > 0 ? substr($tw_post->text,0,$tweet_limit)."..." : $tw_post->text;
			$tweet_title = $tweet_link == 1 ? "<a href='http://twitter.com/".$twitter_user."/statuses/".$tw_post->id_str."' target='_blank'>".$tweet_title."</a>" : $tweet_title ;		
		?>
        <div class="yj_twitter_cont">
         <div class="yj_twitter_title"><?php echo $tweet_title ?></div>
         <?php if($tweet_date == 1) { ?>
         	<div class="yj_twitter_updated"><?php echo ago($tw_post->created_at) ?></div>
         <?php } ?>
        </div>
        <?php endforeach; ?>
         <?php if($tweet_image == 1 || $tweet_follow == 1) { ?>
              <div class="yj_twitter_footer">
                <?php if($tweet_image == 1) { ?>
                <div class="yj_twitter_image"><img src="<?php echo $tw_post->user->profile_image_url ?>" alt="<?php echo $tw_post->user->name ?>" /></div>
                <?php } ?>
                <?php if($tweet_follow == 1) { ?>
                <div class="yj_twitter_follow"><a href="http://twitter.com/<?php echo $twitter_user ?>" target="_blank"><?php echo JText::_( 'TWITTER_FOLLOW_ME' ) ?></a></div>
                <?php } ?>       
              </div>
        <?php } ?>  
<?php 
}else{
	echo JText::_( 'SERVICE_UNAVAILABLE' );
}?>