<?php
/**
* @version		1.7.0
* @package		AceSearch Library
* @subpackage	Filters
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.html.parameter.element');

class JFormFieldAcesearchFilters extends JFormField{

    protected $type = 'AcesearchFilters';

	protected function getInput() {
		// Construct the various argument calls that are supported
		$attribs = 'class="inputbox"';

		$db =& JFactory::getDBO();
		$db->setQuery("SELECT id, title FROM #__acesearch_filters_groups");
		$rows = $db->loadObjectList();

        $options = array();
        $options[] = array('option' => '', 'name' => JText::_('- - - - - -'));

		foreach ($rows as $row){
			$options[] = array('option' => $row->id, 'name' => $row->title);
		}

		return JHTML::_('select.genericlist', $options, $this->name, $attribs, 'option', 'name', $this->value, $this->name);
	}
}