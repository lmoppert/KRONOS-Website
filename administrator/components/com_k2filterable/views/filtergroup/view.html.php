<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class K2FilterableViewFilterGroup extends JView {
  
  protected $state;
  protected $item;
  protected $form;
  
  public function display($tpl = null) {
    $this->_loadData();
    $this->_loadAssets();
    $this->_addToolbar();
    parent::display($tpl);
  }
  
  protected function _loadData(){
    $this->state = $this->get('State');
    $this->item	= $this->get('Item');
    $this->form	= $this->get('Form');
  }

  protected function _loadAssets(){
    JHtml::_('behavior.mootools');
    JHtml::_('behavior.formvalidation');
    JHtml::script('com_k2filterable/admin_sort.js', false, true, false, false);
    JHtml::stylesheet('com_k2filterable/admin_k2filterable.css', array(), true, false, false);
  }
  
  protected function _addToolbar() {
    Toolbarcommon::title(JText::_('COM_K2FILTERABLE_TITLE_FILTER_GROUP'));
    Toolbarcommon::hideMainMenu();
    JToolBarHelper::save('filtergroup.save', 'JTOOLBAR_SAVE');
    JToolBarHelper::cancel('filtergroup.cancel', 'JTOOLBAR_CANCEL');
  }


}
