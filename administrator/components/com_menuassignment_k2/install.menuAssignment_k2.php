<?php
/*------------------------------------------------------------------------
 # com_menuassignment_k2 - Assign articles directly to menu and modules
 # ------------------------------------------------------------------------
 # author    US Joomla Pros
 # copyright Copyright (C) 2010 USJoomlaPros.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Websites: http://www.USJoomlaPros.com
 # Technical Support:  Forum - http://www.USJoomlaPros.com/forum.html
 -------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
 

		# get back up of files
		$sourcepath =  JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_k2'.DS;
		
		$backuppath =  JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_menuassignment_k2'.DS.'backup'.DS;
		
		$pluginpath =  JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_menuassignment_k2'.DS;
		
		
		  # Bakup files
		 
		 //Model file backup 
		 if(file_exists($sourcepath.'models'.DS.'item.php') && !file_exists($backuppath.'models'.DS.'item.php')){
			JFile::move($sourcepath.'models'.DS.'item.php', $backuppath.'models'.DS.'item.php');
		 }
		 
		 //View file backup 
		 if(file_exists($sourcepath.'views'.DS.'item'.DS.'tmpl'.DS.'default.php') && !file_exists($backuppath.'views'.DS.'default.php')){
			JFile::move($sourcepath.'views'.DS.'item'.DS.'tmpl'.DS.'default.php', $backuppath.'views'.DS.'default.php');
		 }
		 
		  # Copy files
		  
		  //Copy of model file
		 JFile::copy($pluginpath.'models'.DS.'item.php', $sourcepath.'models'.DS.'item.php');
		  
		  //Copy of view file
		 JFile::copy($pluginpath.'views'.DS.'default.php', $sourcepath.'views'.DS.'item'.DS.'tmpl'.DS.'default.php');

?>