<?php
/**
* @version		1.5.0
* @package		AceSearch
* @subpackage	AceSearch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Model Class
class AcesearchModelCSS extends AcesearchModel {
	
	// Main constructer
	function __construct() {
        parent::__construct('css');
    }
	
	function save() {
		
		$filecontent = JRequest::getVar('filecontent', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$file = JPATH_ACESEARCH.'/assets/css/acesearch.css';
		
		// Set FTP credentials, if given
		jimport('joomla.client.helper');		
		//Import filesystem libraries. Perhaps not necessary, but does not hurt
		jimport('joomla.filesystem.file');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');
		
		if (JFile::exists($file)) {
		
			// Try to make the params file writeable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755') || !JPath::setPermissions($file, '0644')) {
				JError::raiseNotice('SOME_ERROR_CODE', JText::_('Could not make the template parameter file writable'));
			}
			
			$return = JFile::write(JPATH_ACESEARCH.'/assets/css/acesearch.css', $filecontent);

			// Try to make the css file unwriteable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555') || !JPath::setPermissions($file, '0644')) {
				JError::raiseNotice('SOME_ERROR_CODE', JText::_('Could not make the css file unwritable'));
			}
			
			return $return;
		}
		else {
			return false;
		}
		
	}
}