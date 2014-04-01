var JV_Collapse = new Class({

	initialize: function(myElements) {
		options = Object.extend({			
			transition: Fx.Transitions.quadOut
		}, {});
			
		if(!exModules) var exModules = [];
		
		myElements.each(function(el){
			//el = this.getDeepestDiv(el);
			if ($E('.jv-collapse',el)) return;
			var va = el.getElementsByTagName('h3');
			
			if (typeof(va) == 'undefined') return;
			
			var titleCon = va[0].childNodes[0];
			if( typeof(titleCon) == 'undefined'){return; }
			
			el.titleSpan = $E('.jv-spancoll',el);
			el.titleSpan.setStyle('cursor','pointer');
			el._id = titleCon.innerHTML.trim().replace (' ', '_');

			if (exModules.contains(el._id)) {
				return;
			}
			//title.remove();
			el.divContent = $E('.jvmod-cc',el);
			el.divContent.innerHTML = '<div class="jv-collapse">' + el.divContent.innerHTML + '</div>';
			
			el.titleEl = titleCon;
			el.elmain = $E('.jv-collapse',el);

			el.status = Cookie.get(el._id);
			el.openH = el.elmain.getStyle('height').toInt();
			el.elmain.setStyle ('overflow','hidden');

			if(el.status == 'hide') {
				el.titleEl.className='h3hide';
				el.titleSpan.addClass('hide');
				//el.elmain.setStyle('height', 0);
			} else {
				el.titleEl.className='h3show';
				el.titleSpan.addClass('show');
				el.status = 'show';
			}
			
			el.titleSpan.addEvent('click', function(e){
				e = new Event(e).stop();
				el.toggle();
			});	
			
			el.toggle = function(){
				if (el.status=='hide') el.show();
				else el.hide();
			}	
			
			el.show = function() {
				el.titleEl.className='h3show';
				el.titleSpan.removeClass('hide');
				el.titleSpan.addClass('show');
				var ch = el.elmain.getCoordinates().height;
				new Fx.Style(el.elmain,'height',{onComplete:el.toggleStatus}).start(ch,el.openH);
			}	
			el.hide = function() {
				el.titleEl.className='h3hide';
				el.titleSpan.removeClass('show');
				el.titleSpan.addClass('hide');
				var ch = el.elmain.getCoordinates().height;
				new Fx.Style(el.elmain,'height',{onComplete:el.toggleStatus}).start(ch,0);				
			}
			el.toggleStatus = function () {
				el.status=(el.titleEl.className=='h3show')?'show':'hide';
				if (el.status == 'show')
				{
					el.elmain.setStyle ('height', 'auto');
					el.openH = el.elmain.getCoordinates().height;
				}
				Cookie.set(el._id,el.status,{duration:365});
			}				
			
			if(el.status=='hide') el.hide();

		}, this);
	},

	getDeepestDiv: function (div) {
		while (div.getChildren().length && (div.getChildren()[0].tagName == 'DIV'))
		{
			div = div.getChildren()[0];
		}
		return div;
	}
});

window.addEvent ('load', function(e){
	var jvcols = new JV_Collapse ($ES('.module', $('jv-colleft')));
	var jvcols1 = new JV_Collapse ($ES('.module', $('jv-colright')));
	var jvcols2 = new JV_Collapse ($ES('.module-gray', $('jv-colleft')));
	var jvcols2 = new JV_Collapse ($ES('.module-gray', $('jv-colright')));
	var jvcols_menu = new JV_Collapse ($ES('.module_menu', $('jv-colleft')));
	var jvcols_menu1 = new JV_Collapse ($ES('.module_menu', $('jv-colright')));
	var jvcols_text = new JV_Collapse ($ES('.module_text', $('jv-colleft')));
	var jvcols_text1 = new JV_Collapse ($ES('.module_text', $('jv-colright')));
	var jvcols_cat = new JV_Collapse ($ES('.module-catmenu', $('jv-colleft')));
	var jvcols_cat1 = new JV_Collapse ($ES('.module-catmenu', $('jv-colright')));
	//var jvcols_black = new JV_Collapse ($ES('.module-black', $('jv-colleft')));
	//var jvcols_black1 = new JV_Collapse ($ES('.module-black', $('jv-colright')));
	var jvcols_accordion1 = new JV_Collapse ($ES('.module-accordion', $('jv-colright')));
});
