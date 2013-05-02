<?php
/**
* @version		1.5.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class AcesearchViewAdvancedSearch extends JView {

	function display( $tpl = null){
		$mainframe =& JFactory::getApplication();
        $uri = JFactory::getURI();
		$document =& JFactory::getDocument();
        $params = $mainframe->getParams();
        
        $cache =& AcesearchFactory::getCache();
		$this->AcesearchConfig = AcesearchFactory::getConfig();

		JHTML::_('behavior.mootools');
		
		$document->addStyleSheet(JURI::root().'components/com_acesearch/assets/css/acesearch.css');

		if ($this->AcesearchConfig->enable_complete == 1) {
			$document->addScript(JURI::root().'components/com_acesearch/assets/js/autocompleter.js');
		}
		
        $component = $params->get('component', $uri->getVar('ext'));

        $filter = "";
        $req_filter = JRequest::getInt('filter');
        if (!empty($req_filter)) {
            $filter = '&filter='.$req_filter;

            $extensions = $cache->getFilterExtensions($req_filter);
            if (!empty($extensions) && is_array($extensions) && count($extensions) == 1) {
                $component = $extensions[0]->extension;
            }
        }
        elseif (empty($component)) {
            $extensions = $cache->getExtensions();
            if (!empty($extensions) && is_array($extensions) && count($extensions) == 1) {
                foreach ($extensions as $extension) {
                    $component = $extension->extension;
                    break;
                }
            }
        }
		
		$lists = AcesearchHTML::getExtensionList();

        if ($this->AcesearchConfig->show_order == '1') {
            $lists['order'] = AcesearchHTML::_renderFieldOrder();
        }
		
		$this->assignRef('params', 			$params);
		$this->assignRef('component', 		$component);
		$this->assignRef('filter', 		    $filter);
		$this->assignRef('uri', 		    $uri);
		$this->assignRef('lists', 			$lists);
		$this->assignRef('results', 		$this->get('Data'));
		$this->assignRef('pagination',		$this->get('Pagination'));
		
		parent::display($tpl);
	}
}