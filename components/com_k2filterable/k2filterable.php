<?php 

defined('_JEXEC') or die('Access Restricted');

require_once('controller.php');

JLoader::register('ViewCommonExecution', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/ViewCommonExecution.php');
JLoader::register('K2FilterableModelResults', JPATH_COMPONENT . '/models/results.php');
JLoader::register('K2FilterableModelFilters', JPATH_COMPONENT . '/models/filters.php');
JLoader::register('K2FilterableModelExtraFields', JPATH_COMPONENT . '/models/extrafields.php');
JLoader::register('FilterFormBuilder', JPATH_COMPONENT . '/helpers/FilterFormBuilder.php');
JLoader::register('K2FilterableMenuLinkLocator', JPATH_COMPONENT . '/helpers/K2FilterableMenuLinkLocator.php');
JLoader::register('K2filterableViewCommon', JPATH_COMPONENT . '/helpers/views/K2FilterableViewCommon.php');

$jinput = JFactory::getApplication()->input;

$controller = JController::getInstance('k2filterable');

$doc = JFactory::getDocument();
$js = "var com_k2filterable = {};\n";
$js.= "com_k2filterable.jbase = '".JURI::base()."';\n";

$doc->addScriptDeclaration($js);

try {
  $controller->execute($jinput->get('task', 'results.display', 'CMD'));
} catch (Exception $e){

}
$controller->redirect();