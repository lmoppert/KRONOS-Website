$(document).ready(function(){

$('#map').zoommap({
		// Width and Height of the Map
		width: '735px',
		height: '460px',
			
		//Misc Settings
		blankImage: 'images/blank.png',
		zoomDuration: 1000,
		bulletWidthOffset: '10px',
		bulletHeightOffset: '10px',
		
		//ids and classes
		zoomClass: 'zoomable',
		popupSelector: 'div.popup',
		popupCloseSelector: 'a.close',
		
		//Return to Parent Map Link
		showReturnLink: true,
		returnId: 'returnlink',
		returnText: 'Return to world map',
		
		//Initial Region to be shown
		map: {
			id: 'world',
			image: 'images/na_euroMap.png',
			data: 'popups/world.html',
			maps: [
			{
				id: 'euro',
				parent: 'world',
				image: 'images/euroMap.png',
				data: 'popups/euro.html',
				width: '75px',
				height: '100px',
				top: '65px',
				left: '570px'
				/* More maps can be nested
				maps : [ ]
				*/
			}
			]
		}
	});


});
