<?php
/**
* @version		1.5.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU GPL
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

// tmpl var
$tmpl = JRequest::getVar('tmpl');
?>

<script language="javascript">
	function submitbutton(pressbutton){
		// Check if is modal ivew
		<?php if ($tmpl == 'component') { ?>
		document.adminForm.modal.value = '1';
		<?php } ?>
		
		submitform(pressbutton);
	}
</script>

<form action="index.php?option=com_acesearch&amp;controller=extensions&amp;task=edit&amp;cid[]=<?php echo $this->row->id; ?>&amp;tmpl=component" method="post" name="adminForm">
	<fieldset class="panelform">
		<table class="toolbar1">
			<tr>
				<td class="desc" width="550px">
					<?php  echo '<h3>'.$this->row->description.'</h3>';  ?>
				</td>
				<td>
					<a href="#" onclick="javascript: submitbutton('editSave'); window.top.setTimeout('SqueezeBox.close();', 1000);" class="toolbar1"><span class="icon-32-save1" title="<?php echo JText::_('Save'); ?>"></span><?php echo JText::_('Save'); ?></a>
				</td>
				<td>
					<a href="#" onclick="javascript: submitbutton('editApply');" class="toolbar1"><span class="icon-32-apply1" title="<?php echo JText::_('Apply'); ?>"></span><?php echo JText::_('Apply'); ?></a>
				</td>
				<td>
					<a href="#" onclick="javascript: submitbutton('editCancel'); window.top.setTimeout('SqueezeBox.close();', 1000);" class="toolbar1"><span class="icon-32-cancel1" title="<?php echo JText::_('Cancel'); ?>"></span><?php echo JText::_('Cancel'); ?></a>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<?php if ($fields = $this->ext_params->getFieldset('download_id')) { ?>
		<fieldset class="panelform">
			<legend><?php echo JText::_('COM_ACESEARCH_CONFIG_MAIN_UPGRADE_ID'); ?></legend>
			<ul class="adminformlist">
				<?php foreach($fields as $field) { ?>
					<li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
			</ul>
		</fieldset>
	<?php }	?>
	
	<fieldset class="panelform">
		<legend><?php echo JText::_('Parameters'); ?></legend>
			<?php echo $this->tabs->startPane('tabs'); ?>
			
			<?php if ($fields = $this->ext_params->getFieldset('extension')) { ?>
			<?php echo $this->tabs->startPanel(JText::_('COM_ACESEARCH_PARAMS_EXTENSION'), 'extension'); ?>
			<ul class="adminformlist">
				<?php foreach($fields as $field) { ?>
					<li><?php echo $field->label; echo $field->input; ?></li>
				<?php } ?>
			</ul>
			<?php echo $this->tabs->endPanel();	?>
			<?php }	?>
			
			<?php if ($fields = $this->ext_params->getFieldset('properties')) { ?>
			<?php echo $this->tabs->startPanel(JText::_('COM_ACESEARCH_PARAMS_PROPERTIES'), 'properties'); ?>
			<ul class="adminformlist">
				<?php foreach($fields as $field) { ?>
					<li><?php echo $field->label; echo $field->input; ?></li>
				<?php } ?>
			</ul>
			<?php echo $this->tabs->endPanel();	?>
			<?php }	?>
			
			<?php echo $this->tabs->startPanel(JText::_('COM_ACESEARCH_PARAMS_COMMON'), 'common'); ?>
			<ul class="adminformlist">
				<?php foreach($this->common_params->getFieldset('default_params') as $field) { ?>
					<li><?php echo $field->label; echo $field->input; ?></li>
				<?php } ?>
			</ul>
			<?php
			echo $this->tabs->endPanel();
			
			echo $this->tabs->endPane();
			?>
	</fieldset>
	
	<input type="hidden" name="option" value="com_acesearch" />
	<input type="hidden" name="controller" value="extensions" />
	<input type="hidden" name="task" value="view" />
	<input type="hidden" name="modal" value="0" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>