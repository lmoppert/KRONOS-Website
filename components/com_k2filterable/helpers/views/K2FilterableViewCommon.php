<?php

defined('_JEXEC') or die('Access Restricted');

jimport('joomla.application.component.view');
jimport('joomla.html.html');

class K2filterableViewCommon extends JView {

  public $resultsIntro = '';
  public $filtersIntro = '';
  public $filters = null;
  public $results = array();
  protected $_k2ItemMenu = 0;
  protected $_k2Itemid = 0;
  protected $_linkHelper = null;


  public function display($tpl = null) {
    $this->_loadAssets();
    $this->_loadMenuItemSettings();
    $this->_loadData();
    $this->_replaceCountToken();
    parent::display($tpl);
  }
  
  protected function _loadData() {
    $this->results = $this->get('all');
    $this->filters = new FilterFormBuilder(array(
						 'filterData' => $this->get('filters'), 
						 'filtersIntro' => $this->filtersIntro,
						 'remainingSearches' => $this->get('possibleRemainingSearches'))
					   );
    $this->count = $this->get('count');
  }
  
  protected function _loadAssets() {
    JHtml::_('behavior.mootools');
    JHtml::stylesheet('com_k2filterable/k2filterable.css', array(), true, false, false);
    JHtml::script('com_k2filterable/filters.js', false, true, false, false);
  }
  
  protected function _loadMenuItemSettings(){
    $params = JFactory::getApplication('site')->getParams();
    $this->resultsIntro = $params->get('resultsIntro', false);
    $this->filtersIntro = $params->get('filtersIntro', false);
    $this->_k2ItemMenu = $params->get('k2ItemMenu', false);
    $this->_k2Itemid = $params->get('k2Itemid', 0);
  }

  protected function _replaceCountToken(){
    $this->resultsIntro = str_ireplace('%count%', $this->count, $this->resultsIntro);
  }

  public function getLink(&$result, $text){
    if(!$this->_linkHelper) $this->_loadLinkHelper();
    return $this->_linkHelper->getLink($result->id, $text);
  }

  protected function _loadLinkHelper(){
    $this->_linkHelper = new K2FilterableMenuLinkLocator(array('menu' => $this->_k2ItemMenu,
							       'fallbackItemid' => $this->_k2Itemid));
  }

}  