/*
// "K2" Component by JoomlaWorks for Joomla! 1.5.x - Version 2.1
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr and http://k2.joomlaworks.gr
// Designed and developed by the JoomlaWorks team
// *** Last update: September 9th, 2009 ***
*/

window.addEvent('domready', function(){
	
  // Comments
  if ($('comment-form')) {
    $('comment-form').addEvent('submit', function(e){
      new Event(e).stop();
      var log2 = $('formLog').empty().addClass('formLogLoading');
      this.send({
        update: log2,
        onComplete: function(res){
          log2.removeClass('formLogLoading');
  				if(typeof(Recaptcha) != "undefined"){ 
						Recaptcha.reload();
  				}
          if (res.substr(13, 7) == 'success') {
              window.location.reload();
          }
        }
      });
    });
  }
  
  // Text Resizer
  if ($('fontDecrease')) {
		$('fontDecrease').addEvent('click', function(e){
			new Event(e).stop();
			$$('.itemFullText').removeClass('largerFontSize');
			$$('.itemFullText').addClass('smallerFontSize');
		});
  }
  if ($('fontIncrease')) {
		$('fontIncrease').addEvent('click', function(e){
			new Event(e).stop();
			$$('.itemFullText').removeClass('smallerFontSize');
			$$('.itemFullText').addClass('largerFontSize');
		});
  }
  
  // Smooth Scroll
  new SmoothScroll({
      duration: 500
  });
  	
	// Rating
	if ($$('.itemRatingForm').length > 0) {
		$$('.itemRatingForm a').addEvent('click', function(e){
			e = new Event(e).stop();
			var itemID = this.getProperty('rel');
			var log = $('itemRatingLog' + itemID).empty().addClass('formLogLoading');
			var rating = this.getText();
			var url = K2RatingURL+"index.php?option=com_k2&view=item&task=vote&user_rating=" + rating + "&itemID=" + itemID;
			new Ajax(url, {
				method: "get",
				update: log,
				onComplete: function(){
					log.removeClass('formLogLoading');
					new Ajax(K2RatingURL+"index.php?option=com_k2&view=item&task=getVotesPercentage&itemID=" + itemID, {
						method: "get",
						onComplete: function(percentage){
							$('itemCurrentRating' + itemID).setStyle('width', percentage + "%");
							setTimeout(function(){
								new Ajax(K2RatingURL+"index.php?option=com_k2&view=item&task=getVotesNum&itemID=" + itemID, {
									method: "get",
									update: log
								}).request();
							}, 2000);
						}
					}).request();
				}
			}).request();
		});
	}	
	
});

window.addEvent('load', function(){

  // Equal block heights for the "default" view
  if($$('.subCategory')){
		var blocks = $$('.subCategory');
		var maxHeight = 0;
		blocks.each(function(item){
			maxHeight = Math.max(maxHeight, parseInt(item.getStyle('height')));
		});
		blocks.setStyle('height', maxHeight);
	}
	
});

// End
