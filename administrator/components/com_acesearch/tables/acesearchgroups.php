<?php
/**
* @version		1.5.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

class TableAcesearchGroups extends JTable {

	var $id 	= null;
	var $title	= null;
	
	function __construct(& $db) {
		parent::__construct('#__acesearch_filters_groups', 'id', $db);
	}
}