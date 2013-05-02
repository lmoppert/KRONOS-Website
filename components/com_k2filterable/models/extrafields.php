<?php

defined('_JEXEC') or die('Access Restricted');

jimport('joomla.application.component.model');

class K2FilterableModelExtraFields extends JModel {

  protected $_idList = array();

  public function __construct($args = array()){
    parent::__construct($args);
    $this->_idList = (array_key_exists('idList', $args)) ? $args['idList'] : array();
  }

  public function getAll(){
    if(empty($this->_idList)) return array();
    $this->_escapeIdList();
    return $this->_getFieldsByIdList();
  }

  protected function _getFieldsByIdList(){
    $idLists = $this->_idList;
    $idLists = preg_replace("/'([0-9]+)'/i",'$1',$idLists);
    $idList = explode(',', $idLists);
    $results = array ();
    foreach ($idList as $id) :
        $query = $this->_db->getQuery(true);
        $query->select('`id`, `name`,`value`')->from('`#__k2_extra_fields`')->where("`id` = {$id}");
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();
        if ($error = $this->_db->getErrorMsg()) {
		throw new Exception($error);
	}
	if (empty($result)) {
		return JError::raiseError(404, JText::_('K2FILTERABLE_ERROR_K2EXTRAFIELD_NOT_FOUND'));
	}
        $results[] = $result;
   endforeach;
   return $results;

  }
  
  protected function _escapeIdList() {
    $processed = '';
    foreach(explode(',',$this->_idList) as $id){
      $processed .= $this->_db->quote($id) . ',';
    }
    $this->_idList = substr($processed, 0, -1);
  }

}