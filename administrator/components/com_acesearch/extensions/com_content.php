<?php
/*
* @package		AceSearch
* @subpackage	Content
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		http://www.joomace.net/company/license
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.'/components/com_content/helpers/route.php');

class Acesearch_com_content extends AcesearchExtension {
	
	public function getResults() {
		$cats = array();
		
		$items = self::_getItems();

		$cat = parent::getInt('category');
		$user = parent::getString('user');
		if (empty($cat) && empty($user) && $this->params->get('search_categories', '1') == '1') {
			$cats = self::_getCategories(); 
		}
		
		$results = array_merge($items, $cats);
		
		return $results;
	}
	
	protected function _getItems() {
		$where = parent::getSearchFieldsWhere('a.title:name, a.introtext:description, a.`fulltext`:description');
		if (empty($where)){
			return array();
		}
		
		if ($this->site) {
			$where[] = 'a.state = 1';
			
			$date_now = JFactory::getDate()->toMySQL();
			$date_null = JFactory::getDBO()->getNullDate();
			$where[] = '(a.publish_up = "'.$date_null.'" OR a.publish_up <= "'.$date_now.'")';
			$where[] = '(a.publish_down = "'.$date_null.'" OR a.publish_down >= "'.$date_now.'")';
			
			if ($this->AcesearchConfig->access_checker == '1') {
				$where[] = parent::getAccessLevelsWhere('a.access');
			}
		}

        if ($this->site && $this->params->get('search_uncategorised', '1') == '0'){
            $where[] = 'a.catid != 0';
        }
		
		parent::getFilterWhere($where, array('category' => 'a.catid'));
		parent::getUserWhere($where, 'a.created_by');			
		parent::getDateWhere($where, 'a.created');	

		$where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
		
		$group_by = ' GROUP BY a.id';
		
		$order_by = parent::getOrder();
		
		$identifier = parent::getIdentifier();
		
		$relevance = parent::getRelevance(array('title' => 'a.title', 'desc' => 'a.introtext, a.`fulltext`'));
		
		$slug = parent::getSlug('a.id', 'a.alias');
		$catslug = parent::getSlug('c.id', 'c.alias', 'catslug');
		
		$query = "SELECT {$identifier}, {$relevance}, {$slug}, {$catslug}, a.id, a.title AS name, a.sectionid,".
		" CONCAT(a.introtext, a.`fulltext`) AS description, a.created AS date, a.hits, c.title AS category".
		" FROM #__content AS a LEFT JOIN #__categories AS c ON c.id = a.catid".
		" {$where}{$group_by}{$order_by}";
		
		return AceDatabase::loadObjectList($query, '', 0, parent::getSqlLimit());
	}
	
	public function _getItemURL(&$item) {
        if (parent::is16()) {
            if ($this->site){
                $item->link = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug);
            }
            else {
                $item->link = 'index.php?option=com_content&task=article.edit&id='.$item->id;
            }
        }
        else {
            if ($this->site){
                $item->link = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid);
            }
            else {
                $item->link = 'index.php?option=com_content&sectionid='.$item->sectionid.'&task=edit&cid[]='.$item->id;
            }
        }

        unset($item->catslug);
        unset($item->slug);
        unset($item->sectionid);
    }
	
	public function _getCategories() {
        if (parent::is16()) {
            return parent::_getCategories('com_content');
        }
        else {
            $where = parent::getSearchFieldsWhere('c.title:name, c.description:description');
            if (empty($where)) {
                return array();
            }

            $where[] = "s.scope = 'content'";

            if ($this->site) {
                $where[] = "c.published = 1";

                if ($this->AcesearchConfig->access_checker == '1') {
					$where[] = parent::getAccessLevelsWhere('c.access');
                }
            }

            parent::getFilterWhere($where, array('category' => 'c.id'));

            $where = (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
            $order_by = parent::getOrder(0, 0);

            $identifier = parent::getIdentifier('Category');
            $relevance = parent::getRelevance(array('title' => 'c.title', 'desc' => 'c.description'));

            $query = "SELECT {$identifier}, {$relevance}, c.id, c.alias, c.title AS name, c.description".
                     " FROM #__categories AS c LEFT JOIN #__sections AS s ON c.section = s.id {$where} {$order_by}";

            return AceDatabase::loadObjectList($query, '', 0, parent::getSqlLimit());
        }
	}
	
	public function _getCategoryURL(&$cat) {
        if (parent::is16()) {
            if ($this->site) {
                $cat->link = ContentHelperRoute::getCategoryRoute($cat->id.':'.$cat->alias,'');
            }
            else {
                $cat->link = 'index.php?option=com_categories&view=category&layout=edit&id='.$cat->id.'&extension=com_content';
            }
        }
        else {
            if ($this->site) {
                $cat->link = ContentHelperRoute::getCategoryRoute($cat->id.':'.$cat->alias,'');
            }
            else {
                $cat->link = 'index.php?option=com_categories&section=com_content&task=edit&cid[]='.$cat->id.'&type=content';
            }
        }
	}
	
	public function getCategoryList($apply_filter = '0') {
		$where = array();

        if (parent::is16()) {
            if ($this->site || $apply_filter == '1') {
                $where[] = "published = 1";

                if ($this->AcesearchConfig->access_checker == '1') {
                    $where[] = parent::getAccessLevelsWhere();
                }

                $filter = JRequest::getInt('filter');
                if (!empty($filter)) {
                    parent::getFilterWhere($where, array('category' => 'id'));
                }
            }

            $where[] = "parent_id > 0 AND extension = 'com_content'";
			
            $where = (count($where) ? ' WHERE ' . implode(' AND ' , $where): '');
			
            return AceDatabase::loadObjectList("SELECT id, title AS name, parent_id AS parent FROM #__categories {$where} ORDER BY parent_id, lft");
        }
        else{
            if ($this->site || $apply_filter == '1') {
                $where[] = "c.published = 1";

                if ($this->AcesearchConfig->access_checker == '1') {
                    $where[] = 'c.access <= '.$this->aid.' AND s.access <= '.$this->aid;
                }

                $filter = JRequest::getInt('filter');
                if (!empty($filter)) {
                    parent::getFilterWhere($where, array('category' => 'c.id'));
                }
            }

            $where[] = "s.scope = 'content' AND c.section = s.id";
			
            $where = (count($where) ? ' WHERE ' . implode(' AND ' , $where): '');
			
            return AceDatabase::loadObjectList("SELECT c.id, CONCAT_WS(' / ', s.title, c.title) AS name FROM #__categories AS c, #__sections AS s {$where} ORDER BY s.title, c.title");
        }
	}
}