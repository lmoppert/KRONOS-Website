<?php

/**
* @Copyright (C) 2011 - DM Digital
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

?>

<script>
	function dmSSlideDown_Toggle( id ){
		jQuery( '#dmSSlideDown_' + id + '_spoilcont' ).slideToggle();
		
		var link = document.getElementById('dmSSlideDown_' + id + '_a').innerHTML;
		
		if (link == "Read More") {
			document.getElementById('dmSSlideDown_' + id + '_a').innerHTML = "Read Less";
			}
		else {document.getElementById('dmSSlideDown_' + id + '_a').innerHTML = "Read More";}
	}
</script>
