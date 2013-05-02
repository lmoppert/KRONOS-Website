<?php

/**
* @Copyright (C) 2011 - DM Digital
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemdmSSlideDown extends JPlugin {


	function plgSystemdmSSlideDown( &$subject, $config ){
		parent::__construct( $subject, $config );
	}
	
	function onBeforeRender(){
		
		$app = JFactory::getApplication();
		if( $app->isSite() ){
			self::dmSSlideDown_loadheader();
		}
	}
	
	function onAfterRender(){
		
		$app = JFactory::getApplication();
		if( $app->isSite() ){

			$buffer = JResponse::getBody();
			
			$strPosResult = true;
			$currentPos = 0;
			while ($strPosResult) {
				$strPosResult = strpos($buffer, '{dmSSlideDown', $currentPos);
				if ($strPosResult) {
					
					$currentPos = $strPosResult + 1;
					$endPos = strpos($buffer, '}', $currentPos);
					$myTag = substr($buffer, $currentPos, ($endPos - $currentPos));
					
					//title
					$titleStart = strpos($myTag, 'title=');
					if ($titleStart) {
						$titleEnd = strpos($myTag, '=', $titleStart + 6);
						$myTitle = substr($myTag, $titleStart + 6, ($titleEnd - ($titleStart + 6)));
					} else {
						$myTitle = '';
					}
					//text
					$textStart = $endPos + 1;
					$textEnd = strpos($buffer, '{/dmSSlideDown}', $textStart);
					$myText = substr($buffer, $textStart, ($textEnd - $textStart));
					$endPos = $textEnd + 14;
					
					//replace
					if(  (JRequest::getVar('option', '') == 'com_content' && JRequest::getVar('view', '') == 'article') || 
						(JRequest::getVar('option', '') == 'com_k2' && JRequest::getVar('view', '') == 'item') ){
						$contentr = self::dmSSlideDown_loadHTML( $myTitle, rand(), $myText );
						
					} else {
						switch( $this->params->get('dmSSlideDown_listview') ){
							case 22:		
									$contentr = self::dmSSlideDown_loadHTML( $myTitle, rand(), $myText );
									break;
							case 99:	$contentr = '';
									break;
							case -99:	$contentr = $myText;
									break;
						}
					}
					
					$buffer = substr_replace($buffer, $contentr, $strPosResult, ($endPos - $strPosResult +1) );
					JResponse::setBody($buffer);
					
				}
			}
		}
	}
	
	function dmSSlideDown_loadheader(){
		$document = JFactory::getDocument();
		if( $this->params->get('dmSSlideDown_nojquery') ){
			$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js');
			$document->addScript('plugins/content/dmSSlideDown/dmSSlideDown/jquery_noconflict.js');
		}
	}
	
	function dmSSlideDown_loadHTML( $title, $id, $text ){
		
		if( !empty($text) ){
			if( empty($title) ) $title = $this->params->get('dmSSlideDown_button');
			
			$output = '<div class="dmSSlideDown_cont">';
			$output .= '	<a href="#" onclick="dmSSlideDown_Toggle(\'' . $id . '\'); return false;" id="dmSSlideDown_' . $id . '_a" class="dmSSlideDown_a">' . $title . '</a>';
			$output .= '	<div id="dmSSlideDown_' . $id . '_spoilcont" class="dmSSlideDown_spoilcont" style="display:none;">' . $text . '</div>';
			$output .= '</div>';
			
			require_once('dmSSlideDown/javascript.php');
			
			return $output;
		}
	}
}

?>
