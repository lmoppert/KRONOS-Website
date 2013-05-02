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

$filter_value = "";
$ajax_filter = JRequest::getInt('filter');
if (!empty($ajax_filter)) {
	$filter_value = '&filter='.$ajax_filter;
}

$_ext = JRequest::getCmd('ext');
?>

<script type="text/javascript">	
	window.onload = function Acesearch(){
        <?php if (!empty($_ext)) { ?>
		url = '<?php echo JRoute::_("index.php?option=com_acesearch&task=changeExtensionMod&format=raw&ext=".$_ext.$filter_value, false);?>';
		new Request({method: "get", url: url, onComplete : function(result) {$('custom_fields').innerHTML = result;}}).send();
        <?php } ?>
	} 
	
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
			document.location.href=link+'&query='+query;		
		}
		else {
			document.location.href=link;		
		}
	}
	
	window.addEvent('load', function() {
	<?php
	if ($this->AcesearchConfig->enable_complete == '1') { ?>
		$('q').addEvent('focus', function(){
			var url = '<?php echo JRoute::_('index.php?option=com_acesearch&task=complete&format=raw', false); ?>';
			var completer = new Autocompleter.Ajax.Json($('q'), url, {'postVar': 'q'});
		});
	<?php }  ?>
		var mySlide = new Fx.Slide('more-menu',{duration: 1000, transition: Fx.Transitions.Quad.easeInOut});
		mySlide.hide();		
		$('more-link').addEvent('click', function(e){
			e = new Event(e);
			mySlide.toggle();
			e.stop();
			$('more-link').setStyle('display','none');
			$('fewer-link').setStyle('display','block');
		});
		$('fewer-link').addEvent('click', function(e){
			e = new Event(e);
			mySlide.hide();
			e.stop();
			$('more-link').setStyle('display','block');
			$('fewer-link').setStyle('display','none');
		});
	});
</script>

<div id="acesearch_google_empty">
    <div class="acesearch_google_results">
        <?php if ($this->AcesearchConfig->show_ext_flt == '1') { ?>
            <div class="acesearch_google_results_left">
                <?php echo AcesearchHTML::getExtensions();?>
                <?php if ($this->AcesearchConfig->show_order == '1') { ?>
                    <div class="advancedsearch_div">
                        <span class="acesearch_span_label_module">
                            <?php echo JText::_('COM_ACESEARCH_FIELDS_ORDER'); ?>
                        </span>
                        <span class="acesearch_span_field_module">
                            <?php echo $this->lists['order']; ?>
                        </span>
                    </div>
                <?php } ?>
                <div id="custom_fields"></div>
            </div>
        <?php } ?>
        <div class="acesearch_google_results_center">
            <div class="acesearch_google_results_top">
                <div class="acesearch_google_results_up">
                    <table style="position:relative; z-index:2; border-bottom:1px solid transparent" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr style="border:0px !important;">
                                <td style="width:100%; border:0px !important;">
                                    <div class="acesearch_google_results_inputbox">
                                        <input class="acesearch_result_google" type="text" name="query" id="q" value="<?php echo $this->query; ?>" maxlength="<?php echo $this->AcesearchConfig->max_search_char; ?>" />
                                    </div>
                                    <div class="acesearch_google_results_jsb"> </div>
                                </td>
                                <td style="border:0px !important;">
                                    <div class="acesearch_google_results_lsbb" id="acesearch_google_results_sblsbb">
                                        <button class="acesearch_google_results_lsb" type="submit" name="btnG">
                                            <span class="acesearch_google_results_sbico"></span>
                                        </button>
                                    </div>
                                </td>
                                <td style="border:0px !important;">
                                    <div style="position:relative;height:29px;z-index:2">
                                        <div class="acesearch_google_results_lsd">
                                            <div id="ss-bar" style="white-space:nowrap;z-index:98"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="acesearch_google_results_bottom">
                    <span class="acesearch_google_results_about"><?php echo JText::_('COM_ACESEARCH_SEARCH_TOTAL_RESULTS').'&nbsp;'.$this->total.'&nbsp;'.JText::_('COM_ACESEARCH_SEARCH_RESULTS_FOUND'); ?> </span>
                    <?php if ($this->AcesearchConfig->show_adv_search == '1') { ?>
                        <a class="acesearch_google_results_about" style="font-size:10px; float:right;  color: #3366CC;" href="<?php echo JRoute::_('index.php?option=com_acesearch&view=advancedsearch'.$this->suffix .$this->Itemid); ?>" title="<?php echo JText::_('COM_ACESEARCH_SEARCH_ADVANCED_SEARCH'); ?>" >
                            <?php echo JText::_('COM_ACESEARCH_SEARCH_ADVANCED_SEARCH'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <?php

            if ($this->AcesearchConfig->enable_suggestion == '1' && !empty($this->results["suggest"])) {
                echo $this->results["suggest"];
            }

            $this->renderModules();

            if (!empty($this->results[0])) {
                $ext = JRequest::getCmd('ext','');
                $n = count($this->results);
                $more_results = array();
                
                for ($i = 0; $i < $n; $i++){
                    $result = isset($this->results[$i]) ? $this->results[$i] : "";

                    if (!empty($result)){
                        AcesearchSearch::finalizeResult($result);
                        ?>
                        <div>
                            <font size="3px"><a href="<?php echo $result->link; ?>"><?php echo $result->name; ?></a></font>
                        </div>
                        <?php if(!empty($result->description)) { ?>
                        <div>
                            <?php echo $result->description; ?>
                        </div>
                        <?php }
                            if(!empty($result->properties)) { ?>
                        <div>
                            <font color="#6a6767">
                                <?php echo $result->properties;	?>
                            </font>
                        </div>
                        <?php }
                            if(!empty($result->route)) { ?>
                        <div>
                            <a class="acesearch_results_route_link" href="<?php echo $result->route; ?>">
                                <?php echo $result->route; ?>
                            </a>
                        </div>
                        <?php
                            if ($this->AcesearchConfig->google_more_results == '1' && empty($ext) && empty($ajax_filter)) {
                                if (!isset($more_results[$result->acesearch_ext])) {
                                    $more_results[$result->acesearch_ext] = 1;
                                }
                                else {
                                    $more_results[$result->acesearch_ext]++;
                                }
                                
                                $prm_results_length = AcesearchFactory::getCache()->getExtensionParams($result->acesearch_ext)->get('google_more_results_length', '');
                                if (!empty($prm_results_length)){
                                    $results_length = intval($prm_results_length);
                                }
                                else {
                                    $results_length = $this->AcesearchConfig->google_more_results_length;
                                }

                                if ($more_results[$result->acesearch_ext] == $results_length) {
                                    $name = AcesearchExtension::getExtensionName($result->acesearch_ext);
                                    ?>
                                    <br/>
                                    <div>
                                        <div class="google_pluss"></div>
                                        <a href="<?php echo JRoute::_(JFactory::getURI()->toString()).'&ext='.$result->acesearch_ext; ?>" class="google_pluss_link">
                                            <?php echo JText::_('COM_ACESEARCH_SEARCH_SHOW_MORE_RESULTS').' "'.$name.'" '. JText::_('COM_ACESEARCH_SEARCH_SHOW_MORE_RESULTS_SEC'); ?>
                                        </a>
                                    </div>
                                    <?php
                                    $more_results[$result->acesearch_ext] = 1;
                                }
                            }
                            if ($i < $n - 1){ ?>
                        <div id="dotttt"></div>
                        <?php }
                            }
                    }
                }
                ?>

                <?php $this->renderModules('acesearch_bottom'); ?>
                    
                <div class="acesearch_clear"></div>
                <div id="acesearch_pagination">
                    <div class="pagination"">
                        <?php echo $this->pagination->getPagesLinks(); ?>&nbsp;
                    </div>
                </div>
            <?php

            }
            else{
                ?>
                <h2><?php echo JText::_('COM_ACESEARCH_SEARCH_NO_RESULTS'); ?></h2>
                    <span><?php echo JText::_('COM_ACESEARCH_SEARCH_NO_RESULTS_QUERY'); ?><?php echo AcesearchSearch::getSearchQuery(); ?></span>
                <?php
            }
            ?>
    </div>
    <div class="acesearch_clear"></div>
    </div>
</div>