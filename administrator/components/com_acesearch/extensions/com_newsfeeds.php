<?php
/*
* @package		AceSearch
* @subpackage	News Feeds
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		http://www.joomace.net/company/license
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class Acesearch_com_newsfeeds extends AcesearchExtension {

	public function getResults() {
		$cats = $items = array();
		$items = self::_getItems();
		
		$cat = parent::getInt('category');
		if (empty($cat) && $this->params->get('search_categories', '1') == '1') {
			$cats = parent::_getCategories($this->extension->extension);
		}
		
		$results = array_merge($items, $cats);
		
		return $results;
	}
	
	protected function _getItems() {
		$where = parent::getSearchFieldsWhere('n.name:name');
		if (empty($where)){
			return array();
		}
		
		if ($this->site) {
			$where[] = 'n.published = 1';
		}
		
		parent::getFilterWhere($where, array('category' => 'n.catid'));
		
		$where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
		
		$group_by = ' GROUP BY n.id';
		
		$order_by = parent::getOrder(0, 0);
		
		$identifier = parent::getIdentifier();
		
		$relevance = parent::getRelevance(array('title' => 'n.name'));
		
		$itemslug = parent::getSlug('n.id', 'n.alias', 'itemslug');
		$catslug = parent::getSlug('c.id', 'c.alias', 'catslug');
		
		$query = "SELECT {$identifier}, {$relevance}, {$itemslug}, {$catslug}, n.id, n.name AS name, c.title AS category".
		" FROM #__newsfeeds AS n".
		" LEFT JOIN #__categories AS c ON n.catid = c.id".
		" {$where}{$group_by}{$order_by}";		
		
		return AceDatabase::loadObjectList($query, '', 0, parent::getSqlLimit());
	}
	
	public function _getItemURL(&$item) {
		if ($this->site) {				
			$item->link = 'index.php?option=com_newsfeeds&view=newsfeed&catid='.$item->catslug.'&id='.$item->itemslug . parent::getItemid(array('view' => 'newsfeed'));
		}
        else {
            if (parent::is16()) {
                $item->link = 'index.php?option=com_newsfeeds&view=newsfeed&layout=edit&id='.$item->id;
            }
            else {
                $item->link = 'index.php?option=com_newsfeeds&task=edit&cid[]='.$item->id;
            }
        }
    }
	
	public function _getCategoryURL(&$cat) {
        if ($this->site) {
            $cat->link = 'index.php?option=com_newsfeeds&view=category&id='.$cat->id.$cat->alias. parent::getItemid(array('view' => 'category'));
        }
        else {
            if (parent::is16()) {
                $cat->link = 'index.php?option=com_categories&view=category&layout=edit&id='.$cat->id.'&extension=com_newsfeeds';
            }
            else {
                $cat->link = 'index.php?option=com_categories&section=com_newsfeeds&task=edit&cid[]='.$cat->id.'&type=other';
            }
        }
	}
	
	public function getCategoryList($apply_filter = '0') {
		return parent::_getCategoryList($this->extension->extension, $apply_filter);
	}
}