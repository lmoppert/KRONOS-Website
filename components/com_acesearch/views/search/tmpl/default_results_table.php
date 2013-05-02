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

$check = isset($this->results[0]) ? $this->results[0] : '';
$filter = JRequest::getInt('filter');
?>

<fieldset class="acesearch_fieldset">
	<legend class="acesearch_legend"><?php echo JText::_('COM_ACESEARCH_SEARCH_RESULTS'); ?></legend>
	<?php
	if ($this->AcesearchConfig->enable_suggestion == '1' && !empty($this->results["suggest"])) {
		echo $this->results["suggest"];
	}

	if (!empty($check)) {
		?>
		
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
				<?php echo $this->pagination->getPagesLinks(); ?>&nbsp;
			</div>
		</div>
		<div class="acesearch_clear" style="margin-bottom:10px;"></div>

        <?php $this->renderModules(); ?>
		
		<div id="editcell">
			<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane">
				<tr>
					<td class="sectiontableheader" width="1%">
						<?php echo JText::_('Num'); ?>
					</td>
					<td class="sectiontableheader">
						<?php echo JText::_('COM_ACESEARCH_FIELDS_TITLE'); ?>
					</td>
					<td class="sectiontableheader" width="150px">
						<?php echo JText::_('COM_ACESEARCH_SEARCH_SECTION'); ?>
					</td>		
				</tr>
				<?php
				$k =0;
				$n = count($this->results);
                $ext = JRequest::getCmd('ext','');
                $more_results = array();
				
				for ($i = 0; $i < $n; $i++){
					$b = $k +1;
					$result = isset($this->results[$i]) ? $this->results[$i] : "";
					
					if (!empty($result)) {
						AcesearchSearch::finalizeResult($result);
						?>
						<tr class="sectiontableentry<?php echo $b;?>">
							<td>
								<font size="2px" color="#6a6767"><?php echo $this->pagination->getRowOffset($i); ?>.</font>
							</td>
							<td width="60%">
								<font size="2px"><a href="<?php echo $result->link; ?>"><?php echo $result->name; ?></a></font>
                            </td>
							<td width="20%">
								<font size="2px"><?php echo AcesearchExtension::getExtensionName($result->acesearch_ext); ?></font>
                            </td>
						</tr>
						<?php
						$k = 1 - $k;

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
                            
                            if ($more_results[$result->acesearch_ext] == $results_length){
                                $name = AcesearchExtension::getExtensionName($result->acesearch_ext);
                                ?>
                                <tr class="sectiontableentry<?php echo $b;?>">
                                    <td colspan="3">
                                        <div class="google_pluss"></div>
                                        <a href="<?php echo JRoute::_(JFactory::getURI()->toString()).'&ext='.$result->acesearch_ext; ?>" class="google_pluss_link">
                                            <?php echo JText::_('COM_ACESEARCH_SEARCH_SHOW_MORE_RESULTS').' "'.$name.'" '. JText::_('COM_ACESEARCH_SEARCH_SHOW_MORE_RESULTS_SEC'); ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $more_results[$result->acesearch_ext] = 1;
                            }
                        }
					}
				}
				?>
				<div class="acesearch_clear" style="margin-bottom:10px;"></div>
			</table>
		</div>

        <?php $this->renderModules('acesearch_bottom'); ?>

		<div id="acesearch_pagination">
			<div class="pagination" style="margin: 0px 0; float: right;">
				<?php echo $this->pagination->getPagesLinks(); ?>&nbsp;
			</div>
		</div>
		<?php
	}
	
	if (empty($check)){
		?>
		<h2><?php echo JText::_('COM_ACESEARCH_SEARCH_NO_RESULTS'); ?></h2>
		<span><?php echo JText::_('COM_ACESEARCH_SEARCH_NO_RESULTS_QUERY'); ?><?php echo $q; ?></span>
		<?php
	}
	?>
</fieldset>

<input type="hidden" name="filter" value="<?php echo JRequest::getCmd('filter', ''); ?>"/>

<div class="acesearch_clear" style="margin-bottom:10px;"></div>