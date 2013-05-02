<?php

defined('_JEXEC') or die('Access Restricted');

require_once(JPATH_BASE . '/libraries/joomla/form/fields/checkbox.php');
require_once(JPATH_BASE . '/libraries/joomla/form/fields/radio.php');

class K2FilterableFieldCheckbox extends JFormFieldCheckbox {

  protected $element = null;

  public function __construct($args)  {
    $args['class'] = 'ajaxCheckbox';
    $this->value = (array_key_exists('checked', $args)) ? $args['checked'] : null;
    $this->id = (array_key_exists('id', $args)) ? $args['id'] : null;
    $this->name = (array_key_exists('name', $args)) ? $args['name'] : null;
    $this->element = K2FilterableFieldElementBuilder::get($args);
  }
  
  public function __toString() {
    return $this->getInput();
  }

}



class K2FilterableFieldRadio extends JFormFieldRadio {

  public function __construct($args)  {
    $args['class'] = 'ajaxRadioButton';
    $this->id = (array_key_exists('id', $args)) ? $args['id'] : null;
    $this->element = K2FilterableFieldElementBuilder::get($args);
  }

  public function __toString()  {
    $class = 'class="ajaxCheckbox"';
    $disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
    $checked = ($this->element['checked'] === false ) ? '' : ' checked="checked"';
    $onclick = $this->element['onclick'] ? ' onclick="' . (string) $this->element['onclick'] . '"' : '';
    $name = (array_key_exists('name', $this->element)) ? $this->element['name'] : '';
    
    return '<input type="radio" name="' . $name . '" id="' . $this->id . '"' . ' value="'
    . htmlspecialchars((string) $this->element['value'], ENT_COMPAT, 'UTF-8') . '" ' . $class . $checked . $disabled . $onclick . '/>';

  }
}


class K2FilterableFieldElementBuilder {

  static public function get($args){
    $element = array();
    foreach ($args as $key => $value) {
      $element[$key] = $value;
    }
    return $element;
  }

}
