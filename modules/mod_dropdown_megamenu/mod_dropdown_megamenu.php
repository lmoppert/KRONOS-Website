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

require_once (dirname(__FILE__).DS.'helper.php');

JHTML::_('behavior.mootools');

$menutype 	= $params->get('menutype', 'mainmenu');
$mainframe 	= &JFactory::getApplication('site');

//Main navigation
$params->def('menutype',			$menutype);
$params->def('menu_images',			1);
$params->def('menu_images_align',	'left');
$params->def('menupath',			'modules/mod_dropdown_megamenu');
$params->def('menu_title',			0);

$menuStyle	= $params->get('menustyle',		'left');

$dropdownmenu	= new Mod_DropDown_MegaMenu($params);

$document = &JFactory::getDocument();
//$document->addScript('modules/mod_dropdown_megamenu/assets/js/dropdown_menu.js');
//$document->addScript('modules/mod_dropdown_megamenu/assets/js/showhide.js');
$document->addStyleSheet("modules/mod_dropdown_megamenu/assets/css/dropdown_menu_$menuStyle.css");

$layout		= $params->get('layout', 'default');
/*$document->addStyleSheet('modules/mod_dropdown_megamenu/assets/css/'.$layout.'/style.css');*/

require(JModuleHelper::getLayoutPath('mod_dropdown_megamenu'));
