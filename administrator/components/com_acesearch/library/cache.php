<?php
/**
* @version		1.5.0
* @package		AceSearch Library
* @subpackage	Cache
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Imports
jimport('joomla.cache.cache');

// Cache class
class AcesearchCache extends JCache {

	function __construct($lifetime) {
		$this->AcesearchConfig = AcesearchFactory::getConfig();
		
        $options = array(
			'defaultgroup' 	=> 'com_acesearch',
			'cachebase'		=> JPATH_SITE.'/cache',
			'lifetime' 		=> $lifetime,
			'language' 		=> 'en-GB',
			'storage'		=> JFactory::getConfig()->get('cache_handler', 'file'),
            'caching'       => true,
		);
		
		parent::__construct($options);
	}

    function load($id) {
        $content = parent::get($id);
        
        if ($content === false) {
            return false;
        }
        
        $cache = @unserialize($content);
		
		if ($cache === false || !is_array($cache)) {
            return false;
        }
		
		return $cache;
    }
	
	function save($content, $id) {
		// Store the cache string
		for ($i = 0; $i < 5; $i++) {
            if (parent::store(serialize($content), $id)) {
                return;
            }
        }
		
		parent::remove($id);
	}
	
	function getExtensions($apply_filter = 0) {
		if ($this->AcesearchConfig->cache_extensions == 1) {
			$cache = AcesearchFactory::getCache();
			$cached_extensions = $cache->load('extensions');

			if (!empty($cached_extensions)) {
				return $cached_extensions;
			}
		}

        static $extensions;
		if (!isset($extensions)) {
            $levels	= JFactory::getUser()->getAuthorisedViewLevels();
            
			$fields = "id, name, extension, params";

            if (($apply_filter == 1) || (JFactory::getApplication()->isSite())) {
                $where = " WHERE params NOT LIKE '%\"handler\":\"0\"%' AND (client = 0 OR client = 2)";
            }
            elseif (JFactory::getApplication()->isAdmin()){
                $where = "WHERE params LIKE '%\"handler\":\"1\"%' AND (client = 1 OR client = 2)";
            }

			$extensions = AceDatabase::loadObjectList("SELECT {$fields} FROM #__acesearch_extensions {$where} ORDER BY ordering ASC, name ASC", 'extension');

            if (($apply_filter == 1) || (JFactory::getApplication()->isSite())) {
                foreach ($extensions as $key => $value) {
                    $params = new JRegistry($value->params);

                    if (!in_array($params->get('access'), $levels)) {
                        unset($extensions[$key]);
                    }
                }
            }
        }
		
		if (!empty($extensions)) {
			if ($this->AcesearchConfig->cache_extensions == 1) {
				$cache->save($extensions, 'extensions');
			}
			
			return $extensions;
		}
		
		return false;
	}
	
	function getFilterExtensions($group_id) {
		if ($this->AcesearchConfig->cache_extensions == 1) {
			$cache = AcesearchFactory::getCache();
			$cached_filt_extension = $cache->load('filter_extensions');
		
			if (!empty($cached_filt_extension)) {
				return $cached_filt_extension;
			}
		}
		
		static $extensions;
		if (!isset($extensions)) {
            $levels	= JFactory::getUser()->getAuthorisedViewLevels();

			$query = "SELECT flt.extension, ext.params, ext.name, flt.params AS flt_params FROM #__acesearch_filters AS flt LEFT JOIN #__acesearch_extensions AS ext ON ext.extension = flt.extension WHERE flt.group_id = '{$group_id}' AND flt.published = 1";
			$extensions = AceDatabase::loadObjectList($query);

            foreach ($extensions as $key => $value) {
                $params = new JRegistry($value->flt_params);

                if (!in_array($params->get('access'), $levels)) {
                    unset($extensions[$key]);
					continue;
                }
				
				$params = new JRegistry($value->params);

                if (!in_array($params->get('access'), $levels)) {
                    unset($extensions[$key]);
                }
            }
		}
		
		if (!empty($extensions)) {
			if ($this->AcesearchConfig->cache_extensions == 1) {
				$cache->save($extensions, 'filter_extensions');
			}
			
			return $extensions;
		}
		
		return false;
	}
		
	function getFilterParams($group_id, $option) {
		static $cache = array();

		if (!isset($cache[$group_id])) {
			$cache[$group_id] = AceDatabase::loadObjectList("SELECT params, extension FROM #__acesearch_filters WHERE group_id = {$group_id} AND published = '1'", "extension");
		}

        if (!isset($cache[$group_id][$option]->params_object)) {
            $cache[$group_id][$option]->params_object = new JRegistry($cache[$group_id][$option]->params);
        }
		
		return $cache[$group_id][$option]->params_object;
	}

	function getExtensionParams($option) {
		static $params = array();

        if (!isset($params[$option])) {
		    $extensions = self::getExtensions();

			$params[$option] = new JRegistry($extensions[$option]->params);
		}

		return $params[$option];
	}
	
	function getRemoteInfo() {
		// Get config object
		if (!isset($this->AcesearchConfig)) {
			$this->AcesearchConfig = AcesearchFactory::getConfig();
		}
		
		static $information;
		
		if ($this->AcesearchConfig->cache_versions == 1) {
			$cache = AcesearchFactory::getCache('86400');
			$information = $cache->load('versions');
		}
		
		if (!is_array($information)) {
			$information = array();
			$information['acesearch'] = '?.?.?';
			
			$components = AcesearchUtility::getRemoteData('http://www.joomace.net/index.php?option=com_aceversions&view=xml&format=xml&catid=6');
			$extensions = AcesearchUtility::getRemoteData('http://www.joomace.net/index.php?option=com_aceversions&view=xml&format=xml&catid=2');
			
			if (strstr($components, '<?xml version="1.0" encoding="UTF-8" ?>')) {
				$manifest = JFactory::getXML($components, false);
				
				if (is_null($manifest)) {
					return $information;
				}
				
				$category = $manifest->category;
				if (!is_a($category, 'JXMLElement') || (count($category->children()) == 0)) {
					return $information;
				}
				
				foreach ($category->children() as $component) {
					$option = $component->getAttribute('option');
					$compability = $component->getAttribute('compability');

					if ($option == 'com_acesearch'
					&& ($compability == '1.6'
					|| $compability == '1.7'
					|| $compability == '1.5_1.6'
					|| $compability == '1.6_1.7'
					|| $compability == '1.5_1.6_1.7'
					|| $compability == '1.6_1.7_2.5'
					|| $compability == '1.5_1.6_1.7_2.5'
					|| $compability == '1.6_1.7_2.5_2.6'
					|| $compability == '1.5_1.6_1.7_2.5_2.6')
					) {
						$information['acesearch'] = trim($component->getAttribute('version'));
						break;
					}
				}
			}
			
			if (strstr($extensions, '<?xml version="1.0" encoding="UTF-8" ?>')) {
				$manifest = JFactory::getXML($extensions, false);

				if (is_null($manifest)) {
					return $information;
				}

				$category = $manifest->category;
				if (!is_a($category, 'JXMLElement') || (count($category->children()) == 0)) {
					return $information;
				}
				
				foreach ($category->children() as $extension) {
					$option = $extension->getAttribute('option');
					$compability = $extension->getAttribute('compability');
					
					if ($compability == 'all'
					|| $compability == '1.6'
					|| $compability == '1.7'
					|| $compability == '1.5_1.6'
					|| $compability == '1.6_1.7'
					|| $compability == '1.5_1.6_1.7'
					|| $compability == '1.6_1.7_2.5'
					|| $compability == '1.5_1.6_1.7_2.5'
					|| $compability == '1.6_1.7_2.5_2.6'
					|| $compability == '1.5_1.6_1.7_2.5_2.6'
					) {
						$ext = new stdClass();
						$ext->version		= trim($extension->getAttribute('version'));
						$ext->link			= trim($extension->getAttribute('download'));
						$ext->description	= trim($extension->getAttribute('description'));
					
						$information[$option] = $ext;
					}
				}
			}
			
			if ($this->AcesearchConfig->cache_versions == 1 && !empty($information)) {
				$cache->save($information, 'versions');
			}
		}
		
		return $information;
	}
}