<?php
/**
* @version		1.5.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
//No Permision
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.module.helper');

$lang = JFactory::getLanguage();
$lang->load('com_acesearch' , JPATH_SITE);
$lang->load('mod_acesearch' , JPATH_SITE);

$loader = JPATH_ADMINISTRATOR.'/components/com_acesearch/library/loader.php';
if (!file_exists($loader)) {
    return;
}

require_once($loader);
$AcesearchConfig = AcesearchFactory::getConfig();

$disable_autocompliter = false;

$option = JRequest::getCmd('option');
$view = JRequest::getCmd('view');
if ($option == 'com_k2' && ($view == 'item' || $view == 'extraField')){
    $disable_autocompliter = true;
}

$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_acesearch/assets/css/acesearch.css');
$document->addStyleSheet('../components/com_acesearch/assets/css/acesearch.css');

if ($params->get('admin_enable_complete','1')== '1' && $disable_autocompliter == false) {
	$document->addScript('../components/com_acesearch/assets/js/autocompleter.js');
}

$text = $advanced_link = $focus = '';
$f = $params->get('text', 'search...');

$query = JRequest::getString('query');
if (!empty($query)) {
	$text = $query; 
	$focus = "";
}
elseif(!empty($f)) {
	$text = $f;
	$focus = 'onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';"';
}

$main_div_style = $fieldset_start = $filedset_end = $filedset_end_div = $filedset_sub_div_start = $filedset_sub_div_end = $button = "";
if ($module->position == 'cpanel') {
	$main_div_style = 'height:420px; padding-top:5px; padding-left:5px;';
	$fieldset_start = '<div id="acesearch_bg_module_admin"><fieldset class="acesearch_fieldset_admin"><legend class="acesearch_legend">'.JText::_('COM_ACESEARCH_SEARCH').'</legend>';
    $filedset_sub_div_start = '<div style="padding-top:3px;">';
	
	$input_width = (JRequest::getCmd('option') == 'com_acesearch') ? 'width:115px !important;' : '';
	$input_box = '<input type="text" name="query" value="'.$text.'" id="q" class="acesearch_input_module_simple" '.$focus.' style="margin-right:5px; '.$input_width.'" />';

    if ($params->get('show_button', '0') == '1') {
        $button ='<button type="submit" class="'.$params->get('button_class', 'acesearch_button').'" style="height:27px;margin-left:5px;" onclick="search();">'.JText::_('COM_ACESEARCH_SEARCH'). '</button>';
    }

    $filedset_sub_div_end = '</div>';
	$filedset_end = '</fieldset>';
	$filedset_end_div = '</div>';
}
elseif ($module->position == 'menu') {
    $main_div_style = 'float:right;margin:5px;';
    
	$input_box = '<input type="text" name="query" value="'.$text.'" id="q" class="inputbox" style="width:160px;" '.$focus.' />&nbsp;&nbsp;';

    if ($params->get('show_button', '0') == '1') {
        $button ='<input type="submit" value="'.JText::_('COM_ACESEARCH_SEARCH').'"/>';
    }
}

// Advanced Search link
if ($params->get('show_advanced_search', '0') != '0') {
	$link = 'index.php?option=com_acesearch&controller=search&task=advancedsearch';
	$advanced_link = '&nbsp;&nbsp;<a style="font-size:12px;" href='.JRoute::_($link).' title="'.$params->get('advanced_label', 'Advanced Search').'">'.$params->get('advanced_label', 'Advanced Search').'</a>';
}

?>
<script type="text/javascript">	
	<?php if ($params->get('admin_enable_complete','1') == '1' && $disable_autocompliter == false) { ?>
	window.addEvent('load', function() {
		var url = '<?php echo JRoute::_("index.php?option=com_acesearch&controller=ajax&task=complete", false); ?>';
		var completer = new Autocompleter.Ajax.Json($('q'), url, {'postVar': 'q'});
	});
	<?php }
	
	if ($module->position == 'cpanel' && $params->get('show_advanced_options', '1') == '1') { ?>
		function ChangeType(a){
			url = '<?php echo JRoute::_("index.php?option=com_acesearch&controller=ajax&format=raw&task=changeExtension", false); ?>&ext='+a;
			new Request({method: "get", url: url, onComplete : function(result) {$('module_custom_fields').innerHTML = result;}}).send();
		}
	<?php } ?>
</script>

<form name="acesearchModule" id="acesearchModule" action="index.php?option=com_acesearch&controller=search&task=view" method="post">
	<div class="search<?php echo $params->get('moduleclass_sfx', ''); ?>" style="<?php echo $main_div_style;?>">
		<?php
		echo $fieldset_start;
		echo $filedset_sub_div_start;
		echo $input_box;

		if ($module->position == 'cpanel' && $params->get('show_order', '0') == '1') {
			echo AcesearchHTML::_renderFieldOrder('', 'style="margin-right:5px;background: #F9F9F9;border: 1px solid gainsboro;padding: 5px;');
		}

		if ($module->position == 'cpanel' && $params->get('show_ext_flt', '0') == '1') {
			$lists = AcesearchHTML::getExtensionList();
			echo $lists['extension'];
		}

		echo $button;
		echo $advanced_link;
		echo $filedset_sub_div_end;
		echo $filedset_end;

		if ($module->position == 'cpanel' && $params->get('show_advanced_options', '1') == '1') {
			?>
			<div id="module_custom_fields"></div>
			<?php
		}
		
		echo $filedset_end_div;
		?>
	</div>
</form>