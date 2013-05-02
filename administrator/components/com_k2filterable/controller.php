<?php

defined('_JEXEC') or die('Access Restricted');

class K2filterableController extends JController {
  
  public function display($cachable = false, $urlparams = false) {
    $view = JRequest::getCMD('view');
    if (empty($view)) {
      $jinput = JFactory::getApplication()->input;
      $jinput->set('view', 'filtergroups');
    }
    parent::display($cachable, $urlparams);
  }
  
}
