/**
 * @version		$Id$
 * @author		Joomseller
 * @package		Joomla!
 * @subpackage	MegaMenu_Framework
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL version 3, SEE LICENSE.php
 */


function updateFormMenu(obj, changeHeight){
	if(!obj) return;
	switch(obj.value.trim()){
		case '0':
			$('jformparamsmega_subcontent_mod_modules').getParent().setStyle('display', 'none');
			$('jformparamsmega_subcontent_pos_positions').getParent().setStyle('display', 'none');
			break;
		case 'mod':
			$('jformparamsmega_subcontent_mod_modules').getParent().setStyle('display', 'block');
			$('jformparamsmega_subcontent_pos_positions').getParent().setStyle('display', 'none');
			break;
		case 'pos':
			$('jformparamsmega_subcontent_mod_modules').getParent().setStyle('display', 'none');
			$('jformparamsmega_subcontent_pos_positions').getParent().setStyle('display', 'block');
			break;
	}
	if(changeHeight){
		$('mega-params-options').getNext().setStyle('height', $('mega-params-options').getNext().getElement('fieldset.panelform').offsetHeight)
		window.fireEvent('resize');
	}
}