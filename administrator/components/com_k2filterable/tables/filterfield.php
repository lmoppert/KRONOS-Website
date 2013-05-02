<?php 
/*-----------------------------------------
  License: GPL v 3.0 or later
-----------------------------------------*/

defined('_JEXEC') or die('Access Restricted');

class TableBlackList extends JTable {

  public $id = null;
  public $fk_userId = null;
  public $fk_ballotId = null;

  public function __construct(&$db){
    parent::__construct('#__mailinvote_blacklist', 'id', $db);
  }

}