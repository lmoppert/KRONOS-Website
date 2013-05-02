<?php
/*
* @package		AceSearch
* @subpackage	Modules
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		http://www.joomace.net/company/license
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class AceSearch_com_modules extends AcesearchExtension {

	public function getResults() {
		$results = self::_getItems();
		
		return $results;
	}

	protected function _getItems() {
		$where = parent::getSearchFieldsWhere('title:name');
		if (empty($where)){
			return array();
		}
		
		$where = (count($where) ? ' WHERE ' . implode(' AND ', $where): '');
		
		$order_by = parent::getOrder(0, 0);
		
		$identifier = parent::getIdentifier();
		
		$relevance = parent::getRelevance(array('title' => 'title'));
		
		$query = "SELECT {$identifier}, {$relevance}, id, title as name, client_id".
		" FROM #__modules".
		" {$where}{$order_by}";
		
		return AceDatabase::loadObjectList($query, '', 0, parent::getSqlLimit());
	}
	
	public function _getItemURL(&$item) {
        if ($this->admin) {
            if (parent::is16()) {
                $item->link = 'index.php?option=com_modules&view=module&layout=edit&id='.$item->id;
            }
            else{
                $item->link = 'index.php?option=com_modules&client='.$item->client_id.'&task=edit&cid[]='.$item->id;
            }
        }
    }
}