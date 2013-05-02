<?php
/**
 * Left right image slideshow
 *
 * @package Left right image slideshow
 * @subpackage Left right image slideshow
 * @version   2.0 Februray, 2012
 * @author    Gopi http://www.gopiplus.com
 * @copyright Copyright (C) 2010 - 2012 www.gopiplus.com, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die;

if ( ! empty($images) ) 
{
	@$slideshow_link	= $params->get('slideshow_link');
	
	@$slideshow_width = $params->get('slideshow_width');
	@$slideshow_height = $params->get('slideshow_height');
	@$slideshow_pause = $params->get('slideshow_pause');
	@$slideshow_cycles = $params->get('slideshow_cycles');
	@$slideshow_slideduration = $params->get('slideshow_slideduration');
	@$slideshow_persist = $params->get('slideshow_persist');	
	
	if(!is_numeric(@$slideshow_width)) { @$slideshow_width = 200; }
	if(!is_numeric(@$slideshow_height)) { @$slideshow_height = 150; }
	if(!is_numeric(@$slideshow_pause)) { @$slideshow_pause = 3000; }
	if(!is_numeric(@$slideshow_cycles)) { @$slideshow_cycles = 10; }
	if(!is_numeric(@$slideshow_slideduration)) { @$slideshow_slideduration = 300; }
	
	@$slideshow_path = "";
	@$slideshow_returnstr = "";
	foreach ( $images as $images ) 
	{
		$slideshow_path = JURI::base().$folder .DS. $images->name;
		$slideshow_path = str_replace('\\', '/', $slideshow_path);
		
		if($slideshow_link == "" )
		{
			$slideshow_link =  '#';
		}
		$slideshow_returnstr = $slideshow_returnstr .'["'.$slideshow_path.'", "'.$slideshow_link.'", "_self"],';
	}
	$slideshow_returnstr = substr($slideshow_returnstr,0,(strlen($slideshow_returnstr)-1));
}
@$moduleclass_sfx	= $params->get('moduleclass_sfx');
?>
<script type="text/javascript">
	var Lrisg_SlideShow=new Lrisg_Show({
		Lrisg_Wrapperid: "lrisg_<?php echo @$moduleclass_sfx; ?>", 
		Lrisg_WidthHeight: [<?php echo $slideshow_width; ?>, <?php echo $slideshow_height; ?>], 
		Lrisg_ImageArray: [ <?php echo $slideshow_returnstr; ?> ],
		Lrisg_Displaymode: {type:'auto', pause:<?php echo $slideshow_pause; ?>, cycles:<?php echo $slideshow_cycles; ?>, pauseonmouseover:true},
		Lrisg_Orientation: "h", 
		Lrisg_Persist: <?php echo $slideshow_persist; ?>, 
		Lrisg_Slideduration: <?php echo $slideshow_slideduration; ?> 
	})
</script>
<div id="lrisg_<?php echo @$moduleclass_sfx; ?>"></div>