<?php
/**
* @version		1.7.0
* @package		AceSearch Library
* @subpackage	MultiSelectSQL
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.form.formfield');
jimport('joomla.html.parameter.element');

// Load AceSearch library
if (!class_exists('AceDatabase')) {
	require_once(JPATH_ADMINISTRATOR.'/components/com_acesearch/library/database.php');
}
if (!class_exists('AcesearchFactory')) {
	require_once(JPATH_ADMINISTRATOR.'/components/com_acesearch/library/factory.php');
}
if (!class_exists('AcesearchUtility')) {
	require_once(JPATH_ADMINISTRATOR.'/components/com_acesearch/library/utility.php');
}

class JFormFieldMultiSelectSQL extends JFormField {

    protected $type = 'MultiSelectSQL';

	protected function getInput() {
        $this->AcesearchConfig = AcesearchFactory::getConfig();

		// Construct the various argument calls that are supported
		$attribs = 'class="inputbox" multiple="multiple" size="7"';
		
		$db	= JFactory::getDBO();
		$db->setQuery($this->element['db_query']);
		
		$key = ($this->element['db_id'] ? $this->element['db_id'] : 'id');
		$val = ($this->element['db_name'] ? $this->element['db_name'] : 'name');

		$rows = array();

		if ($this->element['default'] == 'alll') {
			$rows[0] = new stdClass();
			$rows[0]->id = 'alll';
			$rows[0]->name = JText::_('- All -');
		}

		$apps = array_merge($rows, $db->loadObjectList());

		return JHTML::_('select.genericlist', $apps, $this->name, $attribs, $key, $val, $this->value, $this->name);
	}
}