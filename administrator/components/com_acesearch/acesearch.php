<?php
/**
* @version		1.7.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined( '_JEXEC' ) or die( 'Restricted access' );

// Access check
if (!JFactory::getUser()->authorise('core.manage', 'com_acesearch')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JHTML::_('behavior.framework');

// Load AceSearch library
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_acesearch'.DS.'library'.DS.'loader.php');

$lang =& JFactory::getLanguage();
$lang->load('com_acesearch', JPATH_ADMINISTRATOR, 'en-GB', true);
$lang->load('com_acesearch', JPATH_ADMINISTRATOR, $lang->getDefault(), true);
$lang->load('com_acesearch', JPATH_ADMINISTRATOR, null, true);
$lang->load('com_acesearch', JPATH_SITE, 'en-GB', true);
$lang->load('com_acesearch', JPATH_SITE, $lang->getDefault(), true);
$lang->load('com_acesearch', JPATH_SITE, null, true);

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

if ($controller = JRequest::getCmd('controller')){
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	
	if (file_exists($path)) {
	    require_once($path);
	} else {
	    $controller = '';
	}
}

$classname = 'AcesearchController'.$controller;
$controller = new $classname();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
