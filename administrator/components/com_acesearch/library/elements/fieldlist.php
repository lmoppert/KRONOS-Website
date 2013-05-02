<?php
/**
* @version		1.7.0
* @package		AceSearch Library
* @subpackage	Field List
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

class JFormFieldFieldList extends JFormField{

    protected $type = 'FieldList';

	protected function getInput() {
        $this->AcesearchConfig = AcesearchFactory::getConfig();

		// Construct the various argument calls that are supported
		$attribs = 'class="inputbox" multiple="multiple" size="7"';

		// Get rows
		$fields = array();

        $option = AcesearchUtility::getExtensionFromRequest();

		if (file_exists(JPATH_ACESEARCH_ADMIN.'/extensions/'.$option.'.php')) {
			$rows = AcesearchFactory::getExtension($option, 1)->getFieldList();

			if (!empty($rows)) {
				foreach ($rows as $row) {
					if (isset($row->id)) {
						$id = $row->id.'_'.$row->name;
					}
                    else {
						$id = $row->name;
					}
					
                    $fields[] = array('option' => $id, 'name' => $row->name);
				}
			}
		}

		return JHTML::_('select.genericlist', $fields, $this->name, $attribs, 'option', 'name', $this->value, $this->name);
	}
}