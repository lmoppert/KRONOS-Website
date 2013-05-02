<?php
/**
 * @version		$Id$
 * @author		Joomseller
 * @package		Joomla!
 * @subpackage	Mod_DropDown_MooMenu
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldLayout extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'layout';

	/**
	 * fetch Element
	 */
	function getInput(){
		$db = &JFactory::getDBO();

		$folders	= JFolder::folders(JPATH_ROOT.DS.'modules'.DS.'mod_dropdown_megamenu'.DS.'assets'.DS.'css');
		$options = array ();
		foreach ($folders as $folder) {
			$options[] = JHTML::_('select.option', $folder, $folder);
		}

		return JHTML::_('select.genericlist', $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}
}