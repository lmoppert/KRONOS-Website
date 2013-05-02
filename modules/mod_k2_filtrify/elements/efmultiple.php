<?php
/**
 * @version		$Id: efmultiple.php 3 2012-10-8 10:20:04Z grigor.mihov $
 * @package		K2
 * @author		Styleware http://www.styleware.eu
 * @copyright	Copyright (c) 2012 Styleware,  All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
 if (K2_JVERSION != '15')
        {
	jimport('joomla.form.formfield');
	class JFormFieldEfmultiple extends JFormField {
		var	$type = 'Efmultiple';
		function getInput(){
			return JElementEfmultiple::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
		}
	}
}
jimport('joomla.html.parameter.element');
class JElementEfmultiple extends JElement
{
	var	$_name = 'Efmultiple';
	function fetchElement($name, $value, &$node, $control_name){
		$params = &JComponentHelper::getParams('com_k2');
		
		$document = &JFactory::getDocument();
		
		$db = &JFactory::getDBO();
		$query = 'SELECT m.id, m.name FROM #__k2_extra_fields m';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		
		//check is there extrafields
		if(empty($mitems)){
			$output = "WARNING: You need to create one or more K2 Extra fields!";
			return $output;
		}
		
		foreach ( $mitems as $item ) {
		
			$mitems_option[] = JHTML::_('select.option',  $item->name, '   '.$item->name );
		}
		
		$mitems = array();
		$doc = & JFactory::getDocument();
		 if (K2_JVERSION != '15')
        {
			$js = "
			var \$K2 = jQuery.noConflict();
			\$K2(document).ready(function(){
				
				\$K2('#jform_params_catfilter0').click(function(){
					\$K2('#jformparamscategory_id').attr('disabled', 'disabled');
					\$K2('#jformparamscategory_id option').each(function() {
						\$K2(this).attr('selected', 'selected');
					});
				})
				
				\$K2('#jform_params_catfilter1').click(function(){
					\$K2('#jformparamscategory_id').removeAttr('disabled');
					\$K2('#jformparamscategory_id option').each(function() {
						\$K2(this).removeAttr('selected');
					});
	
				})
				
				if (\$K2('#jform_params_catfilter0').attr('checked')) {
					\$K2('#jformparamscategory_id').attr('disabled', 'disabled');
					\$K2('#jformparamscategory_id option').each(function() {
						\$K2(this).attr('selected', 'selected');
					});
				}
				
				if (\$K2('#jform_params_catfilter1').attr('checked')) {
					\$K2('#jformparamscategory_id').removeAttr('disabled');
				}
				
			});
			";			
				
		}
		else {
			$js = "
			var \$K2 = jQuery.noConflict();
			\$K2(document).ready(function(){
				
				\$K2('#paramscatfilter0').click(function(){
					\$K2('#paramscategory_id').attr('disabled', 'disabled');
					\$K2('#paramscategory_id option').each(function() {
						\$K2(this).attr('selected', 'selected');
					});
				})
				
				\$K2('#paramscatfilter1').click(function(){
					\$K2('#paramscategory_id').removeAttr('disabled');
					\$K2('#paramscategory_id option').each(function() {
						\$K2(this).removeAttr('selected');
					});
	
				})
				
				if (\$K2('#paramscatfilter0').attr('checked')) {
					\$K2('#paramscategory_id').attr('disabled', 'disabled');
					\$K2('#paramscategory_id option').each(function() {
						\$K2(this).attr('selected', 'selected');
					});
				}
				
				if (\$K2('#paramscatfilter1').attr('checked')) {
					\$K2('#paramscategory_id').removeAttr('disabled');
				}
				
			});
			";			
				
				
		}
		
		 if (K2_JVERSION != '15')
        {
			$fieldName = $name.'[]';
		}
		else {
			$fieldName = $control_name.'['.$name.'][]';
		}
		$doc->addScriptDeclaration($js);
		$output= JHTML::_('select.genericlist',  $mitems_option, $fieldName, 'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $value );
		return $output;
	}
}
