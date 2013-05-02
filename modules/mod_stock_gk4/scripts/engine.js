window.addEvent("load",function(){
	$$(".gks_main").each(function(el,i){
		var module_id = el.getProperty("id");
		var wrapper = el;
		var triggers = wrapper.getElements('.gks_trigger');
		var links = wrapper.getElements('.gks_stock_name');
		var links_wrap = wrapper.getElements('.gks_stock');
		var tooltip_data = wrapper.getElements('.gks_tooltip_stock');
		var tooltip_class = 'ts-left';
		var tooltip_size = 580;
		if(tooltip_data.length > 0){
			var tooltip = wrapper.getElement(".gks_tooltip");
			
			if(tooltip.hasClass('ts-right')) {
				tooltip_class = 'ts-right';
			} else if(tooltip.hasClass('ts-bottom')) {
				tooltip_class = 'ts-bottom';
			}
			
			if(tooltip.hasClass('tp-tooltip2')) {
				tooltip_size = 290;
			}
			
			tooltip.inject(document.id(document.body));
			tooltip.setStyle("display","block");
			var tooltipFX = new Fx.Tween(tooltip,{duration:250, property: 'opacity',link:'cancel'});
			tooltipFX.set(0);
		}
		var active = [];
		var tooltip_pos = {
			"ts-left" : { top: 10, left: tooltip_size},
			"ts-right" : { top: 10, left: -20},
			"ts-bottom" : { top: -30, left: tooltip_size / 2}
		};
		
		if(wrapper.getElement('.first')) active[0] = wrapper.getElement('.first').getProperty("title");
		if(wrapper.getElement('.second')) active[1] = wrapper.getElement('.second').getProperty("title");
		if(wrapper.getElement('.third')) active[2] = wrapper.getElement('.third').getProperty("title");
		if(wrapper.getElement('.fourth')) active[3] = wrapper.getElement('.fourth').getProperty("title");
		var counter_max = active.length;
		var counter = 0;
		
		if(tooltip_data.length > 0){
			links.each(function(elmt,j){
				elmt.addEvent("mouseenter",function(e){
					var evt = new Event(e);
					tooltip.setStyles({
						"top" : (evt.page.y - tooltip_pos[tooltip_class].top)+"px",
						"left" : (evt.page.x - tooltip_pos[tooltip_class].left)+"px"
					});
					tooltip.innerHTML = tooltip_data[j].innerHTML;
					tooltipFX.start(1);
				});
				elmt.addEvent("mouseleave",function(){
					tooltipFX.start(0);
				});
			});
			
			links_wrap.each(function(elmt,j){
				elmt.addEvent("mouseover",function(e){
					var evt = new Event(e);
					if(evt.target != links[j]){
						tooltipFX.start(0);
					}
				});
			});
		}
		//
		if(triggers.length > 4) {
			triggers.each(function(elmt,j){
				elmt.addEvent("click", function(){
					if(
						elmt != wrapper.getElement('.first') && 
						elmt != wrapper.getElements('.second') &&
						elmt != wrapper.getElements('.third') &&
						elmt != wrapper.getElements('.fourth')
					){
						switch(counter) {
							case 0:
								wrapper.getElement('.first').toggleClass('first');
								elmt.toggleClass('first');
								active[0] = elmt.getProperty("title"); 
							break;
							case 1:
								wrapper.getElement('.second').toggleClass('second');
								elmt.toggleClass('second');
								active[1] = elmt.getProperty("title"); 
							break;
							case 2:
								wrapper.getElement('.third').toggleClass('third');
								elmt.toggleClass('third');
								active[2] = elmt.getProperty("title"); 
							break;
							case 3:
								wrapper.getElement('.fourth').toggleClass('fourth');
								elmt.toggleClass('fourth');
								active[3] = elmt.getProperty("title"); 
							break;
						}
						
						counter = (counter + 1 < counter_max) ? counter + 1 : 0;
						
						var oldImg = wrapper.getElement('.gks_img');
						var newImg = new Asset.image('http://www.google.com/finance/chart?cht=c&q='+active.join(',')+'&nc='+$random(10000,99999), {"class": 'gks_img', onload: function(){
							new Fx.Tween(wrapper.getElement('.gks_img'),{duration:350, property:'opacity',link:'ignore'}).start(0);
							(function(){
								newImg.setStyle('opacity', 0);
								newImg.inject(oldImg, 'before');
								new Fx.Tween(newImg,{duration:350, property: 'opacity',link:'ignore'}).start(1);								
							}).delay(350);
							(function(){oldImg.setStyle("display","none");}).delay(700);			
						}
						});					
					}
				});
			});
		}
	});
});