<?php
/**
* @version		1.7.0
* @package		AceSearch Library
* @subpackage	ComponentList
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.html.parameter.element');

class JFormFieldComponentList extends JFormField{

    protected $type = 'ComponentList';

	protected function getInput() {
		// Construct the various argument calls that are supported
		$attribs = 'class="inputbox"';
		
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT name, extension FROM #__acesearch_extensions WHERE params NOT LIKE '%handler=0%' AND (client = 0 OR client = 2)");
		$rows = $db->loadObjectList();

        $options = array();
        $options[] = array('option' => '', 'name' => JText::_('- All Components -'));

		foreach ($rows as $row){
			$options[] = array('option' => $row->extension, 'name' => $row->name);
		}

		return JHTML::_('select.genericlist', $options, $this->name, $attribs, 'option', 'name', $this->value, $this->name);
	}
}