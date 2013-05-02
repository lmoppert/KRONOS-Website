<?php


defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class K2FilterableModelFilterGroups extends JModelList {

        public function __construct($config = array()) {
            if (empty($config['filter_fields'])) {
             $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',               
                'title', 'a.title',
            );
        }

            parent::__construct($config);
        }

    	protected function populateState($ordering = null, $direction = null) {
		parent::populateState('a.title', 'asc');
	}

	protected function getStoreId($id = '') {		
		return parent::getStoreId($id);
	}

	protected function getListQuery() {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__k2filterable_group` AS a');

                $orderCol	= $this->state->get('list.ordering');
                $orderDirn	= $this->state->get('list.direction');
                if ($orderCol && $orderDirn) {
                  $query->order($db->escape($orderCol.' '.$orderDirn));
                }

		return $query;
	}
}
