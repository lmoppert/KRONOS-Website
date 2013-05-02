<?php
/**
* @version		1.5.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined( '_JEXEC' ) or die( 'Restricted access' );

class AcesearchControllerFilters extends AcesearchController {
	
	function __construct() {
		if (!JFactory::getUser()->authorise('acesearch.filters', 'com_acesearch')) {
			//return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		parent::__construct('filters');
	}

	function editGroup() {
		// Check token
		//JRequest::checkToken() or jexit('Invalid Token');

		JRequest::setVar('hidemainmenu', 1);

		$view = $this->getView(ucfirst($this->_context), 'gedit');
		$view->setModel($this->_model, true);
		$view->edit('gedit');
	}

	function deleteGroup() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');

        // Apply
        if ($this->_model->deleteGroup()) {
            $msg = JText::_('COM_ACESEARCH_COMMON_RECORDS_DELETED');
        }
        else {
            $msg = JText::_('COM_ACESEARCH_COMMON_RECORDS_DELETED_NOT');
        }

		// Return
		parent::route($msg);
	}

	function getExtensionFields() {
		$option = JRequest::getCmd('ext');
		if (empty($option)) {
			return;
		}

        echo $this->_model->getExtensionFields($option);
	}

	function editSave() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');

		// Get post
		$post = JRequest::get('post');

		if ($post['is_group'] == '1') {
			$table = 'AcesearchGroups';
		}
		else {
			$this->_model->getFilterParams($post);
			$table = 'AcesearchFilters';
		}

		// Save record
		if (!parent::saveRecord($post, $table , $post['id'])) {
			return JError::raiseWarning(500, JText::_('COM_ACESEARCH_COMMON_RECORD_SAVED_NOT'));
		} else {
			if ($post['modal'] == '1') {
				// Display message
				JFactory::getApplication()->enqueueMessage(JText::_('COM_ACESEARCH_COMMON_RECORD_SAVED'));
			} else {
				// Return
				parent::route(JText::_('COM_ACESEARCH_COMMON_RECORD_SAVED'));
			}
		}
	}
	
	function editApply() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');

		// Get post
		$post = JRequest::get('post');
		
		if ($post['is_group'] == '1') {
			$table = 'AcesearchGroups';
		}
		else {
			$this->_model->getFilterParams($post);
			$table = 'AcesearchFilters';
		}
		
		// Save record
		if (!parent::saveRecord($post, $table, $post['id'])) {
			return JError::raiseWarning(500, JText::_('COM_ACESEARCH_COMMON_RECORD_SAVED_NOT'));
		} else {
			if ($post['modal'] == '1') { 
				// Return
				$this->setRedirect('index.php?option=com_acesearch&controller=filters&task=edit&cid[]='.$post['id'].'&tmpl=component', JText::_('COM_ACESEARCH_COMMON_RECORD_SAVED'));
			} else { 
				// Return
				$this->setRedirect('index.php?option=com_acesearch&controller=filters&task=edit&cid[]='.$post['id'], JText::_('COM_ACESEARCH_COMMON_RECORD_SAVED'));
			}
		}
	}
}