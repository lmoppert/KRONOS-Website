<?php

defined('_JEXEC') or die();

class K2FilterableMenuLinkLocator {

  protected $_menu = 0;
  protected $_fallbackItemid = 0;
  protected $_linkList = array();
  protected $_linkMap = null;

  public function __construct($args = array()){
    extract($args);
    $this->_menu = $menu;
    $this->_fallbackItemid = $fallbackItemid;
  }

  public function getLink($k2Itemid, $text){
    if(!$this->_linkMap) $this->_loadLinkMap();
    if(array_key_exists($k2Itemid, $this->_linkMap)) return $this->_createMenuItemLink($k2Itemid, $text);
    return $this->_createFallBackMenuLink($k2Itemid, $text);
  }

  protected function _createMenuItemLink($k2Itemid, $text){
    return JHtml::link($this->_linkMap[$k2Itemid]->path, $text, array('target' => '_blank'));
  }

  protected function _createFallBackMenuLink($k2Itemid, $text){
    $link = 'index.php?option=com_k2&view=item&layout=item&id=' . $k2Itemid . '&Itemid=' . $this->_fallbackItemid;
    return JHtml::link($link, $text, array('target' => '_blank'));
  }

  protected function _loadLinkMap(){
    $this->_loadLinkList();
    $this->_organizeListIntoMap();
  }

  protected function _loadLinkList(){
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select('m.path, m.link')
      ->from('`#__menu` AS m')
      ->innerJoin('`#__extensions` AS x ON m.component_id = x.extension_id')
      ->where('m.menutype = ' . $db->quote($this->_menu) . " AND m.published = '1' AND x.name = 'com_k2'");
    $db->setQuery($query);
    $this->_linkList = $db->loadObjectList();
  }

  protected function _organizeListIntoMap(){
    if(empty($this->_linkList)) return;
    foreach($this->_linkList as $link){
      $k2Id = $this->_extractK2Id($link->link);
      if($k2Id) $this->_linkMap[$k2Id] = $link;
    }
  }

  protected function _extractK2Id($link){
    $id = (int) preg_replace('|(.)+(id=)([0-9]+)(.*)|si', "$3", $link);
    if($id === 0) return false;
    return $id;
  }

}