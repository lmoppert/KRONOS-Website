<?php 

defined('_JEXEC') or die('Access Restricted');

jimport('joomla.application.component.view');

abstract class ViewCommonExecution extends JView {

    public function display($tmpl = null){
        $this->_loadData();
        $this->_initToolbar();
        $this->_initAssets();
        $this->_setTemplateFile();
        parent::display($tmpl);
    }

    protected function _loadData(){
    
    }

    protected function _initToolbar(){

    }

    protected function _initAssets(){

    }
    
    protected function _setTemplateFile(){

    }

}