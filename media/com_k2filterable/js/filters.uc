window.addEvent('domready', function() {
	init ();
});

var lastSelected = false;

function init() {
	addAjaxEvents();
	addSliders();
	$$('#filterForm a.filterButton').addEvent('click', resetAll);
}

function resetAll () {
 $$('#filterForm .ajaxCheckbox').each(function (el) {
	el.set('checked', false);
 });
 doAjaxEvent ();
}

function addSliders() {

    var lastCheckedWasThisDescending = function(input){
	if(input.id === lastSelected){
	    return true;
	}
	return false;
    };

    $$('.descendable span').each(function(heading, i) {
	var collapsible = new Fx.Slide($$('.descendable ul')[i], {
	    duration: 500,
	    transition: Fx.Transitions.Quad.easeInOut
	});
	
	
	heading.onclick = function() {
	    collapsible.toggle().chain(function (){
		heading.getParent().toggleClass('closed');
	    });
	}
	
	var checked = false;
	var shouldBeOpen = false;
	
	heading.getParent().getElements('input').each(function(checkbox, i) {
	    if(checkbox.get('checked')) {
		checked = true;
	    }
	    if(lastCheckedWasThisDescending(checkbox)){
		shouldBeOpen = true;
	    }
	});
	
	if(!shouldBeOpen && !checked) {
	    collapsible.hide();
	    heading.getParent().addClass('closed');
	}
    });
    
    lastCheckedDescends = false;
}

function doAjaxEvent () {
    lastSelected = this.id;
    k2DivLoader('results');
    var formOptions = { 'onComplete' : function(){ init(); }};
    var requestObject = new Form.Request('filterForm', 'k2filterable', formOptions);
    requestObject.send();
}

function addAjaxEvents() {
    $$('.ajaxCheckbox').addEvent('click', doAjaxEvent);
}

if(!displayField){
    var displayField = function(){};
}

function next() {
    var offset = document.getElementById('offset');
    var limit = document.getElementById('limit');
    offset.value = parseInt(offset.value) + parseInt(limit.value);
    var formOptions = { 'onComplete' : function(){ addAjaxEvents(); addSliders(); }};
    var requestObject = new Form.Request('filterForm', 'k2filterable', formOptions);
    requestObject.send();
}

function prev() {
    var offset = document.getElementById('offset');
    var limit = document.getElementById('limit');
    offset.value = parseInt(offset.value) - parseInt(limit.value);
    var formOptions = { 'onComplete' : function(){ addAjaxEvents(); addSliders(); }};
    var requestObject = new Form.Request('filterForm', 'k2filterable', formOptions);
    requestObject.send();
}
function k2DivLoader(container) {	
    document.getElementById(container).style.position = 'relative';
    var divh = document.getElementById(container).clientHeight;
    var divw = document.getElementById(container).clientWidth;
    var t = (divh / 2) - 15;
    if(t > 200) t = 200;
    var l = (divw / 2) - 15;
    var img_loader = '<img src="'+window.com_k2filterable.jbase+'media/com_k2filterable/images/ajax-loader.gif'+'" style="position: absolute; top: '+t+'px; left: '+l+'px"/>';	
    document.getElementById( container ).innerHTML += img_loader+'<div class="k2filterableAjaxGrayDiv" style="width: '+divw+'px; height: '+divh+'px;"></div>';
}
