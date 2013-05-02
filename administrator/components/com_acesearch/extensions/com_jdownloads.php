<?php
/*
* @package		AceSearch
* @subpackage	jDownloads
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		http://www.joomace.net/company/license
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class Acesearch_com_jdownloads extends AcesearchExtension {
	
	public function getResults() {
		$cats = array();
		$items = self::_getItems();
		
		$cat = parent::getInt('category');
		if (empty($cat) && ($this->params->get('search_categories', '1') == '1')) {
			$cats = self::_getCategories();
		}
		
		$results = array_merge($items, $cats);
		
		return $results;
	}
	
	protected function _getItems() {
		$where = parent::getSearchFieldsWhere('d.file_title:name, d.description:description, d.description_long:description');
		if (empty($where)){
			return array();
		}
		
		if ($this->site) {
			$where[] = '(d.published = 1)';
		}
		
		parent::getFilterWhere($where, array('category' => 'd.cat_id, c.parent_id'));
		parent::getFilterWhere($where, array('license:text' => 'd.license'));
		parent::getUserWhere($where, '', 'd.author');
		parent::getDateWhere($where, 'd.created');
		
		$where = (count($where) ? ' WHERE ' . implode(' AND ', $where): '');
		$order_by = parent::getOrder(1, 0);
		
		$identifier = parent::getIdentifier();
		$relevance = parent::getRelevance(array('title' => 'd.file_title', 'desc' => 'd.description_long'));
		
		$catslug = parent::getSlug('c.cat_id', 'c.cat_alias', 'catslug');
		$slug = parent::getSlug('d.file_id', 'd.file_alias');
		
		$query = "SELECT {$identifier}, {$relevance}, {$slug}, {$catslug}, d.file_id AS id, d.file_title AS name, d.cat_id AS catid, ".
		" CONCAT(d.description, d.description_long) AS description, d.date_added AS date, d.downloads AS downloads, c.cat_title AS category".
		" FROM #__jdownloads_files AS d LEFT JOIN #__jdownloads_cats AS c ON c.cat_id = d.cat_id {$where}{$order_by}";
		
		return AceDatabase::loadObjectList($query, '', 0, parent::getSqlLimit());
	}
	
	public function _getItemURL(&$item) {
		if ($this->site){
			$Itemid = parent::getItemid(array('view' => 'view.download', 'catid' => $item->catid, 'cid' => $item->id));
			$item->link = 'index.php?option=com_jdownloads&view=viewdownload&catid=' . $item->catslug . '&cid=' . $item->slug . $Itemid;
		}
		else {
			$item->link = 'index.php?option=com_jdownloads&task=files.edit&hidemainmenu=1&cid=' . $item->id;
		}
    }
	
	public function _getItemProperties(&$item) {
		$properties = '';
        
		if($this->params->get('show_section', '1') == '1'){
            $properties .= parent::_getProperty($item, 'section');
        }
		if($this->params->get('show_category', '1') == '1'){
            $properties .= parent::_getProperty($item, 'category');
        }
		if($this->params->get('show_date', '1') == '1'){
            $properties .= parent::_getProperty($item, 'date');
        }
		if($this->params->get('show_downloads', '1') == '1' && isset($item->downloads)){
			$properties .= JText::_('Downloads').': '.$item->downloads;
        }
		
		$item->properties =rtrim($properties, ' | ');
	}
	
	public function _getCategories() {
		$where = parent::getSearchFieldsWhere('cat_title:name, cat_description:description');
		if (empty($where)){
			return array();
		}
		
		if ($this->site) {
			$where[] = "published = 1";

			if ($this->AcesearchConfig->access_checker == '1') {
				$where[] = parent::getAccessLevelsWhere('cat_access');
			}
		}
		
		$where = (count($where) ? ' WHERE ' . implode(' AND ', $where): '');
		$order_by = parent::getOrder(0, 0);
		
		$identifier = parent::getIdentifier('Category');
		$relevance = parent::getRelevance(array('title' => 'cat_title', 'desc' => 'cat_description'));
		
		$query = "SELECT {$identifier}, {$relevance}, cat_id AS id, cat_title AS name, cat_description AS description, ".
		"CASE WHEN CHAR_LENGTH(cat_alias) THEN CONCAT_WS(':', cat_id, cat_alias) ELSE cat_id END AS catslug FROM #__jdownloads_cats {$where}{$order_by}";
		
		return AceDatabase::loadObjectList($query,'','0', parent::getSqlLimit());
	}
	
	function _getCategoryURL(&$cat) {
		if ($this->site){
			$Itemid = parent::getItemid(array('view' => 'viewcategory', 'catid' =>$cat->id));
			
			$cat->link = 'index.php?option=com_jdownloads&view=viewcategory&catid='.$cat->catslug . $Itemid;
		}
		else {
			$cat->link = 'index.php?option=com_jdownloads&task=categories.edit&hidemainmenu=1&cid='.$cat->id;
		}
    }
	
	public function getCategoryList($apply_filter = '0') {
		$where = array();
		
		if ($this->site || $apply_filter == '1') {
			$where[] = 'published = 1';

			if ($this->AcesearchConfig->access_checker == '1') {
				$where[] = parent::getAccessLevelsWhere('cat_access');
			}
			
			$filter = JRequest::getInt('filter');
			if(!empty($filter)) {
				parent::getFilterWhere($where, array('category' => 'cat_id, parent_id'));
			}
		}
		
		$where = (count($where) ? ' WHERE ' . implode(' AND ' , $where): '');
		
		return AceDatabase::loadObjectList("SELECT cat_id AS id, cat_title AS name, parent_id AS parent FROM #__jdownloads_cats {$where}");
	}
}