<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class K2FilterableModelFilterGroup extends JModelAdmin {

        protected $text_prefix = 'COM_K2FILTERABLE';
  
  	protected function populateState() {
		$app = JFactory::getApplication('administrator');

        	if (!($pk = (int) JRequest::getInt('id'))) {
			if ($extensionId = (int) $app->getUserState('com_k2filterable.filtergroup.extension_id')) {
				$this->setState('extension.id', $extensionId);
			}
		}
		$this->setState('filtergroup.id', $pk);
		$params	= JComponentHelper::getParams('com_k2filterable');
		$this->setState('params', $params);
	}
        
	public function delete(&$pks) {
		$pks	= (array) $pks;
		$user	= JFactory::getUser();
		$table	= $this->getTable();

		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$user->authorise('core.delete', 'com_k2filterable')) {
					JError::raiseWarning(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
					return;
				}

				if (!$table->delete($pk)) {
					throw new Exception($table->getError());
				} else {
					$db	= $this->getDbo();
					$query	= $db->getQuery(true);
					$query->delete();
					$query->from('#__k2filterable_group');
					$query->where('id='.(int)$pk);
					$db->setQuery((string)$query);
					$db->query();
				}

				parent::cleanCache($table->module, $table->client_id);
			}
			else {
				throw new Exception($table->getError());
			}
		}

		$this->cleanCache();

		return true;
	}
        
        public function getForm($data = array(), $loadData = true) {
            
		if (empty($data)) {
			$item		= $this->getItem();
			$extrafields         = $item->extrafields;
		} else {
			$extrafields		= JArrayHelper::getValue($data, 'extrafields');
		}

                $form = $this->loadForm('com_k2filterable.filtergroup', 'filterGroup', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false; 

		if (!$this->canEditState((object) $data)) {
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
		}
		return $form;
        }
        

        protected function loadFormData() {

                $app = JFactory::getApplication();

		$data = $app->getUserState('com_k2filterable.edit.filtergroup.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
                return $data;
        }
        

         public function getItem($pk = null) {
             
                if ($item = parent::getItem($pk)) {
                }
                return $item;
        }

        public function getTable($type = 'FilterGroup', $prefix = 'K2FilterableTable', $config = array()) {
            
            return JTable::getInstance($type, $prefix, $config);
        }
  
  
       protected function prepareTable(&$table) {
      
      	$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);

        }
        
        protected function preprocessForm(JForm $form, $data, $group = 'content') {
            
		parent::preprocessForm($form, $data, $group);
	}
    
    	public function save($data) {
            
		$dispatcher = JDispatcher::getInstance();
		$table		= $this->getTable();
		$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('filtergroup.id');
		$isNew		= true;
                
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		$this->prepareTable($table);

		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		$result = $dispatcher->trigger('onExtensionBeforeSave', array('com_k2filterable.filtergroup', &$table, $isNew));
		if (in_array(false, $result, true)) {
			$this->setError($table->getError());
			return false;
		}

		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		$dispatcher->trigger('onExtensionAfterSave', array('com_k2filterable.filtergroup', &$table, $isNew));

		$db =& JFactory::getDBO();
                $query	= $db->getQuery(true);
		$query->select('extension_id');
		$query->from('#__extensions AS e');
		$query->leftJoin('#__modules AS m ON e.element = m.module');
		$query->where('m.id = '.(int) $table->id);
		$db->setQuery($query);

		$extensionId = $db->loadResult();

		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
			return;
		}

		$this->setState('module.extension_id',	$extensionId);
		$this->setState('module.id',			$table->id);

		$this->cleanCache();

		parent::cleanCache($table->filtergroup, $table->client_id);

		return true;
	}

        function validate($form, $data, $group = null) {
                
                $title = $data['title'];
                $id = $data['id'];
                $extrafields = $data['extrafields'];
                
                $error = false;
                if (!is_string($extrafields) || $extrafields == '') {JError::raiseWarning( 100, JText::_('COM_K2FILTERABLE_ERROR_NOT_SELECTED') ); $error=true;}

                if ($title != "") {
                        $db =& JFactory::getDBO();
                        $query	= $db->getQuery(true);
                        $query->select('id');
                        $query->from('#__k2filterable_group');
                        $query->where('id <> '.$db->quote($id).' AND title ='.$db->quote($title)); 
                        $db->setQuery($query);

                        $result = $db->loadResult();


                        if ($error = $db->getErrorMsg()) {
                                JError::raiseWarning(500, $error);
                                return;
                        }

                        if (isset($result)) {JError::raiseWarning( 100, JText::_('COM_K2FILTERABLE_ERROR_TITLE_EXIST') ); $error=true;}
                } else {
                    JError::raiseWarning( 100, JText::_('COM_K2FILTERABLE_ERROR_TITLE_NOT_EXIST') ); $error=true;
                }
                if ($error) return false;
		return parent::validate($form, $data, $group);
	}
        
}