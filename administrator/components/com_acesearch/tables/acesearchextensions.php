<?php
/**
* @version		1.7.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

class TableAcesearchExtensions extends JTable {

	var $id 		 		= null;
	var $name 		 		= null;
	var $extension 	 		= null;
	var $params		 		= null;
	var $ordering			= null;
	var $client				= null;
	
	public function __construct(& $db) {
		parent::__construct('#__acesearch_extensions', 'id', $db);
	}
	
	public function bind($array) {
		if (is_array($array['params'])) {
            $params = new JRegistry($array['params']);

			$array['params'] = $params->toString();
		}
		
		return parent::bind($array);
	}
}