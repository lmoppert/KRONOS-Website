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

class AcesearchControllerSearch extends AcesearchController {
	
	function __construct() {
		if (!JFactory::getUser()->authorise('acesearch.search', 'com_acesearch')) {
			//return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		parent::__construct('search');
	}
	
	function advancedSearch() {
		$view =& $this->getView('Search', 'html');
		$view->setModel($this->_model, true);
		$view->view('advanced');
	}	
}