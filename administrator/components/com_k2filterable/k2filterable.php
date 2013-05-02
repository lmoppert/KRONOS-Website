<?php 

defined('_JEXEC') or die;

JLoader::register('ToolbarCommon', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/ToolbarCommon.php');
JLoader::register('ViewCommonExecution', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/ViewCommonExecution.php');
JLoader::register('BBUtilities', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/utilities.php');
JLoader::register('K2FilterableHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/filtergroups.php');


$controller = JController::getInstance('k2filterable');

try {
  $controller->execute(JRequest::getCmd('task'));
} catch (Exception $e){
  echo $e->getMessage();
}

$controller->redirect();