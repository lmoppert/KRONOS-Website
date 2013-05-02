<?php

	/**
	* @Copyright (C) 2011 - DM Digital
	* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	//--- no direct access
		defined( '_JEXEC' ) or die( 'Restricted access' );
	
	$output .= 	'
				<script>
					function dmSSlideDown_Toggle( id ){
						jQuery( \'#dmSSlideDown_\' + id + \'_spoilcont\' ).slideToggle();
					}
				</script>
				';
	
?>	