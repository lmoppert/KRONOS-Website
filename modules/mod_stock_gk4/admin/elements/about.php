<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldAbout extends JFormField {
	protected $type = 'About';

	protected function getInput() {
		return '<div id="gk_about_us">' . JText::_('ABOUT_US_CONTENT') . '</div>';
	}
}
