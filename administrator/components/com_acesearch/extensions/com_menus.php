<?php
/*
* @package		AceSearch
* @subpackage	Menus
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		http://www.joomace.net/company/license
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class AceSearch_com_menus extends AcesearchExtension {

	public function getResults() {
		$results = self::_getItems();
		
		return $results;
	}

	protected function _getItems() {
        $statuss = parent::is16();

        if ($statuss) {
            $where = parent::getSearchFieldsWhere('alias:name');
            $name = "title AS name";
            $names = "title";
        }
        else {
            $where = parent::getSearchFieldsWhere('name');
            $name = "name";
            $names = "name";
        }

		if (empty($where)){
			return array();
		}
		
		if ($this->site) {
            $where[] = "type NOT LIKE 'url'";

            if ($this->AcesearchConfig->access_checker == '1') {
                $where[] = parent::getAccessLevelsWhere('access');
            }
		}
		
		$where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
		
		$order_by = parent::getOrder(0, 0);
		
		$identifier = parent::getIdentifier();
		
		$relevance = parent::getRelevance(array('title' => $names));

		$query = "SELECT {$identifier}, {$relevance}, id, {$name}, link AS href".
		" FROM #__menu".
		" {$where}{$order_by}";
		
		return AceDatabase::loadObjectList($query, '', 0, parent::getSqlLimit());
	}
	
	public function _getItemURL(&$item) {
        if ($this->site){
            $item->link = $item->href.'&Itemid='.$item->id;
        }
        else {
			if(parent::is16()){
				$item->link = 'index.php?option=com_menus&task=item.edit&id='.$item->id;
			}
			else {
				$item->link = 'index.php?option=com_menus&menutype=mainmenu&task=edit&cid[]='.$item->id;
			}
		}
	}
}