<?php
/**
* @version		1.5.0
* @package		AceSearch Library
* @subpackage	Factory
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Factory class
abstract class AcesearchFactory {
	
	public static function &getConfig() {
		static $instance;

        if (version_compare(PHP_VERSION, '5.2.0', '<')) {
			JError::raiseWarning('100', JText::sprintf('AceSearch requires PHP 5.2.x to run, please contact your hosting company.'));
			return false;
		}
		
		if (!is_object($instance)) {
			jimport('joomla.application.component.helper');

			$reg = new JRegistry(JComponentHelper::getParams('com_acesearch'));

            $instance = $reg->toObject()->data;
		}
		
		return $instance;
	}
	
	public static function &getCache($lifetime = '315360000') {
		static $instances = array();
		
		if (!isset($instances[$lifetime])) {
			require_once(JPATH_ADMINISTRATOR.'/components/com_acesearch/library/cache.php');
			$instances[$lifetime] = new AcesearchCache($lifetime);
		}
		
		return $instances[$lifetime];
	}

	public static function getTable($name) {
		static $tables = array();
		
		if (!isset($tables[$name])) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_acesearch/tables');
			$tables[$name] =& JTable::getInstance($name, 'Table');
		}
		
		return $tables[$name];
	}
	
	public static function &getExtension($option, $apply_filter = 0) {
		static $instances = array();

		$filter_prms = null;
        $cache = self::getCache();

		// Filters params
		$group_id = JRequest::getInt('filter');
		if (is_object($option)) {
			$filter_prms = new JRegistry($option->params);
			$option = $option->extension;
		}
		elseif (!empty($group_id)) { //For Html class
			$filter_prms = $cache->getFilterParams($group_id, $option);
		}
		
		if (!isset($instances[$option])) {
			$file = JPATH_ADMINISTRATOR.'/components/com_acesearch/extensions/'.$option.'.php';
			
			if (!file_exists($file)) {
				$instances[$option] = null;
				
				return $instances[$option];
			}
			
			require_once($file);

			$extensions = $cache->getExtensions($apply_filter);
			$ext_params = new JRegistry($extensions[$option]->params);
			
			$class_name = 'AceSearch_'.$option;
			
			$instances[$option] = new $class_name($extensions[$option], $ext_params, $filter_prms);
		}
		
		return $instances[$option];
	}

    public static function getExtraFields($option, $is_module = false) {
		$html = '';

		$fields = AcesearchUtility::getExtensionFieldsFromXml($option);

        if (empty($fields)) {
            return $html;
        }

		$extensions = self::getCache()->getExtensions();
		$ext_params = new JRegistry($extensions[$option]->params);

		if ($ext_params->get('handler', '1') == '2') {
			return $html;
		}

		$custom_name = $ext_params->get('custom_name', '');
		if (!empty($custom_name)) {
			$name = $custom_name;
		} else {
			$name = $extensions[$option]->name;
		}

		$filter_params = null;
		$group_id = JRequest::getInt('filter');
		if (!empty($group_id)) {
			$filter_params = self::getCache()->getFilterParams($group_id, $option);
		}

		$html_class = new AcesearchHTML($option, $ext_params, $filter_params, $is_module);

        $html = $html_class->getExtraFields($fields, $name);

        return $html;
    }

    public static function &getClass($class, $options = null) {
        static $instances = array();

		if (!isset($instances[$class])) {
			require_once(JPATH_ADMINISTRATOR.'/components/com_acesearch/library/'.$class.'.php');

            $class_name = 'Acesearch'.ucfirst($class);
			$instance[$class] = new $class_name($options);
		}

		return $instances[$class];
    }
}