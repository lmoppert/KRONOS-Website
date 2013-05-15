<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
if ($item->menu_image) {
		$item->params->get('menu_text', 1 ) ?
		$linktype = '<span class="image-title">'.$item->title.'</span>' :
		$linktype = '';
		//$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
		$menuimage = $item->menu_image;
}
else { $linktype = $item->title;
}

?><span class="separator"><?php echo $title; ?><?php echo $linktype; ?></span><?php if ($item->menu_image) { ?><img src="<?php echo $menuimage; ?>" alt="<?php echo $linktype; ?>" class="menu-image" /><?php } ?>
