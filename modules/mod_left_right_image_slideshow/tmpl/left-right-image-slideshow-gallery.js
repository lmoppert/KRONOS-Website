
jQuery.noConflict()

function Lrisg_Show(options){
	var $=jQuery
	this.setting={Lrisg_Displaymode:{type:'auto', pause:2000, cycles:2, pauseonmouseover:true}, Lrisg_Orientation:'h', Lrisg_Persist:true, slideduration:500} //default settings
	jQuery.extend(this.setting, options) //merge default settings with options
	var curslide=(this.setting.Lrisg_Persist)? Lrisg_Show.routines.getCookie("slider-"+this.setting.Lrisg_Wrapperid) : 0
	this.curslide=(curslide==null || curslide>this.setting.Lrisg_ImageArray.length-1)? 0 : parseInt(curslide) //make sure curslide index is within bounds
	this.curstep=0
	this.zIndex=1
	this.animation_isrunning=false //variable to indicate whether an image is currently being slided in
	this.posprop=(this.setting.Lrisg_Orientation=="h")? "left" : "top"
	options=null
	var slideshow=this, setting=this.setting, preloadimages=[], slidesHTML=''
	for (var i=0; i<setting.Lrisg_ImageArray.length; i++){ //preload images
		preloadimages[i]=new Image()
		preloadimages[i].src=setting.Lrisg_ImageArray[i][0]
		slidesHTML+=Lrisg_Show.routines.getSlideHTML(setting.Lrisg_ImageArray[i], setting.Lrisg_WidthHeight[0]+'px', setting.Lrisg_WidthHeight[1]+'px', this.posprop)+'\n'
	}
	jQuery(function($){ //on document.ready
		slideshow.init($, slidesHTML)
	})
	$(window).bind('unload', function(){ //on window onload
		if (slideshow.setting.Lrisg_Persist) //remember last shown slide's index?
			Lrisg_Show.routines.setCookie("slider-"+setting.Lrisg_Wrapperid, slideshow.curslide)
	})
}

Lrisg_Show.prototype={

	slide:function(nextslide, dir){ //possible values for dir: "left", "right", "top", or "down"
		if (this.curslide==nextslide)
			return
		var slider=this
		var nextslide_initialpos=this.setting.Lrisg_WidthHeight[(dir=="right"||dir=="left")? 0 : 1] * ((dir=="right"||dir=="down")? -1 : 1)
		var curslide_finalpos=-nextslide_initialpos
		var posprop=this.posprop
		if (this.animation_isrunning!=null)
			this.animation_isrunning=true //indicate animation is running
		this.$imageslides.eq(dir=="left"||dir=="top"? nextslide : this.curslide).css("zIndex", ++this.zIndex) //increase zIndex of upcoming slide so it overlaps outgoing
		this.$imageslides.eq(nextslide).css(Lrisg_Show.routines.createobj(['visibility', 'visible'], [posprop, nextslide_initialpos])) //show upcoming slide
			.animate(Lrisg_Show.routines.createobj([posprop, 0]), this.setting.slideduration, function(){slider.animation_isrunning=false})
		this.$imageslides.eq(this.curslide).animate(Lrisg_Show.routines.createobj([posprop, curslide_finalpos]), this.setting.slideduration, function(){jQuery(this).css("visibility", "hidden")}) //hide outgoing slide
		this.curslide=nextslide
	},

	navigate:function(keyword){ //keyword: "back" or "forth"
		clearTimeout(this.rotatetimer)
		var dir=(keyword=="back")? (this.setting.Lrisg_Orientation=="h"? "right" : "down") : (this.setting.Lrisg_Orientation=="h"? "left" : "up")
		var targetslide=(keyword=="back")? this.curslide-1 : this.curslide+1
		targetslide=(targetslide<0)? this.$imageslides.length-1 : (targetslide>this.$imageslides.length-1)? 0 : targetslide //wrap around
		if (this.animation_isrunning==false)
			this.slide(targetslide, dir)
	},

	rotate:function(){
		var slideshow=this
		if (this.ismouseover){ //pause slideshow onmouseover
			this.rotatetimer=setTimeout(function(){slideshow.rotate()}, this.setting.Lrisg_Displaymode.pause)
			return
		}
		var nextslide=(this.curslide<this.$imageslides.length-1)? this.curslide+1 : 0
		this.slide(nextslide, this.posprop) //go to next slide, either to the left or upwards depending on setting.Lrisg_Orientation setting
		if (this.setting.Lrisg_Displaymode.cycles==0 || this.curstep<this.maxsteps-1){
			this.rotatetimer=setTimeout(function(){slideshow.rotate()}, this.setting.Lrisg_Displaymode.pause)
			this.curstep++
		}
	},

	init:function($, slidesHTML){
		var slideshow=this, setting=this.setting
		this.$wrapperdiv=$('#'+setting.Lrisg_Wrapperid).css({position:'relative', visibility:'visible', overflow:'hidden', width:setting.Lrisg_WidthHeight[0], height:setting.Lrisg_WidthHeight[1]}) //main DIV
		if (this.$wrapperdiv.length==0){ //if no wrapper DIV found
			alert("Error: DIV with ID \""+setting.Lrisg_Wrapperid+"\" not found on page.")
			return
		}
		this.$wrapperdiv.html(slidesHTML)
		this.$imageslides=this.$wrapperdiv.find('div.slide')
		this.$imageslides.eq(this.curslide).css(Lrisg_Show.routines.createobj([this.posprop, 0])) //set current slide's CSS position (either "left" or "top") to 0
		if (this.setting.Lrisg_Displaymode.type=="auto"){ //auto slide mode?
			this.setting.Lrisg_Displaymode.pause+=this.setting.slideduration
			this.maxsteps=this.setting.Lrisg_Displaymode.cycles * this.$imageslides.length
			if (this.setting.Lrisg_Displaymode.pauseonmouseover){
				this.$wrapperdiv.mouseenter(function(){slideshow.ismouseover=true})
				this.$wrapperdiv.mouseleave(function(){slideshow.ismouseover=false})
			}
			this.rotatetimer=setTimeout(function(){slideshow.rotate()}, this.setting.Lrisg_Displaymode.pause)
		}
	}

}

Lrisg_Show.routines={

	getSlideHTML:function(imgref, w, h, posprop){
		var posstr=posprop+":"+((posprop=="left")? w : h)
		/*var layerHTML=(imgref[1])? '<a href="'+imgref[1]+'" target="'+imgref[2]+'">' : '' //hyperlink slide?
		layerHTML+='<img src="'+imgref[0]+'" style="border-width:0;" />'
		layerHTML+=(imgref[1])? '</a>' : ''*/
		var layerHTML='<img src="'+imgref[0]+'" style="border-width:0;" />'
		return '<div class="slide" style="position:absolute;'+posstr+';width:'+w+';height:'+h+';text-align:center;">'
			+'<div style="width:'+w+';height:'+h+';display:table-cell;vertical-align:middle;">'
			+layerHTML
			+'</div></div>' //return HTML for this layer
	},


	getCookie:function(Name){ 
		var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
		if (document.cookie.match(re)) //if cookie found
			return document.cookie.match(re)[0].split("=")[1] //return its value
		return null
	},

	setCookie:function(name, value){
		document.cookie = name+"=" + value + ";path=/"
	},

	createobj:function(){
		var obj={}
		for (var i=0; i<arguments.length; i++){
			obj[arguments[i][0]]=arguments[i][1]
		}
		return obj
	}
}