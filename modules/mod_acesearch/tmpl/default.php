<?php
/**
* @version		1.5.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<script type="text/javascript">
	<?php if($params->get('show_extra_fields', '1') == '1' && $params->get('show_sections', '0') == '1') { ?>
        window.onload = function AcesearchModule(){
            url = '<?php echo JRoute::_("index.php?option=com_acesearch&task=changeExtensionMod&format=raw".$flter, false); ?>&ext=<?php echo JRequest::getCmd('ext', '', 'post'); ?>';
            new Request({method: "get", url: url, onComplete : function(result) {$('custom_fields_module').innerHTML = result;}}).send();
        }
	<?php
	}

	if ($params->get('enable_complete', '0') == '1') { ?>
	window.addEvent('load', function() {
		var url = '<?php echo JRoute::_("index.php?option=com_acesearch&task=complete&format=raw", false); ?>';
		var completer = new Autocompleter.Ajax.Json($('qr'), url, {'postVar': 'q'});
	});
	<?php } 
	
	if ($params->get('show_extra_fields', '1') == '1' && $params->get('show_sections', '0') == '1') { ?>

	function changeExtModule(a){
		url = '<?php echo JRoute::_("index.php?option=com_acesearch&task=changeExtensionMod&format=raw".$flter, false); ?>&ext='+a;
		new Request({method: "get", url: url, onComplete : function(result) {$('custom_fields_module').innerHTML = result;}}).send();
	}
	<?php } ?>
	
	function acesearchsubmit(){
		var moquery = document.getElementById("qr").value.length;
		
		if (moquery >= "<?php echo $AcesearchConfig->search_char; ?>"  ) {
			return true;
		} 
		else {
			alert("<?php echo JText::_('MOD_ACESEARCH_QUERY_ERROR'). ' ' .$AcesearchConfig->search_char. ' ' .JText::_('MOD_ACESEARCH_QUERY_ERROR_CHARS');?>");
			return false;
		}
	}
</script>

<form id="acesearchModule" action="<?php echo JRoute::_('index.php?option=com_acesearch&view=search'); ?>" method="post" name="acesearchModule" onsubmit="return acesearchsubmit();">
	<div class="search<?php echo $params->get('moduleclass_sfx', ''); ?> acesearch_bg_module">
		<?php
		echo $output;
		echo $order;
		echo $section;
		
		if ($params->get('show_button', '1') == '1') {	?>
			<button type="submit" class="<?php echo $params->get('button_class', 'acesearch_button');?>" id="module_button"><?php echo JText::_('COM_ACESEARCH_SEARCH'); ?></button>
			<?php
		}
		
		echo $advanced;
		echo $hidden;
		?>
	</div>
	
	<input type="hidden" name="option" value="com_acesearch"/>
	<input type="hidden" name="view" value="search"/>
	<input type="hidden" name="task" value="search"/>
</form>
<div class="acesearch_clear"></div>