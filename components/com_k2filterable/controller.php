<?php 

defined('_JEXEC') or die('Access Restricted');

jimport('joomla.application.component.controller');

class K2FilterableController extends JController {

  public function display() {
    $jinput = JFactory::getApplication()->input;
    $view = $jinput->get('view', false, 'CMD');
    if(!$view) $jinput->set('view', 'results');
    parent::display();
  }

}