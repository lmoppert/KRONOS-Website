<?php

defined('_JEXEC') or die('Access Restricted');

jimport('joomla.application.component.model');

class K2FilterableModelFilters extends JModel {

  protected $_filterGroups = array();

  public function getItems() {
    $this->_loadFilterGroups();
    $this->_rebuildGroupDataWithFields();
    return $this->_filterGroups;
  }
  
  protected function _loadFilterGroups() {
    $query = $this->_db->getQuery(true);
    $query->select('*')->from('`#__k2filterable_group`')->order('ordering');
    $this->_db->setQuery($query);
    $this->_filterGroups = $this->_db->loadObjectList();
  }

  protected function _rebuildGroupDataWithFields(){
    $newGroups = array();
    foreach($this->_filterGroups AS $key => $filtergroup){
      $fieldsModel = JModel::getInstance('ExtraFields', 'K2FilterableModel', array('idList' => $filtergroup->extrafields));
      $fields = $fieldsModel->getAll();
      $this->_processFields($fields, $filtergroup);
      $newGroups[] = array('title' => $filtergroup->title, 'items' => $fields);
    }
    $this->_filterGroups = $newGroups;
  }

  protected function _processFields(&$fields, $filtergroup){
    foreach($fields as $field){
      $field->groupTitle = $filtergroup->title;
      $field->descends = $filtergroup->descends;
      $field->fields_type = $filtergroup->fields_type;
      $field->value = json_decode($field->value);
    }
  }
 
}