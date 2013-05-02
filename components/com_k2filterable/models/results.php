<?php

defined('_JEXEC') or die('Access Restricted');

jimport('joomla.application.component.model');

class K2filterableModelResults extends JModel {

  protected $_search = array();
  protected $_results = array();
  protected $_totalResults = 0;
  protected $_limit = 100;
  protected $_offset = 0;
  protected $_categoryId = 0;
  
  public function __construct($config = array()) {
    $this->_populateState();
    parent::__construct($config);
  }

  protected function _populateState() {
    $jinput = JFactory::getApplication()->input;
    $this->_search = $jinput->get('search', null, 'ARRAY');
    $this->_limit = $jinput->get('limit', $this->_limit, 'INT');
    $this->_offset = $jinput->get('offset', $this->_offset, 'INT');
	
    $jinput->set('limit', $this->_limit);
    $jinput->set('offset', $this->_offset);
    $this->_loadK2CategoryId();
  }
  
  protected function _loadK2CategoryId(){
    $params = JFactory::getApplication('site')->getParams();
    $this->_categoryId = $params->get('categoryId', false);
  }

  public function getCount(){
    $this->_loadResults();
    return $this->_totalResults;
  }
  
  public function getAll() {
    $this->_loadResults();
    return array(
          "db" => $this->_results,
	  "limit" => $this->_limit,
	  "offset" => $this->_offset,
	  "num" => $this->_totalResults
    );
  }
  
  protected function _loadResults() {
    if(!empty($this->_results)) return;
    $query = $this->_getAllQuery('tbl.*');         
    $this->_db->setQuery($query, $this->_offset, $this->_limit);
    $this->_results = $this->_db->loadObjectList();
    $this->_totalResults = $this->_getListCount($query);
  }

  protected function _getAllQuery($fields) {
    $query = $this->_getAllFieldAndTablesQuery($fields);
    $this->_getAllLoadWhere($query);
    $query->order('tbl.ordering');
    return $query;
  }

  protected function _getAllFieldAndTablesQuery($fields) {
    $query = $this->_db->getQuery(true);
    $query->select($fields);
    $query->from('`#__k2_items` AS tbl');
    $query->join( 'LEFT', '#__k2_categories AS c ON c.id = tbl.catid' );
    return $query;
  }

  protected function _getAllLoadWhere(&$query) {
    $this->_getAllAddWhereCategory($query);
    $this->_getAllAddWhereSearch($query);
  }

  protected function _getAllAddWhereCategory(&$query) {
    if($this->_categoryId) {
	$query->where('(c.id = '.(int) $this->_categoryId.' OR c.parent = '.(int) $this->_categoryId.')');		
    }
  }

  protected function _getAllAddWhereSearch(&$query) {
    $search = array();    
    if(count($this->_search)) {
        $search = $this->_loadSearchWords($this->_search);
        $query->where($this->_prepareWhereCondition($search));
    }
  }

  protected function _loadSearchWords($search) {
      $results = array ();
      foreach ($search as $key => $value): 
          $query = $this->_db->getQuery(true);
          $query->select('value');
          $query->from('`#__k2_extra_fields`');
          $query->where('id = '.$key);  
          $this->_db->setQuery($query);
          $result = $this->_db->loadObject();
          if ($error = $this->_db->getErrorMsg()) {
		throw new Exception($error);
          }
          if (empty($result)) {
		return JError::raiseError(404, JText::_('K2FILTERABLE_ERROR_K2EXTRAFIELD_NOT_FOUND'));
          }
          $words = json_decode($result->value);
          foreach ($words as $word) {
              if (in_array((string)$word->value, $value)) $results[$key][] = $word->name; 
          }
      endforeach;
      return $results;
  }

  protected function _prepareWhereCondition ($search) {
      	$where = array();
	foreach ($search as $id =>$words) :
            $where2 = array ();
            $where2[] = '(tbl.extra_fields LIKE '.$this->_db->quote('%"id":"'.$id.'"%', true).')';
            foreach ($words as $word) {
               $where2[] = '(tbl.extra_fields_search LIKE '.$this->_db->quote('%'.$word.'%').')';
            }
            $where[] = '(' . implode( ' AND ', $where2 ) . ')';
        endforeach; 
        $return = '(' . implode( ' AND ', $where ) . ')'; 
        return $return;        
                
  }

  public function getPossibleRemainingSearches() {
    $encodedExtraFields = $this->_getItemExtraFieldData();
    return $this->_processExtraFieldJson($encodedExtraFields);
  }

  protected function _getItemExtraFieldData() {
    $query = $this->_getAllQuery('tbl.extra_fields');
    $this->_db->setQuery($query);
    return $this->_db->loadObjectList();
  }

  protected function _processExtraFieldJson($encodedExtraFields) {
    $possibleSearches = array();
    foreach($encodedExtraFields as $field){
      $itemDataSelected = json_decode($field->extra_fields);
      $this->_addSelectedValues($itemDataSelected, $possibleSearches);
    }
    return $possibleSearches;
  }

  protected function _addSelectedValues($itemDataSelected, &$possibleSearches) {
    foreach($itemDataSelected as $selected){
      if(!array_key_exists($selected->id, $possibleSearches)) $possibleSearches[$selected->id] = array();
      $possibleSearches[$selected->id] = $this->_addExtractedValues($selected->value, $possibleSearches[$selected->id]);
    }
  }

  protected function _addExtractedValues($selectedValues, $selectedArr) {
    foreach($selectedValues as $value){
      $selectedArr[] = $value;
    }
    return $selectedArr;
  }

  public function getFilters() {
    $filters = new K2FilterableModelFilters;
    return $filters->getItems();
  }
  
}