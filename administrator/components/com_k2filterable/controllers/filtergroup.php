<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class K2FilterableControllerFilterGroup extends JControllerForm {

  public function cancel(){
    $this->setRedirect('index.php?option=com_k2filterable');
  }

}