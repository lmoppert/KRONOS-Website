<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

abstract class K2FilterableHelper {

	public static function getStateOptions() {
		$options	= array();
                $options[] = JHtml::_('select.option', '0', JText::_('JENABLED'));
                $options[] = JHtml::_('select.option', '1', JText::_('JDISABLED'));
		return $options;
	}


}
