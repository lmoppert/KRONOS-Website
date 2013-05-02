<?php  
/*------------------------------------------------------------------------
# author    KRONOS TITAN GmbH
# copyright Copyright (C) 2013 KRONOS TITAN GmbH. All rights reserved.
# @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website   http://kronostio2.com
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die; 

// variables
$app = JFactory::getApplication();
$doc = JFactory::getDocument(); 
$params = &$app->getParams();
$pageclass = $params->get('pageclass_sfx');
$tpath = $this->baseurl.'/templates/'.$this->template;

$this->setGenerator(null);

// load sheets and scripts
$doc->addStyleSheet($tpath.'/css/template.css.php?v=1.0.0'); 
$doc->addScript($tpath.'/js/modernizr.js'); // <- this script must be in the head

// unset scripts, put them into /js/template.js.php to minify http requests
//unset($doc->_scripts[$this->baseurl.'/media/system/js/mootools-core.js']);
//unset($doc->_scripts[$this->baseurl.'/media/system/js/core.js']);
//unset($doc->_scripts[$this->baseurl.'/media/system/js/caption.js']);
//unset all js files from the system
foreach ($document->_scripts as $src => $attr)
{
   $find   = '/media\/system\/js\//';
   if (preg_match($find, $src)) unset($document->_scripts[$src]);
};
$this->_script = preg_replace('%window\.addEvent\(\'load\',\s*function\(\)\s*{\s*new\s*JCaption\(\'img.caption\'\);\s*}\);\s*%', '', $this->_script);
if (empty($this->_script['text/javascript'])) unset($this->_script['text/javascript']);
?>

<!doctype html>

<head>
  <jdoc:include type="head" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="<?php echo $tpath; ?>/favicon.ico" />
  <!--[if lte IE 8]>
    <style> 
      {behavior:url(<?php echo $tpath; ?>/js/PIE.htc);}
    </style>
  <![endif]-->
  <!--[if IE 8]>
    <link href="<?php echo $tpath; ?>/css/ie8.css" rel="stylesheet" type="text/css">
  <![endif]-->
  <!--[if IE 7]>
    <link href="<?php echo $tpath; ?>/css/ie7.css" rel="stylesheet" type="text/css">
  <![endif]-->
  <!--<script type="text/javascript" src="<?php // echo $tpath.'/js/template.js.php'; ?>"></script>-->
  <script>
    function showHideMenu (idmenu) {
    
    
    if (document.getElementById('menuid' + idmenu).style.display == 'block') {
      document.getElementById('menuid' + idmenu).style.display = 'none';
      document.getElementById('clickid' + idmenu + '-hover').id = 'clickid'+ idmenu;
    } else {
        if (document.getElementById('menuid188').id == 'menuid' + idmenu) {
          document.getElementById('menuid188').style.display = 'block';
          document.getElementById('clickid188').id = 'clickid188-hover';
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
        
        else if (document.getElementById('menuid189').id == 'menuid' + idmenu) {
          document.getElementById('menuid189').style.display = 'block';
          document.getElementById('clickid189').id = 'clickid189-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
      
        else if (document.getElementById('menuid190').id == 'menuid' + idmenu) {
          document.getElementById('menuid190').style.display = 'block';
          document.getElementById('clickid190').id = 'clickid190-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
          
        else if (document.getElementById('menuid191').id == 'menuid' + idmenu) {
          document.getElementById('menuid191').style.display = 'block';
          document.getElementById('clickid191').id = 'clickid191-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
          
        else if (document.getElementById('menuid192').id == 'menuid' + idmenu) {
          document.getElementById('menuid192').style.display = 'block';
          document.getElementById('clickid192').id = 'clickid192-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
          
        else if (document.getElementById('menuid193').id == 'menuid' + idmenu) {
          document.getElementById('menuid193').style.display = 'block';
          document.getElementById('clickid193').id = 'clickid193-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
          
        else if (document.getElementById('menuid194').id == 'menuid' + idmenu) {
          document.getElementById('menuid194').style.display = 'block';
          document.getElementById('clickid194').id = 'clickid194-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
          
        else if (document.getElementById('menuid195').id == 'menuid' + idmenu) {
          document.getElementById('menuid195').style.display = 'block';
          document.getElementById('clickid195').id = 'clickid195-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
        
        else if (document.getElementById('menuid196').id == 'menuid' + idmenu) {
          document.getElementById('menuid196').style.display = 'block';
          document.getElementById('clickid196').id = 'clickid196-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
          
          else if (document.getElementById('menuid197').id == 'menuid' + idmenu) {
          document.getElementById('menuid197').style.display = 'block';
          document.getElementById('clickid197').id = 'clickid197-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
        
        else if (document.getElementById('menuid581').id == 'menuid' + idmenu) {
          document.getElementById('menuid581').style.display = 'block';
          document.getElementById('clickid581').id = 'clickid581-hover';
          
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}}
        
        else {
          document.getElementById('menuid188').style.display = 'none';
          if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
          
          document.getElementById('menuid189').style.display = 'none';
          if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
          
          document.getElementById('menuid190').style.display = 'none';
          if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
          
          document.getElementById('menuid191').style.display = 'none';
          if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
          
          document.getElementById('menuid192').style.display = 'none';
          if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
          
          document.getElementById('menuid193').style.display = 'none';
          if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
          
          document.getElementById('menuid194').style.display = 'none';
          if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
          
          document.getElementById('menuid195').style.display = 'none';
          if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
          
          document.getElementById('menuid196').style.display = 'none';
          if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
          
          document.getElementById('menuid197').style.display = 'none';
          if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
          
          document.getElementById('menuid581').style.display = 'none';
          if (document.getElementById('clickid581-hover') != null) {document.getElementById('clickid581-hover').id = 'clickid581';}}
    }
  }
  
  function closeMenu() {
      document.getElementById('menuid188').style.display = 'none';
    if (document.getElementById('clickid188-hover') != null) {document.getElementById('clickid188-hover').id = 'clickid188';}
    
    document.getElementById('menuid189').style.display = 'none';
    if (document.getElementById('clickid189-hover') != null) {document.getElementById('clickid189-hover').id = 'clickid189';}
    
    document.getElementById('menuid190').style.display = 'none';
    if (document.getElementById('clickid190-hover') != null) {document.getElementById('clickid190-hover').id = 'clickid190';}
    
    document.getElementById('menuid191').style.display = 'none';
    if (document.getElementById('clickid191-hover') != null) {document.getElementById('clickid191-hover').id = 'clickid191';}
    
    document.getElementById('menuid192').style.display = 'none';
    if (document.getElementById('clickid192-hover') != null) {document.getElementById('clickid192-hover').id = 'clickid192';}
    
    document.getElementById('menuid193').style.display = 'none';
    if (document.getElementById('clickid193-hover') != null) {document.getElementById('clickid193-hover').id = 'clickid193';}
    
    document.getElementById('menuid194').style.display = 'none';
    if (document.getElementById('clickid194-hover') != null) {document.getElementById('clickid194-hover').id = 'clickid194';}
    
    document.getElementById('menuid195').style.display = 'none';
    if (document.getElementById('clickid195-hover') != null) {document.getElementById('clickid195-hover').id = 'clickid195';}
    
    document.getElementById('menuid196').style.display = 'none';
    if (document.getElementById('clickid196-hover') != null) {document.getElementById('clickid196-hover').id = 'clickid196';}
    
    document.getElementById('menuid197').style.display = 'none';
    if (document.getElementById('clickid197-hover') != null) {document.getElementById('clickid197-hover').id = 'clickid197';}
    
    document.getElementById('menuid581').style.display = 'none';
    if (document.getElementById('clickid1581-hover') != null) {document.getElementById('clickid1581-hover').id = 'clickid1581';}
  }
  </script>
</head>
  
<body class="<?php echo $pageclass; ?>" onLoad="displayField();">
<!-- BLUE HEADER BAR -->
    <div id="header">
      <div id="overall">
          <!-- LOGO -->
            <div id="logo"><a href="<?php echo $this->baseurl ?>"><img src="<?php echo $tpath; ?>/images/kronoslogo.png" /></a></div>
          <!-- END OF LOGO -->
            
            <?php if ($this->countModules('slogan')) : ?>
            <!-- SLOGAN -->
                <div id="slogan">
                    <jdoc:include type="modules" name="slogan" style="xhtml" />
                </div>
            <!-- END OF SLOGAN -->
            <?php endif ?>
            
            <?php if (($this->countModules('topmenu')) || ($this->countModules('search')) || ($this->countModules('language'))) : ?>
            <!-- TOP MENU, SEARCH, and LANGUAGE SELECTION AREA -->
                <div id="topmenu">
                    <?php if ($this->countModules('topmenu')) : ?><jdoc:include type="modules" name="topmenu" style="xhtml" /><?php endif ?>
                </div>
                <div id="searchlang">
                    <?php if ($this->countModules('language')) : ?><span id="language"><jdoc:include type="modules" name="language" style="xhtml" /></span><?php endif ?>
          <?php if ($this->countModules('search')) : ?><span id="search"><jdoc:include type="modules" name="search" style="xhtml" /></span><?php endif ?>
                    
                </div>
            <!-- END OF TOP MENU, SEARCH, and LANGUAGE SELECTION AREA -->
            <?php endif ?>
            
    </div>
    </div>
<!-- END OF BLUE HEADER BAR -->    

  <?php if (($this->countModules('featuredmenu')) && ($this->countModules('featuredslideshow'))) : ?>
    <!-- FEATURED SLIDESHOW MODULE BAR -->
        <div id="featured">
            <div id="overall">
            
              <?php if ($this->countModules('featuredmenu')) : ?>
                <!-- FEATURED MENU -->
                    <div id="featuredmenu">
                        <jdoc:include type="modules" name="featuredmenu" style="xhtml" />
                    </div>
                <!-- END OF FEATURED MENU -->
                <?php endif ?>
                
                <?php if ($this->countModules('featuredslideshow')) : ?>
                <!-- FEATURED MENU -->
                    <div id="featuredslideshow">
                        <jdoc:include type="modules" name="featuredslideshow" style="xhtml" />
                    </div>
                <!-- END OF FEATURED MENU -->
                <?php endif ?>
                
            </div>
        </div>
  <!-- END OF FEATURED SLIDESHOW MODULE BAR -->
  <?php endif ?>
    
    <div id="main">   
    <div id="overall">
      
      <?php if (($this->countModules('userleft')) && ($this->countModules('userright'))) : ?>
                <div id="fpcontent">
                    <!-- USER LEFT -->
                        <div id="userleft">
                            <jdoc:include type="modules" name="userleft" style="xhtml" />
                        </div>
                    <!-- END OF USER LEFT -->
        
                    <!-- USER RIGHT -->
                        <div id="userright">
                          <jdoc:include type="modules" name="userright" style="xhtml" />
                        </div>   
                    <!-- END OF USER RIGHT -->
                </div>
            <?php else : ?>
              <?php if ($this->countModules('left')) : ?>
                <!-- LEFT MENU -->
                    <div id="left">
                        <jdoc:include type="modules" name="left" style="xhtml" />
                    </div>
                <!-- END OF LEFT MENU -->
                <?php endif ?>
                
                <div id="content">
                  
                    <?php if ($this->countModules('breadcrumbs')) : ?>
                        <!-- INNER PAGE BREADCRUMBS -->
                            <div id="breadcrumbs">
                                <jdoc:include type="modules" name="breadcrumbs" style="xhtml" />
                            </div>
                        <!-- END OF INNER PAGE BREADCRUMBS -->
                    <?php endif ?>  
                    
                  <h1 class="componentheading"><?php echo $this->title; ?><?php // echo $this->getTitle(); ?></h1>
                    
          <?php if ($this->countModules('featuredimg')) : ?>
                        <!-- INNER PAGE FEATURED IMAGE -->
                            <div id="featuredimg">
                                <jdoc:include type="modules" name="featuredimg" style="xhtml" />
                            </div>
                        <!-- END OF INNER PAGE FEATURED IMAGE -->
                    <?php endif ?>  
                    
                    <?php if ($this->countModules('right')) : ?>
                    <!-- RIGHT SIDEBAR -->
                        <div id="right">
                            <jdoc:include type="modules" name="right" style="xhtml" />
                        </div>
                    <!-- END OF RIGHT SIDEBAR -->
                    <?php endif ?>
                    
                    <div id="content-block" style="width: <?php if ($this->countModules('right')) : ?> 498px <?php else : ?> 735px <?php endif ?> ">
                    
                      <jdoc:include type="component" style="xhtml" />
                    
            <?php if (($this->countModules('bottom')) || ($this->countModules('bottomleft')) || ($this->countModules('bottomright')) ) : ?>
                        
                            <div id="bottomuser">
                
                <?php if ($this->countModules('bottom')) : ?>
                                        <!-- USER BOTTOM -->
                                            <div id="bottom">
                                                <jdoc:include type="modules" name="bottom" style="xhtml" />
                                            </div>
                                        <!-- END OF USER LEFT -->
                                <?php endif ?>
                
                <?php if ($this->countModules('bottomleft')) : ?>
                                        <!-- USER LEFT -->
                                            <div id="bottomleft">
                                                <jdoc:include type="modules" name="bottomleft" style="xhtml" />
                                            </div>
                                        <!-- END OF USER LEFT -->
                                <?php endif ?>
                                
                                <?php if ($this->countModules('bottomright')) : ?>
                                        <!-- USER RIGHT -->
                                                <div id="bottomright">
                                                    <jdoc:include type="modules" name="bottomright" style="xhtml" />
                                                </div>   
                                        <!-- END OF USER RIGHT -->
                                <?php endif ?>
                                
                                <?php if ($this->countModules('bottombottom')) : ?>
                                        <!-- USER BOTTOMBOTTOM -->
                                            <div id="bottom">
                                                <jdoc:include type="modules" name="bottombottom" style="xhtml" />
                                            </div>
                                        <!-- END OF USER LEFT -->
                                <?php endif ?>
                        
                            </div>
                        
                        <?php endif ?>
                    
                    </div>
                    
                </div>
            <?php endif ?>
            
    </div> 
    </div>
    
  <?php if ($this->countModules('footer')) : ?>
    <!-- FOOTER -->
      <div id="footer">
        <div id="overall">
                    <jdoc:include type="modules" name="footer" style="xhtml" />
                </div>
            </div>    
    <!-- END OF FOOTER -->
  <?php endif ?>      
    
<span class="debug"><?php if ($this->countModules('debug')) : ?><jdoc:include type="modules" name="debug" /><?php endif ?></span>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39343613-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>

</html>

