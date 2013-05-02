<?php
/**
* @version		1.7.0
* @package		AceSearch Library
* @subpackage	Utility
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Imports
jimport('joomla.filesystem.file');

// Utility class
class AcesearchUtility {
	
	static $props = array();
	
	function __construct() {
		// Get config object
		$this->AcesearchConfig = AcesearchFactory::getConfig();
	}
	
	function import($path) {
		require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_acesearch' . DS . str_replace('.', '/', $path).'.php');
	}
	
    function is16() {
		static $status;
		
		if (!isset($status)) {
			if (version_compare(JVERSION,'1.6.0', 'ge')) {
				$status = true;
			} else {
				$status = false;
			}
		}
		
		return $status;
	}
	
	function render($path) {
		ob_start();
		require_once($path);
		$contents = ob_get_contents();
		ob_end_clean();
		
		return $contents;
	}
    
    function get($name, $default = null) {
        if (!is_array(self::$props) || !isset(self::$props[$name])) {
            return $default;
        }
        
        return self::$props[$name];
    }
    
    function set($name, $value) {
        if (!is_array(self::$props)) {
            self::$props = array();
        }
        
        $previous = self::get($name);
		
        self::$props[$name] = $value;
        
        return $previous;
    }
	
	function getConfigState($params, $cfg_name, $prm = "") {
		if(JFactory::getApplication()->isAdmin()) {
			$prm_name = 'admin_'.$cfg_name;
			return isset($this->AcesearchConfig->$prm_name) ? $this->AcesearchConfig->$prm_name : '1';
		}
		
		if (!is_object($params)) {
			return false;
		}
		
		$prm_name = $cfg_name;
		if ($prm != "") {
			$prm_name = $prm;
		}
		
		$param = $params->get($prm_name, 'g');
		
		if (($param == '0') || (isset($this->AcesearchConfig->$cfg_name) && $this->AcesearchConfig->$cfg_name == '0' ) ) {
			return false;
		}
		
		return true;
    }
	
	function &getMenu() {
		jimport('joomla.application.menu');
		$options = array();
		
		$menu =& JMenu::getInstance('site', $options);
		
		if (JError::isError($menu)) {
			$null = null;
			return $null;
		}
		
		return $menu;
	}
	
	function getItemid($filter = 'gelmedi', $is_advanced = false) {
		require_once(JPATH_ADMINISTRATOR . '/components/com_acesearch/library/extension.php');

        if ($filter == 'gelmedi') {
            $filter = JRequest::getInt('filter', '');
        }
		
		if (empty($filter)) {
            $filter = '';
        }

		$Itemid = '';
		$vars = $params = array();
		
		$vars['option'] = 'com_acesearch';
		
		if ($is_advanced) {
			$vars['view'] = 'advancedsearch';
			$params['filter'] = $filter;
			$item = AcesearchExtension::findItemid($vars, $params);
			
			if (!$item) {
				$vars['view'] = 'search';
				$item = AcesearchExtension::findItemid($vars, $params);
			}
		}
		else {
			$vars['view'] = 'search';
			$params['filter'] = $filter;
			
			$item = AcesearchExtension::findItemid($vars);
			
			if (!$item) {
				$vars['view'] = 'advancedsearch';
				$item = AcesearchExtension::findItemid($vars, $params);
			}
		}
		
		if (!empty($item)) {
			$Itemid = '&Itemid='.$item->id;
		}
		
		return $Itemid;
	}

	function getComponents() {
		static $components;

		if (!isset($components)) {
            $components = array();

			$filter = self::getSkippedComponents();
			$rows = AceDatabase::loadResultArray("SELECT `element` FROM `#__extensions` WHERE `type` = 'component' AND `element` NOT IN ({$filter}) ORDER BY `element`");

            $lang =& JFactory::getLanguage();

			foreach($rows as $row) {
                $lang->load($row.'.sys', JPATH_ADMINISTRATOR);
				$components[] = JHTML::_('select.option', $row, JText::_($row));
			}
		}

		return $components;
	}

    function getSkippedComponents() {
        return "'com_acesearch', 'com_search', 'com_admin', 'com_categories', 'com_checkin', 'com_login', 'com_redirect', 'com_user', 'com_contact', 'com_dump', 'com_wrapper', 'com_mailto', 'com_joomfish', 'com_config', 'com_media', 'com_installer', 'com_templates',  'com_cpanel', 'com_cache', 'com_messages',  'com_massmail', 'com_languages'";
    }

	function getAccessLevels() {
		return AceDatabase::loadObjectList('SELECT id, title FROM #__viewlevels');
	}

	function getExtensionFieldsFromXml($option) {
        $html = '';

		$manifest = JFactory::getXML(JPATH_ACESEARCH_ADMIN.'/extensions/'.$option.'.xml');

		if (is_null($manifest)) {
			return $html;
		}

        $fields_xml = $manifest->fields;
		if (!is_a($fields_xml, 'JXMLElement') || (count($fields_xml->children()) == 0)) {
			return $html;
		}

		return $fields_xml->children();
	}
	
	function getExtensionFromRequest() {
		static $extension;
		
		if (!isset($extension)) {
			$cid = JRequest::getVar('cid', array(0), 'method', 'array');
			$extension = AceDatabase::loadResult("SELECT extension FROM #__acesearch_extensions WHERE id = ".$cid[0]);
		}
		
		return $extension;
	}

    function smartSubstr($text, $length = 200, $searchword) {
		$textlen = JString::strlen($text);
		$lsearchword = JString::strtolower($searchword);
		$wordfound = false;
		$pos = 0;

        /*if (!JString::strpos($text, $lsearchword)) {
            return JString::substr($text, 0, $length) . '&nbsp;...';
        }*/

		while ($wordfound === false && $pos < $textlen) {
			if (($wordpos = @JString::strpos($text, ' ', $pos + $length)) !== false) {
				$chunk_size = $wordpos - $pos;
			}
            else {
				$chunk_size = $length;
			}

			$chunk = JString::substr($text, $pos, $chunk_size);
			$wordfound = JString::strpos(JString::strtolower($chunk), $lsearchword);
			if ($wordfound === false) {
				$pos += $chunk_size + 1;
			}
		}

		if ($wordfound !== false) {
			return (($pos > 0) ? '...&nbsp;' : '') . $chunk . '&nbsp;...';
		}
        else {
			if (($wordpos = @JString::strpos($text, ' ', $length)) !== false) {
				return JString::substr($text, 0, $wordpos) . '&nbsp;...';
			}
            else {
				return JString::substr($text, 0, $length);
			}
		}
	}

    function cleanText($text) {
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		$text = preg_replace('#<script[^>]*>.*?</script>#si', ' ', $text);
		$text = preg_replace('#<style[^>]*>.*?</style>#si', ' ', $text);
		$text = preg_replace('#<!.*?(--|]])>#si', ' ', $text);
		$text = preg_replace('#<[^>]*>#i', ' ', $text);
		$text = preg_replace('/{.+?}/', '', $text);
		$text = preg_replace("'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text);

		$text = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', ' ', $text);

		$text = preg_replace('/\s\s+/', ' ', $text);
		$text = preg_replace('/\n\n+/s', ' ', $text);
		$text = preg_replace('/\s/u', ' ', $text);

		$text = strip_tags($text);
        
        return $text;
    }
	
	function getHandlerList($component) {
		static $handlers = array();
		
		if (!isset($handlers[$component])) {
			$extension_file = JPATH_ACESEARCH_ADMIN.'/extensions/'.$component.'.php';
			if (file_exists($extension_file)) {
				$handlers[$component][] = JHTML::_('select.option', 1, JText::_('COM_ACESEARCH_EXTENSIONS_VIEW_SELECT_EXTENSION'));
			}
			
			$plugin = self::findSearchPlugin($component);
			if ($plugin) {
				$handlers[$component][] = JHTML::_('select.option', 2, JText::_('COM_ACESEARCH_EXTENSIONS_VIEW_SELECT_PLUGIN'));
			}
			
			$handlers[$component][] = JHTML::_('select.option', 0, JText::_('COM_ACESEARCH_EXTENSIONS_VIEW_SELECT_DISABLE'));
		}
		
		return $handlers[$component];
	}
	
	function findSearchPlugin($component) {
		jimport('joomla.plugin.helper');
		
		$plugin = substr($component, 4);
		
		$found = JPluginHelper::isEnabled('search', $plugin);
		
		if (!$found) {
			$plugin = $plugin.'search';
			$found = JPluginHelper::isEnabled('search', $plugin);
		}
		
		if (!$found) {
			$plugin = self::_fixSearchPlugin($component);
			$found = JPluginHelper::isEnabled('search', $plugin);
		}
		
		if (!$found) {
			return false;
		}
		
		return $plugin;
	}
	
	function _fixSearchPlugin($component) {
		$com = '';
		
		switch($component) {
			case 'com_hikashop':
				$com = 'hikashop_products';
				break;
			case 'com_docindexer':
				$com = 'doc_indexer';
				break;
			default:
				$com = substr($component, 4);
				break;
		}
		
		return $com;
	}

	function getOptionFromRealURL($url) {
		$url = str_replace('&amp;', '&', $url);
		$url = str_replace('index.php?', '', $url);		
		parse_str($url, $vars);
		
		if (isset($vars['option'])) {
			return $vars['option'];
		}
		else {
			return '';
		}
	}
	
    function replaceLoop($search, $replace, $text) {
        $count = 0;
		
		if (!is_string($text)) {
			return $text;
		}
		
		while ((strpos($text, $search) !== false) && ($count < 10)) {
            $text = str_replace($search, $replace, $text);
			$count++;
        }

        return $text;
    }
	
	function storeConfig($AcesearchConfig) {
		$reg = new JRegistry($AcesearchConfig);
		$config = $reg->toString();
		
		$db =& JFactory::getDBO();
		$db->setQuery('UPDATE #__extensions SET params = '.$db->Quote($config).' WHERE element = "com_acesearch" AND type = "component"');
		$db->query();
	}
	
	function getParam($text, $param) {
		$params = new JRegistry($text);
		return $params->get($param);
	}
	
	function storeParams($table, $id, $db_field, $new_params) {
		$row = AcesearchFactory::getTable($table);
		if (!$row->load($id)) {
			return false;
		}
		
		$params = new JRegistry($row->$db_field);
		
		foreach ($new_params as $name => $value) {
			$params->set($name, $value);
		}
		
		$row->$db_field = $params->toString();
		
		if (!$row->check()) {
			return false;
		}
		
		if (!$row->store()) {
			return false;
		}
	}
	
	function setData($table, $id, $db_field, $new_field) {
		$row = AcesearchFactory::getTable($table);
		if (!$row->load($id)) {
			return false;
		}
		$row->$db_field = $new_field;	

		if (!$row->check()) {
			return false;
		}
		
		if (!$row->store()) {
			return false;
		}
	}
	
	function checkPlugin() {
		if ((ACESEARCH_PACK == 'plus' || ACESEARCH_PACK == 'pro') && strlen(AcesearchFactory::getConfig()->download_id) != 32) {
			return false;
		}
		
		return true;
	}
	
	function getPlugin() {
		if (strlen(AcesearchFactory::getConfig()->download_id) != 32) {
			$bs = 'ba'.'s'.'e'.'6'.'4'.'_'.'de'.'co'.'de';
			echo $bs('PGRpdiBzdHlsZT0idGV4dC1hbGlnbjpjZW50ZXI7IGZvbnQtc2l6ZTo5cHg7IHZpc2liaWxpdHk6
					dmlzaWJsZTsiIHRpdGxlPSJKb29tbGEgU2VhcmNoIGJ5IEFjZVNlYXJjaCI+PGEgaHJlZj0iaHR0
					cDovL3d3dy5qb29tYWNlLm5ldC9qb29tbGEtZXh0ZW5zaW9ucy9hY2VzZWFyY2giIHRhcmdldD0i
					X2JsYW5rIj5Kb29tbGEgU2VhcmNoIGJ5IEFjZVNlYXJjaDwvYT48L2Rpdj4=');
			
		}
	}
		
	function getRemoteData($url) {
		$user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3 (.NET CLR 3.5.30729)";
		$data = false;

		// cURL
		if (extension_loaded('curl')) {
			// Init cURL
			$ch = @curl_init();
			
			// Set options
			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_HEADER, 0);
			@curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			@curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
			@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			
			// Set timeout
			@curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			
			// Grab data
			$data = @curl_exec($ch);
			
			// Clean up
			@curl_close($ch);
			
			// Return data
			return $data;
		}

		// fsockopen
		if (function_exists('fsockopen')) {
			$errno = 0;
			$errstr = '';
			
			$url_info = parse_url($url);
			if($url_info['host'] == 'localhost')  {
				$url_info['host'] = '127.0.0.1';
			}

			// Open socket connection
			$fsock = @fsockopen($url_info['scheme'].'://'.$url_info['host'], 80, $errno, $errstr, 5);
		
			if ($fsock) {				
				@fputs($fsock, 'GET '.$url_info['path'].(!empty($url_info['query']) ? '?'.$url_info['query'] : '').' HTTP/1.1'."\r\n");
				@fputs($fsock, 'HOST: '.$url_info['host']."\r\n");
				@fputs($fsock, "User-Agent: ".$user_agent."\n");
				@fputs($fsock, 'Connection: close'."\r\n\r\n");
		
				// Set timeout
				@stream_set_blocking($fsock, 1);
				@stream_set_timeout($fsock, 5);
				
				$data = '';
				$passed_header = false;
				while (!@feof($fsock)) {
					if ($passed_header) {
						$data .= @fread($fsock, 1024);
					} else {
						if (@fgets($fsock, 1024) == "\r\n") {
							$passed_header = true;
						}
					}
				}
				
				// Clean up
				@fclose($fsock);
				
				// Return data
				return $data;
			}
		}

		// fopen
		if (function_exists('fopen') && ini_get('allow_url_fopen')) {
			// Set timeout
			if (ini_get('default_socket_timeout') < 5) {
				ini_set('default_socket_timeout', 5);
			}
			
			@stream_set_blocking($handle, 1);
			@stream_set_timeout($handle, 5);
			@ini_set('user_agent',$user_agent);
			
			$url = str_replace('://localhost', '://127.0.0.1', $url);
			
			$handle = @fopen($url, 'r');
			
			if ($handle) {
				$data = '';
				while (!feof($handle)) {
					$data .= @fread($handle, 8192);
				}
				
				// Clean up
				@fclose($handle);
			
				// Return data
				return $data;
			}
		}
		
		// file_get_contents
		if (function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
			$url = str_replace('://localhost', '://127.0.0.1', $url);
			@ini_set('user_agent',$user_agent);
			$data = @file_get_contents($url);
			
			// Return data
			return $data;
		}
		
		return $data;
	}

    function postXmlRequest($url, $path, $xml) {
        $response = '';
        
        if (function_exists('curl_init')) {
            // setup headers to be sent
            $header  = "POST {$path} HTTP/1.0 \r\n";
            $header .= "MIME-Version: 1.0 \r\n";
            $header .= "Content-type: text/xml; charset=utf-8 \r\n";
            $header .= "Content-length: ".strlen($xml)." \r\n";
            $header .= "Request-number: 1 \r\n";
            $header .= "Document-type: Request \r\n";
            $header .= "Connection: close \r\n\r\n";
            $header .= $xml;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $header);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);

            $response = curl_exec($ch);

            curl_close($ch);

            return $response;
        }
        elseif (function_exists('stream_context_create') && function_exists('file_get_contents')) {
            $options = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $data
                )
            );

            $context  = stream_context_create($options);

            $response = file_get_contents($url.$path, false, $context);
        }
        elseif (function_exists('fsockopen')) {
            $url = str_replace('https', 'http', $url);
            
            // parse the given URL
            $_url = parse_url($url.$path);

            if ($_url['scheme'] != 'http') {
                return $response;
            }

            // extract host and path:
            $host = $_url['host'];
            $path = $_url['path'];

            // open a socket connection on port 80 - timeout: 30 sec
            $fp = fsockopen($host, 80, $errno, $errstr, 30);

            if ($fp){

                // send the request headers:
                fputs($fp, "POST $path HTTP/1.1\r\n");
                fputs($fp, "Host: $host\r\n");

                if ($referer != '')
                    fputs($fp, "Referer: $referer\r\n");

                fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
                fputs($fp, "Content-length: ". strlen($data) ."\r\n");
                fputs($fp, "Connection: close\r\n\r\n");
                fputs($fp, $data);

                $result = '';
                while(!feof($fp)) {
                    // receive the results of the request
                    $result .= fgets($fp, 128);
                }
            }
            else {
                return '';
            }

            // close the socket connection:
            fclose($fp);

            // split the result header from the content
            $result = explode("\r\n\r\n", $result, 2);

            $content = isset($result[1]) ? $result[1] : '';

            // return as structured array:
            return $content;
        }

        return $response;
    }
	
	// Get text from XML
	function getXmlText($file, $variable) {
		static $xml = array();
		
		if (!isset($xml[$file])) {
			if (JFile::exists($file)) {
				$xml[$file] = JFactory::getXML($file);
			}
			else {
				$xml[$file] = new stdClass();
				$xml[$file]->$variable = null;
			}
		}
		
		return $xml[$file]->$variable;
    }
}