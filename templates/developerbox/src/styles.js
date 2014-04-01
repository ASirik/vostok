/*======================================================================*\
|| #################################################################### ||
|| # Package - Joomla Template based on YJSimpleGrid Framework          ||
|| # Copyright (C) 2010  Youjoomla.com. All Rights Reserved.            ||
|| # Authors - Dragan Todorovic and Constantin Boiangiu                 ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla.com                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/

//window.onload=function(){
//	var mainDiv   	= document.getElementById('main_bg');
//	var getHeight 	= mainDiv.offsetHeight;
//	if (getHeight < 900){
//		mainDiv.className = mainDiv.className + "small_bg_2";
//	}
//	else if(getHeight < 1250 ){
//		mainDiv.className = mainDiv.className + "small_bg";
//	}
//}

window.addEvent("load", function() {
   $$('.yj_hover_effect').each(function (el) {
	var parent_width	= el.getParent().getSize().x;
	var parent_height	= el.getParent().getSize().y;
	var get_image       = el.getElement('img');
	var get_title       = el.getElement('.yj_hover_title');
	var title_width	    = get_title.getSize().x;
	var title_height	= get_title.getSize().y;
	el.setStyles({
		'background-color':'#000',
		width:parent_width,
		height:parent_height
	});
	get_title.setStyles({
		left:parent_width/2 - title_width/2,
		top:parent_height/2 - title_height/2
	});
    var fx = new Fx.Morph(get_image, {
      duration: 200,
      'link': 'cancel'
    });
    var fx2 = new Fx.Morph(get_title, {
      duration: 200,
      'link': 'cancel'
    });
    el.addEvents({
      mouseenter: function () {
        fx.start({
		  'opacity':[0.5],
        });
        fx2.start({
		  'opacity':[1],
        });
      },
      mouseleave: function () {
        fx.start({
		  'opacity':[1],
        });
        fx2.start({
		  'opacity':[0],
        });
      }
    });
  });
});
