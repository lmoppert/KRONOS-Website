<?php
/*
* @package		AceSearch
* @subpackage	Components
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		http://www.joomace.net/company/license
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class AceSearch_com_components extends AcesearchExtension {
	
	public function getResults() {
		$results = self::_getItems();
		
		return $results;
	}

	protected function _getItems() {
        if (parent::is16()) {
			$query 		= parent::getSearchQuery('query');   
			$exact 		= parent::getSearchQuery('exact');
			$all 		= parent::getSearchQuery('all');
			$any 		= parent::getSearchQuery('any');
			$none 		= parent::getSearchQuery('none');
			
			if (!empty($all)) {
				$q = $all;
			}
			elseif (!empty($exact)) {
				$q = $exact;
			}
			elseif (!empty($query)) {
				$q = $query;
			}
			elseif (!empty($any)) {
				$q = $an;
			}
		
			$items = array();
            $lang =& JFactory::getLanguage();
			
            $rows = AceDatabase::loadResultArray("SELECT `element` FROM `#__extensions` WHERE `type` = 'component' ORDER BY `element`");
			
			$c = count($rows);
            for($i = 0; $i < $c; $i++) {
				$row = $rows[$i];
				
                $lang->load($row.'.sys', JPATH_ADMINISTRATOR);
                $title = JText::_($row);
				
				if (stristr($title, $q)) {
					$items[$i] = new stdClass();
					$items[$i]->acesearch_ext = 'com_components';
					$items[$i]->acesearch_type = 'Item';
					$items[$i]->name = $title;
					$items[$i]->admin_menu_link = 'option='.$row;
				}
            }

            return $items;
        }
        else {
            $where = parent::getSearchFieldsWhere('name');
            if (empty($where)){
                return array();
            }

            $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

            $order_by = " ORDER BY name";

            $identifier = parent::getIdentifier();

            $query = "SELECT {$identifier}, name, admin_menu_link".
            " FROM #__components".
            " {$where}{$order_by}";

            return AceDatabase::loadObjectList($query, '', 0, parent::getSqlLimit());
        }
	}
	
	public function _getItemURL(&$item) {			
		$item->link = 'index.php?'.$item->admin_menu_link;
    }
}