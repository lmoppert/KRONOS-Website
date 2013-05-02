<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldRequired extends JFormField {
	protected $type = 'required_field';

	protected function getInput() {
		return '<div id="gk_about_us">' . JText::_('MOD_FB_GK4_REQUIRED_CONTENT') . '</div>';
	}
}
