<?php
/**
* @version		1.7.0
* @package		AceSearch Library
* @subpackage	Handler List
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.form.formfield');
jimport('joomla.html.parameter.element');

// Load AceSearch library
if (!class_exists('AcesearchUtility')) {
	require_once(JPATH_ADMINISTRATOR.'/components/com_acesearch/library/utility.php');
}

class JFormFieldHandlerList extends JFormField{

    protected $type = 'HandlerList';

	protected function getInput() {
		// Construct the various argument calls that are supported
		$attribs = 'class="inputbox"';

        $extension = AcesearchUtility::getExtensionFromRequest();

		$options = AcesearchUtility::getHandlerList($extension);

		return JHTML::_('select.genericlist', $options, $this->name, $attribs, 'value', 'text', $this->value, $this->name);
	}
}