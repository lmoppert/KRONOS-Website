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


$backuppath =  JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_menuassignment_k2'.DS.'backup'.DS;
$destinationpath =  JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_k2'.DS;


JFile::move($backuppath.'models'.DS.'item.php', $destinationpath.'models'.DS.'item.php');
JFile::move($backuppath.'views'.DS.'default.php', $destinationpath.'views'.DS.'item'.DS.'tmpl'.DS.'default.php');

?>