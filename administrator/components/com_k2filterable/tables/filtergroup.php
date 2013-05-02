<?php
defined('_JEXEC') or die;


class K2FilterableTableFilterGroup extends JTable {


    public function __construct(&$db) {
        parent::__construct('#__k2filterable_group', 'id', $db);
    }


    public function check() {

        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }

        return parent::check();
    }
}
