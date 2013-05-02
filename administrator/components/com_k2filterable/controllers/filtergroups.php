<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class K2FilterableControllerFilterGroups extends JControllerAdmin {
  
  function __construct() {
    $this->view_list = 'filtergroups';
    parent::__construct();
  }
  
  public function getModel($name = 'FilterGroup', $prefix = 'K2FilterableModel', $config = array('ignore_request' => true)) {
    $model = parent::getModel($name, $prefix, $config);
    return $model;
  }

}