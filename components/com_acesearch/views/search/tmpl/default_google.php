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
	
	function setLucky() {
		document.acesearchForm.lucky.value = '1';
	}
	
	<?php if ($this->AcesearchConfig->enable_complete == '1') { ?>
	window.addEvent('load', function() {
		var url = '<?php echo JRoute::_('index.php?option=com_acesearch&task=complete&format=raw', false); ?>';
		var completer = new Autocompleter.Ajax.Json($('q'), url, {'postVar': 'q'});
	});
	<?php } ?>
</script>

<form id="acesearchForm" name="acesearchForm" action="<?php echo JRoute::_(JFactory::getURI()->toString()); ?>" method="post" onsubmit="return submitbutton();" >
	<?php
	if (empty($this->query)) {
	?>
		<div class="acesearch_google_search">
			<?php
			$page_title = $this->params->get('page_title', '');
			if (($this->params->get('show_page_heading', '0') == '1') && !empty($page_title)) {
				?><h1 class="acesearch_google_empty"><?php
				echo $page_title;
				?></h1><?php
			} 
			?>
		
			<input class="acesearch_google_search_input" style="width:75%;" type="text" name="query" id="q" value="<?php echo $this->query; ?>" maxlength="<?php echo $this->AcesearchConfig->max_search_char; ?>" />&nbsp;
			
			<?php if ($this->AcesearchConfig->show_adv_search == '1') {	?>
				<a class="acesearch_google_results_about" style="font-size:13px;  color: #3366CC;" href="<?php echo JRoute::_('index.php?option=com_acesearch&view=advancedsearch'.$this->suffix .$this->Itemid); ?>" title="<?php echo JText::_('COM_ACESEARCH_SEARCH_ADVANCED_SEARCH'); ?>" >
					<?php echo JText::_('COM_ACESEARCH_SEARCH_ADVANCED_SEARCH'); ?>
				</a>
			<?php }	?>
		
			<div class="acesearch_clear"></div>
		
			<button type="submit" class="acesearch_button_google" onclick="this.checked=1"><?php echo JText::_('COM_ACESEARCH_SEARCH'); ?></button>
			<button type="submit" class="acesearch_button_google" style="width:140px;" onClick="setLucky();"><?php echo JText::_("I'm feeling lucky"); ?></button>
		</div>
		
		<div class="acesearch_clear"></div>
		<div class="acesearch_clear"></div>
	<?php
	} else {
		echo $this->loadTemplate('results_google');
	}
	
	echo $this->hiddenfilt;
	
	$limit = JRequest::getInt('limit');
	if(!isset($limit)) { ?>
	<input type="hidden" name="limit" value="<?php echo $this->AcesearchConfig->display_limit; ?>"/>
	<?php } ?>
	<input type="hidden" name="limitstart" value="" />
	<input type="hidden" name="option" value="com_acesearch" />
	<input type="hidden" name="task" value="search" />
	<input type="hidden" name="lucky" value="0" />
</form>
<div class="acesearch_clear"></div>