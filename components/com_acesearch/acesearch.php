<?php
/**
* @version		1.5.7
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

// Includes
require_once(JPATH_COMPONENT.'/controller.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_acesearch/library/loader.php');

$lang =& JFactory::getLanguage();
$lang->load('com_acesearch', JPATH_SITE, 'en-GB', true);
$lang->load('com_acesearch', JPATH_SITE, $lang->getDefault(), true);
$lang->load('com_acesearch', JPATH_SITE, null, true);

if (!AcesearchUtility::checkPlugin()) {
	return;
}

$controller = new AceSearchController();

// Perform the Request task
$controller->execute(JRequest::getWord('task'));

$format = JRequest::getWord('format');
if ($format != 'raw') {
	AcesearchUtility::getPlugin();
}

// Redirect if set by the controller
$controller->redirect();
