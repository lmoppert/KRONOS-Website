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

<fieldset class="acesearch_fieldset">
	<legend class="acesearch_legend"><?php echo JText::_('COM_ACESEARCH_SEARCH_RESULTS'); ?></legend>
	<?php
	if ($this->AcesearchConfig->enable_suggestion == '1' && !empty($this->results["suggest"])) {
		echo $this->results["suggest"];
	}
	
	if (!empty($this->results[0])) {
		?>
		<span class="about"><?php echo JText::_('COM_ACESEARCH_SEARCH_TOTAL_RESULTS').'&nbsp;'.$this->total.'&nbsp;'.JText::_('COM_ACESEARCH_SEARCH_RESULTS_FOUND'); ?> </span>
		
		<?php
		$ext = JRequest::getCmd('ext');
		
		if ($this->AcesearchConfig->show_search_refine == '1' && empty($ext) && !empty($this->refines) && empty($filter)) {
		?>
		<div class="acesearch_clear"></div>
		<span class="about">
		<?php
			echo JText::_('COM_ACESEARCH_SEARCH_REFINE').'&nbsp';
			
			foreach($this->refines as $key => $value) {
				if (empty($value)) {
					continue;
				}
				
				$link  = 'index.php?option=com_acesearch&view=search&query='.$this->query.'&ext='.$key.$this->suffix.$this->Itemid;
				
				$name = AcesearchExtension::getExtensionName($key);
				
				echo '&nbsp;'.$name.'&nbsp;(<a href="'.JRoute::_($link).'" title="'.$name.'" >'.$value.'</a>) ';				
			}
		?>
		</span>
		<?php } ?>
		<div class="acesearch_clear" style="margin-bottom:10px;"></div>
		<div id="acesearch_pagination">
            <?php if ($this->AcesearchConfig->show_order == '1') { ?>
                <div class="limitbox">
                    &nbsp;
                    <?php echo JText::_('COM_ACESEARCH_FIELDS_ORDER'); ?>
                    <?php echo $this->lists['order']; ?>
                    &nbsp;&nbsp;
                </div>
            <?php } ?>
			<?php if ($this->AcesearchConfig->show_display == '1') { ?>
				<div class="limitbox">
					&nbsp;<?php echo JText::_('Display'); ?>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php } ?>
			<div class="pagination" style="margin: 0px 0; float: right;">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		</div>
	
		<div id="dotttt"></div>
		<div class="acesearch_clear"></div>

        <?php $this->renderModules(); ?>

		<?php
		$n = count($this->results);
        $more_results = array();
		
		for ($i = 0; $i < $n; $i++){
			$result = isset($this->results[$i]) ? $this->results[$i] : "";
			
			if (!empty($result)){
				AcesearchSearch::finalizeResult($result);				
				?>
				<div id="dotttt"></div>				
				<div>
					<font size="3px" color="#6a6767"><?php echo $this->pagination->getRowOffset($i); ?>.</font>
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
				<?php }
                    if ($this->AcesearchConfig->google_more_results == '1' && empty($ext) && empty($filter)) {
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
			}
		}
		?>

        <?php $this->renderModules('acesearch_bottom'); ?>
        
		<div class="acesearch_clear"></div>
		<div id="acesearch_pagination">
			<div class="pagination" style="margin: 0px 0; float: right;">
				<?php echo $this->pagination->getPagesLinks(); ?>&nbsp;
			</div>
		</div>
		<?php
	}
	else {
		?>
		<h2><?php echo JText::_('COM_ACESEARCH_SEARCH_NO_RESULTS'); ?></h2>
		<span><?php echo JText::_('COM_ACESEARCH_SEARCH_NO_RESULTS_QUERY'); ?><?php echo AcesearchSearch::getSearchQuery(); ?></span>
		<?php
	}	
	?>
</fieldset>
<div class="acesearch_clear"></div>