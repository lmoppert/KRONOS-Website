<?php

/**
* Gavick GK Stock - helper class
* @package Joomla!
* @Copyright (C) 2011 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// no direct access
defined('_JEXEC') or die('Restricted access');
// Main class
class GKSHelper
{
	var $config;
	var $content;
	var $error;
	var $parsedData;
	
	
	/**
	 * 
	 *	INITIALIZATION
	 * 
	 **/
	
	function init() {		
		// importing JFile class 
		jimport('joomla.filesystem.file');
		// configuration array
		$this->config = array(
			'module_unique_id' => '',				            
            'showChart' => 1,
			'stocks' => '',
            'amount' => 4,
			'links' => 1,
			'tooltips' => 1,
			'useCSS' => 1,
			'useScript' => 2,
			'useCache' => 1,
			'cacheTime' => 5
		);	
		// error text
		$this->error = '';
		// parsed data
		$this->parsedData = array();
	}
	
	/**
	 * 
	 *	VARIABLES VALIDATION
	 * 
	 **/
	
	function validateVariables($params, &$module) {
        $this->module_id = ($params->get('auto_id',0) == 1) ? 'gkStock_'.$module->id : $params->get('module_unique_id',0); 
	    $this->config['auto_id'] = $this->module_id;
        $this->config['showChart'] = $params->get('showChart',1);
		$this->config['stocks'] = $params->get('stocks','');
        $this->config['amount'] = $params->get('amount',4);
		$this->config['links'] = $params->get('links',1);
		$this->config['tooltips'] = $params->get('tooltips',1);
		$this->config['useScript'] = $params->get('useScript',2);		
		$this->config['useCSS'] = $params->get('useCSS',1);
		$this->config['useCache'] = $params->get('useCache',1);
		$this->config['cacheTime'] = $params->get('cacheTime',5);
        $this->config['tooltip_position'] = $params->get('tooltip_position', 'left');
        $this->config['tooltip_layout'] = $params->get('tooltip_layout','');
	}
	
	/**
	 * 
	 *	GETTING DATA
	 * 
	 **/
		
	function getData() {
		clearstatcache();
		
		$stocks = preg_replace("/\n$/", '', $this->config['stocks']);
		$stocks = explode(";",$stocks);
		$query = '';
		for($i = 0;$i < count($stocks);$i++) {
			$temp = explode(',',$stocks[$i]);
			$query .= trim($temp[0]).',';
		}
		$query = substr($query, 0, strlen($query)-2);
		$query = str_replace(" ","",$query);

		if($this->config['useCache'] == 1) {	
			if(filesize(realpath('cache/mod_stock_gk4.data')) == 0 || ((filemtime(realpath('cache/mod_stock_gk4.data')) + $this->config['cacheTime'] * 60) < time())) {
				if(function_exists('curl_init')) {
					// initializing connection
					$curl = curl_init();
					// saves us before putting directly results of request
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
					// url to get
					curl_setopt($curl, CURLOPT_URL, 'http://www.google.com/finance/info?q='.$query);
                                        // proxy settings
                                        curl_setopt($curl, CURLOPT_PROXY, 'localhost:3128');    
					// timeout in seconds
					curl_setopt($curl, CURLOPT_TIMEOUT, 20);
					// useragent
					curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					// reading content
					$this->content = curl_exec($curl);
					// closing connection
					curl_close($curl);
				} else if( file_get_contents(__FILE__) && ini_get('allow_url_fopen') && !function_exists('curl_init')) {
                    $this->content=file_get_contents('http://www.google.com/finance/info?q='.$query);
                } else {
					$this->error = 'cURL extension and file_get_content method is not available on your server';
				}
				// if error doesn't exist
				if($this->error == '') {
					// saving cache
					JFile::write(realpath('modules/mod_stock_gk4/cache/mod_stock_gk4.data'), $this->content);
				} else {
                    $this->content = JFile::read(realpath('modules/mod_stock_gk4/cache/mod_stock_gk4.backup.xml'));
                }
			} else {
				$this->content = JFile::read(realpath('modules/mod_stock_gk4/cache/mod_stock_gk4.data'));
			}
		} else {
			if(function_exists('curl_init')) {
				// initializing connection
				$curl = curl_init();
				// saves us before putting directly results of request
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
				// url to get
				curl_setopt($curl, CURLOPT_URL, 'http://www.google.com/finance/info?q='.$query);
                                // proxy settings
                                curl_setopt($curl, CURLOPT_PROXY, 'localhost:3128');    
				// timeout in seconds
				curl_setopt($curl, CURLOPT_TIMEOUT, 20);
				// useragent
				curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				// reading content
				$this->content = curl_exec($curl);
				// closing connection
				curl_close($curl);
			} else if( file_get_contents(__FILE__) && ini_get('allow_url_fopen') && !function_exists('curl_init')) {
                $this->content=file_get_contents('http://www.google.com/finance/info?q='.$query);
            } else {
				$this->error = 'cURL extension and file_get_content method is not available on your server';
			}
		}
	}

	/**
	 * 
	 *	PARSING DATA
	 * 
	 **/
	 
	function parseData() {
	    // if any error exists
		if($this->error === '') {
            //prepare backup
            JFile::write(realpath('modules/mod_stock_gk4/cache/mod_stock_gk4.backup.xml'), $this->content);
			$this->parsedData = array();
			$stocks = preg_replace("/\n$/", '', $this->config['stocks']);
			$stocks = explode(";",$stocks);
			$j = 0;
			for($i = 0;$i < count($stocks);$i++) {
				if(strlen($stocks[$i])) {
					// getting names
					$temp = explode(',',$stocks[$i]);
					$name = explode(':',$temp[0]);
					$market = trim($name[0]);
					$name = trim($name[1]);
					$full_name = $temp[1];
					// getting rest of data
					if(strlen($name) > 0 && strpos($this->content, $name, 0) !== false) {
						// parsing
						$search_start = strpos($this->content, $name);
						$l_cur_start = strpos($this->content,'"l_cur"',$search_start) + 7;
						$l_cur_start1 = strpos($this->content,'"',$l_cur_start)+1;
						$l_cur = substr($this->content,$l_cur_start1, strpos($this->content,'"',$l_cur_start1) - $l_cur_start1);
						$c_start = strpos($this->content,'"c"',$search_start) + 3;
						$c_start1 = strpos($this->content,'"',$c_start)+1;
						$c = substr($this->content,$c_start1, strpos($this->content,'"',$c_start1) - $c_start1);
						$cp_start = strpos($this->content,'"cp"',$search_start) + 4;
						$cp_start1 = strpos($this->content,'"',$cp_start)+1;
						$cp = substr($this->content,$cp_start1, strpos($this->content,'"',$cp_start1) - $cp_start1);
						$ltt_start = strpos($this->content,'"ltt"',$search_start) + 5;
						$ltt_start1 = strpos($this->content,'"',$ltt_start)+1;
						$ltt = substr($this->content,$ltt_start1, strpos($this->content,'"',$ltt_start1) - $ltt_start1);
						// saving results in array
						$this->parsedData[$j] = array($market,$name,$full_name,$l_cur,$c,$cp,$ltt);
						// increment counter
						$j++;
					}
				}
			}
			
			if(count($this->parsedData) == 0) {
				$this->error = 'An error occured during parsing XML data. Please try again.';	
			}
		} else {
            // prepare a backup
            JFile::write(realpath('modules/mod_stock_gk4/cache/mod_stock_gk4.backup.xml'), $this->content);
        }
	}
	
	/**
	 * 
	 *	RENDERING LAYOUT
	 * 
	 **/
	
	function renderLayout() {	
		// if any error exists
		if($this->error === '') {
			/**
				GENERATING FINAL XHTML CODE START
			**/

			// create instances of basic Joomla! classes
			$document = JFactory::getDocument();
			$uri = JURI::getInstance();
			// add stylesheets to document header
	        if($this->config["useCSS"] == 1){
				$document->addStyleSheet( $uri->root().'modules/mod_stock_gk4/style/style.css', 'text/css' );
	        }
	        // init $headData variable
	        $headData = $document->getHeadData();
	        // generate keys of script section
	        $headData_keys = array_keys($headData["scripts"]);
	        // set variable for false
	   	    $engine_founded = false;
	   	    // searching phrase mootools in scripts paths
	       	if(array_search($uri->root().'modules/mod_stock_gk4/scripts/engine.js', $headData_keys) > 0) {
	        	// if founded set variable to true
	        	$engine_founded = true;
			}
			// if engine file doesn't exists in document head section
			if(!$engine_founded || $this->config['useScript'] == 1) {
	        	// add new script tag connected with mootools from module
				$document->addScript($uri->root().'modules/mod_stock_gk4/scripts/engine.js');
			}	
			
			require(JModuleHelper::getLayoutPath('mod_stock_gk4', 'default'));
		} else { // else - output error information
			echo $this->error;
		}
	}
}

?>
