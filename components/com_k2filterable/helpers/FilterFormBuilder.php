<?php
/*-----------------------------------------
  License: GPL v 3.0 or later
-----------------------------------------*/

defined('_JEXEC') or die('Access Restricted');

require_once(JPATH_BASE . '/components/com_k2filterable/helpers/K2FilterableFields.php');

class FilterFormBuilder
{
  protected $_filterData = null;
  protected $_search = array();
  protected $_limit = 100;
  protected $_offset = 0;
  protected $_filtersIntro = '';
  protected $_Itemid = 0;
  protected $_idCounter = 0;
  protected $_buffer = '';
  protected $_remainingSearches = array();

  public function __construct($args) {
    extract($args);
    $this->_filterData = $filterData;
    $this->_filtersIntro = $filtersIntro;
    $this->_remainingSearches = $remainingSearches;
    $this->_populateState();
  }

  protected function _populateState(){
    $jinput = JFactory::getApplication()->input;
    $this->_search = $jinput->get('search', null, 'ARRAY');
    $this->_limit = $jinput->get('limit', $this->_limit, 'INT');
    $this->_offset = $jinput->get('offset', $this->_offset, 'INT');
    $this->_Itemid = $jinput->get('Itemid', 0, 'INT');
  }
  
  public function __toString() {
    return $this->_buildFilterOutput();
  }

  protected function _buildFilterOutput() {
    $output = $this->_getFiltersTopHtml();   
    foreach ($this->_filterData as $group) {
      $output .= $this->_getGroupHeading($group['title']);
      $output .= $this->_getItemList($group['items']);  
      $output .= $this->_closeGroup();
    }
    $output .= $this->_getFiltersBottomHtml();
    return $output;
  }

  protected function _getGroupHeading($title) {
      return '<li><h4>' . $title . '</h4><hr/><ul>';
  }

  protected function _getItemList($items){
    return ($items[0]->descends) ? $this->_buildDescendingLists($items) : $this->_buildFlatList($items);
  }

  protected function _buildDescendingLists($items) {
    $output = '';
    foreach($items as $itemList){
      if($this->_isOneItemList($itemList)){
	$output .= '<li>' . $this->_buildItem($itemList);
      } else {
	$output .= $this->_buildDescendableLi($itemList);
      }     
    }
    return $output;
  }

  protected function _isOneItemList($itemList){
    return (count($itemList->value) === 1);
  }

  protected function _buildDescendableLi($itemList){
      $output = '<li class="descendable"><span>' . $itemList->name . '</span><ul>';
      $output .= $this->_buildItem($itemList);
      $output .= '</ul></li>';
      return $output;
  }


  protected function _buildFlatList($items) {
    return $this->_buildListItems($items);
  }

  protected function _closeGroup() {
    return '</ul></li>';
  }

  protected function _buildListItems($items) {
    $output = '';
    foreach($items as $item){
      $output .= $this->_buildItem($item);
    }
    return $output;
  }

  protected function _buildItem($item) {
    $listItems = "";
    $count = count ($item->value);
    $i = 0;    
    foreach ($item->value as $value) {
      $i = ++$i;  
      $this->_idCounter++;
      $element = $this->_getElement(array('type' => $this->_getFieldType($item->fields_type),
					  'name' => "search[{$item->id}][]",
					  'value' => $value->value,
					  'id' => 'input' . $this->_idCounter,
					  'disabled' => $this->_isDisabled($item->id, $value->value),
					  'checked' => $this->_isChecked($item, $value),
					  'onclick' => null));
      
     $lastnode = ($count == $i) ? 'lastnode' : 'node';
     $listItems .= '<li class="'.$lastnode.'">' . $element . $this->_getLabel($value->name) . '</li>';
    }
    return $listItems;
  }

  protected function _getFieldType($type) {
    if($type === '1') return 'Checkbox';
    return 'Radio';
  }

  protected function _isChecked($item, $value) {
    if ((isset($this->_search) && isset($this->_search[$item->id]) && in_array($value->value,$this->_search[$item->id]))) return true;
    return false;
  }

  protected function _isDisabled($extraFieldId, $inputValue) {
    if(array_key_exists($extraFieldId, $this->_remainingSearches)){
      return (in_array($inputValue, $this->_remainingSearches[$extraFieldId])) ? 'false' : 'true';
    }
    return 'true';
  }

  protected function _getLabel($text) {
    return '<label for="input' . $this->_idCounter . '">' . $text . '</label>';
  }

  protected function _getElement($config) {
    $className = 'K2FilterableField' . $config['type'];
    if(class_exists($className)) return new $className($config);
    return '';
  }
  
  protected function _getFiltersTopHtml(){
    $top = $this->_getContainerAndFormTopHtml();
    $top .= $this->_getFiltersIntro();
    $top .= "\n<ul>";
    return $top;
  }
  
  protected function _getContainerAndFormTopHtml() {
    $action = JURI::base() . 'index.php?option=com_k2filterable&Itemid=' . $this->_Itemid . '&format=raw';
    return <<<TOP
<div class="filterContainer">
<form id="filterForm" method="GET" action="{$action}">
TOP;
  }

  protected function _getFiltersIntro(){
    if($this->_filtersIntro){
      return "\n<p id=\"filtersIntro\">" . $this->_filtersIntro . "</p>\n";
    }
  }
  
  protected function _getFiltersBottomHtml(){
    return <<<EOF
</ul>
<input type="hidden" name="limit" value="{$this->_limit}" id="limit"/>
<input type="hidden" name="offset" value="{$this->_offset}" id="offset"/>
<div class="reset"><a href="javascript:void(0)" class="filterButton">Reset</a></div>
</form>
</div>
EOF;
  }

}
