<?php

/**
* Gavick GK Stock - main file
* @package Joomla!
* @Copyright (C) 2009-2011 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: GK4 1.0 $
**/

/**
	access restriction
**/
defined('_JEXEC') or die('Restricted access');

/**
	Loading helper class
**/

// 
require_once (dirname(__FILE__).DS.'helper.php');
//
$helper = new GKSHelper();
//
$helper->init();
$helper->validateVariables($params, $module);
$helper->getData();
$helper->parseData();
$helper->renderLayout();

/* EOF */