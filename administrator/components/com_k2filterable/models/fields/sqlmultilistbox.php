<?php

defined('JPATH_BASE') or die();

JFormHelper::loadFieldClass('list');

class JFormFieldSQLMultiListBox extends JFormFieldList 
{
	public $type = 'SQLMultiListBox';
                        
	protected function getInput() {

                $selected_values = $this->value ? $this->value : 'none';
                if ($selected_values != 'none') $selected_values = explode(',', $selected_values); else $selected_values = array();

                $db =& JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,name');
                $query->from('#__k2_extra_fields');
                $query->where('published = 1');
                $query->order('ordering ASC');
                $db->setQuery($query);
                
                $results = $db->loadAssocList('id');

                if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
			return $results;
		}

                $optionsString = '';
                
                $array_data = array ();
                $selected_data = array ();
                
                foreach($results as $result) :
                   if(!in_array($result['id'],$selected_values)) {
                         $array_data[] = $result;
                    }
                endforeach;

                foreach($selected_values as $selected_value) :
                        $selected_data[] = $results[$selected_value];
                endforeach;
               
                $optionsString .= '<div id="dragable_lists"><ul title="'.JText::_('COM_K2FILTERABLE_TOOLTIP_SOURCE').'">';
               
                foreach($array_data as $data) :
                     $optionsString .= '<li id="dl_'.$data['id'].'">'.$data['name'].'</li>';
                endforeach;
               
                $optionsString .= '</ul><ul title="'.JText::_('COM_K2FILTERABLE_TOOLTIP_SELECTED').'">';
               
                foreach($selected_data as $data) :
                     $optionsString .= '<li id="dl_'.$data['id'].'">'.$data['name'].'</li>';
                endforeach;

                $optionsString .= '</ul></div>';
                $optionsString .= '<input type="hidden" id="'.$this->id.'" name="jform[extrafields]" value="'.implode(',', $selected_values).'" />';
               
                return $optionsString;
	}
}
