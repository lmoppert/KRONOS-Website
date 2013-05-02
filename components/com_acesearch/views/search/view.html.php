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

class AcesearchViewSearch extends JView {

	function display($tpl = null) {
		$mainframe =& JFactory::getApplication();
		$params =& $mainframe->getParams();
		$document =& JFactory::getDocument();
		$this->AcesearchConfig = AcesearchFactory::getConfig();
		$this->extensions = AcesearchCache::getExtensions();
		
		$this->suffix = $this->hiddenfilt ="";
		
		$flt = JRequest::getInt('filter');
		if(!empty($flt)) {
			$this->suffix = '&filter='.$flt;
			$this->hiddenfilt = '<input type="hidden" name="filter" value="'.$flt.'"/>';
		}
		
		$this->Itemid = '';
		$i_id = JRequest::getInt('Itemid', 0, 'get');
		if (!empty($i_id)) {
			$this->Itemid = '&Itemid='.$i_id;
		}
		
		$this->query = AcesearchSearch::getSearchQuery();
		
		JHTML::_('behavior.mootools');
		
		$document->addStyleSheet(JURI::root().'components/com_acesearch/assets/css/acesearch.css');
		
		if ($this->AcesearchConfig->google == '1') {
			$document->addStyleSheet(JURI::root().'components/com_acesearch/assets/css/acesearch_google.css');
		}
		elseif ($this->AcesearchConfig->yahoo_sections  == '1') {
			$document->addStyleSheet(JURI::root().'components/com_acesearch/assets/css/acesearch_style1.css');
		}
		
		// Get autocomplete
		if ($this->AcesearchConfig->enable_complete == '1') {
			$document->addScript(JURI::root().'components/com_acesearch/assets/js/autocompleter.js');
		}
		
		$lists = AcesearchHTML::getExtensionList();

        if ($this->AcesearchConfig->show_order == '1') {
            $css = '';
            if ($this->AcesearchConfig->google == '1') {
                $css = 'class="acesearch_selectbox_module"';
            }

            $lists['order'] = AcesearchHTML::_renderFieldOrder('', $css, 'onchange="document.acesearchForm.submit();"');
        }

		$this->assignRef('params', 		$params);
		$this->assignRef('lists', 		$lists);
		$this->assignRef('results', 	$this->get('Data'));
		$this->assignRef('total', 		$this->get('Total'));
		$this->assignRef('refines', 	$this->get('Refines'));
		$this->assignRef('pagination', 	$this->get('Pagination'));
		
		parent::display($tpl);
	}

    function renderModules($position = 'acesearch_top') {
		$modules = JModuleHelper::getModules($position);

        if (count($modules) > 0) {
            $renderer = JFactory::getDocument()->loadRenderer('module');
            $attribs = array('style' => 'xhtml');

            ?><div><?php

            foreach ($modules as $mod) {
                echo $renderer->render($mod, $attribs);
            }

            ?></div><?php
        }
    }
}