<?php
/**
 * @version		$Id: mod_k2_filtrify.php 3 2012-10-08Z office@styleware.eu $
 * @package		mod_k2_filtrify

 * @author		Styleware http://www.styleware.eu
 * @copyright	Copyright (c) Styleware. All rights reserved..
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die ;

if (K2_JVERSION != '15')
{
	$language = JFactory::getLanguage();
	$language->load('mod_k2.j16', JPATH_ADMINISTRATOR, null, true);
}

require_once(dirname(__FILE__).DS.'helper.php');

// Params
$moduleclass_sfx = $params->get('moduleclass_sfx','');
$getTemplate = $params->get('getTemplate','Default');
$itemAuthorAvatarWidthSelect = $params->get('itemAuthorAvatarWidthSelect','custom');
$itemAuthorAvatarWidth = $params->get('itemAuthorAvatarWidth',50);
$itemCustomLinkTitle = $params->get('itemCustomLinkTitle','');
if($params->get('itemCustomLinkMenuItem')) {


	$menu = JMenu::getInstance('site');
	$menuLink = $menu->getItem($params->get('itemCustomLinkMenuItem'));
	if(!$itemCustomLinkTitle){

		$itemCustomLinkTitle = (K2_JVERSION != '15') ? $menuLink->title : $menuLink->name;
	}
	$params->set('itemCustomLinkURL', JRoute::_($menuLink->link.'&Itemid='.$menuLink->id));
}

// Get component params
$componentParams = JComponentHelper::getParams('com_k2');

// User avatar
if($itemAuthorAvatarWidthSelect=='inherit'){

	$avatarWidth = $componentParams->get('userImageWidth');
} else {



	$avatarWidth = $itemAuthorAvatarWidth;
}

$items = modK2FiltrifyHelper::getItems($params);

if(count($items)){

	require(JModuleHelper::getLayoutPath('mod_k2_filtrify', $getTemplate.DS.'default'));
}
