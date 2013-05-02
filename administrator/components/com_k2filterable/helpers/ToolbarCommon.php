<?php 

defined ('_JEXEC') or die;

class ToolbarCommon {

    static public function title($title){
        JToolbarHelper::title($title, 'k2filterable');
    }

    static public function hideMainMenu(){
        $jinput = JFactory::getApplication()->input;
        $jinput->set('hidemainmenu', true);
    }

}