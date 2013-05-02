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

$filter = JRequest::getInt('filter');
?>

<script type="text/javascript">
	function submitbutton(){
		var query = document.getElementById("q").value.length;
		if (query >= "<?php echo $this->AcesearchConfig->search_char; ?>") {
			return true;
		}
		else {
			alert("<?php echo JText::_('COM_ACESEARCH_QUERY_ERROR'). ' ' .$this->AcesearchConfig->search_char. ' ' .JText::_('COM_ACESEARCH_QUERY_ERROR_CHARS');?>");
			return false;
		}
	}

	function redirect (link) {
		var query = document.getElementById("q").value;

		if (query != '') {
			document.location.href = link + '&query=' + query;
		}
		else {
			document.location.href = link;
		}
	}

	window.addEvent('load', function() {
	<?php if ($this->AcesearchConfig->enable_complete == '1') { ?>
		$('q').addEvent('focus', function(){
			var url = '<?php echo JRoute::_('index.php?option=com_acesearch&task=complete&format=raw', false); ?>';
			var completer = new Autocompleter.Ajax.Json($('q'), url, {'postVar': 'q'});
		});
	<?php }
		if ($this->AcesearchConfig->yahoo_sections  == '1' && empty($ajax_filter)) { ?>
        if (document.getElementById('more-link')) {
            $('more-link').addEvent('click', function(e){
                e.preventDefault();
                 $("more-menu").setStyle('display','block');
            });
            document.addEvent('mouseup', function(e) {
                $("more-menu").setStyle('display','none');
            });
        }
	<?php } ?>
	});
</script>

<form id="acesearchForm" name="acesearchForm" action="<?php echo JRoute::_(JFactory::getURI()->toString()); ?>" method="post" onsubmit="return submitbutton();" >
	<?php
	$page_title = $this->params->get('page_title', '');
	if (($this->params->get('show_page_heading', '0') == '1') && !empty($page_title)) {
		?><h1><?php
		echo $page_title;
		?></h1><?php
	} 
	?>		
	<fieldset class="acesearch_fieldset">
		<legend class="acesearch_legend"><?php echo JText::_('COM_ACESEARCH_SEARCH'); ?></legend>
		<?php $acesearch_bg = $this->AcesearchConfig->yahoo_sections ? 'acesearch_bg2' : 'acesearch_bg'; ?>
		<div id="<?php echo $acesearch_bg; ?>">
            <?php
            if ($this->AcesearchConfig->yahoo_sections  == '1'){
                echo AcesearchHTML::getExtensions();
            } ?>
			<input class="acesearch_input_image" type="text" name="query" id="q" value="<?php echo $this->query; ?>" maxlength="<?php echo $this->AcesearchConfig->max_search_char; ?>" />&nbsp;
			<?php
			if ($this->AcesearchConfig->show_ext_flt == '1' && $this->AcesearchConfig->yahoo_sections  == '0' && empty($filter)) {
			    if (is_int($this->lists['extension'])) {
					echo '<input type="hidden" name="ext" value="'.$this->lists['ext'].'"/>';
					$this->suffix.='&ext='.$this->lists['ext'];
				}
				else {
					echo $this->lists['extension'];
				}
			}
			?>
			
			<button type="submit" class="acesearch_button"><?php echo JText::_('COM_ACESEARCH_SEARCH' ); ?></button>
			
			<?php 
			if ($this->AcesearchConfig->show_adv_search == '1') {
				?>
				<a  style="font-size:12px;" href="<?php echo JRoute::_('index.php?option=com_acesearch&view=advancedsearch'.$this->suffix .$this->Itemid); ?>" title="<?php echo JText::_('COM_ACESEARCH_SEARCH_ADVANCED_SEARCH'); ?>" >
				<?php echo JText::_('COM_ACESEARCH_SEARCH_ADVANCED_SEARCH'); ?></a>
			<?php } ?>
		</div>
	</fieldset>
	<div class="acesearch_clear"></div>
	
	<?php
	if (!empty($this->query)) {
		if ($this->AcesearchConfig->results_format == '1') {
			echo $this->loadTemplate('results');
		}
		else {
			echo $this->loadTemplate('results_table');
		}
	}
	
	echo $this->hiddenfilt;
	$limit = JRequest::getInt('limit');
	if(!isset($limit )) {
	?>
	<input type="hidden" name="limit" value="<?php echo $this->AcesearchConfig->display_limit; ?>"/><?php } ?>
	<input type="hidden" name="limitstart" value="" />
	<input type="hidden" name="option" value="com_acesearch" />
	<input type="hidden" name="task" value="search" />
</form>
<div class="acesearch_clear"></div>