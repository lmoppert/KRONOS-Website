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

// Imports
jimport('joomla.form.form');
AcesearchUtility::import('library.elements.fieldlist');
AcesearchUtility::import('library.elements.handlerlist');
AcesearchUtility::import('library.elements.multiselectsql');
JLoader::register('JHtmlSelect', JPATH_ACESEARCH_ADMIN.'/library/joomla/select.php');
JLoader::register('JElementRadio', JPATH_ACESEARCH_ADMIN.'/library/joomla/radio.php');
JLoader::register('JElementSpacer', JPATH_ACESEARCH_ADMIN.'/library/joomla/spacer.php');

class AcesearchViewExtensions extends AcesearchView {
	
	function edit($tpl = null){
        $row = $this->getModel()->getEditData('AcesearchExtensions');
		
		$ext_form = JForm::getInstance('extensionForm', JPATH_ACESEARCH_ADMIN.'/extensions/'.$row->extension.'.xml', array(), true, 'config');
		$ext_values = array('params' => json_decode($row->params));
		$ext_form->bind($ext_values);
		
		$common_form = JForm::getInstance('commonForm', JPATH_ACESEARCH_ADMIN.'/extensions/default_params.xml', array(), true, 'config');
		$common_values = array('params' => json_decode($row->params));
		$common_form->bind($common_values);
		
		// Get description from XML
		$xml_file = JPATH_ACESEARCH_ADMIN.DS.'extensions'.DS.$row->extension.'.xml';
		if (file_exists($xml_file)) {
			$row->description = AcesearchUtility::getXmlText($xml_file, 'description');
		}
		
		// Get behaviors
		JHTML::_('behavior.combobox');
		JHTML::_('behavior.tooltip');
		
		// Import pane
		jimport('joomla.html.pane');
		$tabs =& JPane::getInstance('Tabs');
		
		// Get search values              
		$this->assignRef('row',				$row);
		$this->assignRef('ext_params', 		$ext_form);
		$this->assignRef('common_params', 	$common_form);
		$this->assignRef('tabs', 			$tabs);
		
		parent::display($tpl);
	}
}