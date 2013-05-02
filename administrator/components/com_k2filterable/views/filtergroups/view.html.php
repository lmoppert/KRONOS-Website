<?php

defined('_JEXEC') or die;

class K2FilterableViewFilterGroups extends JView {
  
  protected $items;
  protected $pagination;
  protected $state;
  
  public function display($tpl = null) {
    $this->_loadData();
    $this->_addToolbar();
    $this->_loadAssets();
    parent::display($tpl);
  }
  
  protected function _loadData() {
    $this->pagination = $this->get('Pagination');
    $this->items = $this->get('Items');
    $this->state = $this->get('State');
  }
  
  protected function _loadAssets() {
    JHtml::_('behavior.tooltip');
    JHtml::stylesheet('com_k2filterable/admin_k2filterable.css', array(), true, false, false);
  }
  
  protected function _addToolbar() {
    Toolbarcommon::title(JText::_('COM_K2FILTERABLE_TITLE_FILTER_GROUPS'));
    JToolBarHelper::addNew('filtergroup.add');
    JToolBarHelper::editList('filtergroup.edit');
    JToolBarHelper::deleteList('', 'filtergroups.delete');
  }

}