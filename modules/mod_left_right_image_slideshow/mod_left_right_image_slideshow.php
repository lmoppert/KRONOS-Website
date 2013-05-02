<?php
/**
 * Left right image slideshow
 *
 * @package Left right image slideshow
 * @subpackage Left right image slideshow
 * @version   2.0 Februray, 2012
 * @author    Gopi http://www.gopiplus.com
 * @copyright Copyright (C) 2010 - 2012 www.gopiplus.com, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$folder	= modLeftRightImageSlideshowHelper::getFolder($params);
$images	= modLeftRightImageSlideshowHelper::getImages($params, $folder);

if (!count($images)) 
{
	echo JText::_('NO IMAGES ' . $folder . '<br><br>');
	return;
}

require JModuleHelper::getLayoutPath('mod_left_right_image_slideshow', $params->get('layout', 'default'));
modLeftRightImageSlideshowHelper::loadScripts($params);