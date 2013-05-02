<?php
/**
* @module		Art Image Cycle
* @copyright	Copyright (C) 2010 artetics.com
* @license		GPL
*/

defined('_JEXEC') or die('Restricted access');
error_reporting(E_ERROR);
$document = &JFactory::getDocument();

$path = $params->get('path', '');
$style = $params->get('style', '');
$effect = $params->get('effect', 'fade');
$timeout = $params->get('timeout', 4000);
$speed = $params->get('speed', 1000);
$loadJQuery = $params->get('loadJQuery', 1);
$openLinksNewWindow = $params->get('openLinksNewWindow', 0);
$showImages = $params->get('showImages', 'byname');
$numberOfImages = $params->get('numberOfImages', '');
$lightbox = $params->get('lightbox', 0); 
$showArrows = $params->get('showArrows', 0); 
$useSubFolders = $params->get('useSubFolders', 0); 
$moduleId = $module->id;

if (!function_exists('artAICFileAscSort')) {
  function artAICFileAscSort($a, $b) {
    list ($anum, $aalph) = explode ('.', $a);
    list ($bnum, $balph) = explode ('.', $b);
    
    if ($anum == $bnum) return strcmp($aalph, $balph);
    return $anum < $bnum ? -1 : 1;
  }
}

if (!function_exists('isImage')) {
	function isImage($fileName) {
		$extensions = array('.jpeg', '.jpg', '.gif', '.png', '.bmp', '.tiff', '.tif', '.ico', '.rle', '.dib', '.pct', '.pict');
		$extension = substr($fileName, strrpos($fileName,"."));
		if (in_array(strtolower($extension), $extensions)) return true;
		return false;
	}
}

if ($path) {
	$directory_stream = @ opendir (JPATH_SITE . DS . $path . DS) or die ("Could not open a directory stream for <i>" . JPATH_SITE . DS . $path . DS . "</i>");
  $file_handle = @fopen(JPATH_SITE . DS . $path . DS . 'artimagecycle.txt', 'rb');
  $descriptionArray = array();
  if ($file_handle) {
    while (!feof($file_handle) ) {
      $line_of_text = fgets($file_handle);
      $parts = explode('=', htmlspecialchars($line_of_text, ENT_QUOTES));
      $str = '';
      $partsNumber = count($parts);
      for ($i = 1; $i < $partsNumber; $i++) {
        $str .= $parts[$i];
        if ($i != $partsNumber - 1) {
          $str .= '=';
        }
      }
      $str = str_replace('"', "'", $str);
      $descriptionArray[$parts[0]] = html_entity_decode($str);

    }
    fclose($file_handle);
  }

	$filelist = array();
	$ii = 1;
	while ($entry = readdir ($directory_stream)) {
		if ($entry != '.' && $entry != '..' && isImage($path . $entry)) {
			$filelist[] = $entry;
			$ii++;
		} else if ($useSubFolders && is_dir (JPATH_SITE . DS . $path . DS . $entry) && $entry != '.' && $entry != '..') {
      $path1 = JPATH_SITE . DS . $path . DS . $entry;
      $directory_stream1 = @ opendir ($path1);
      while ($entry1 = readdir ($directory_stream1)) {
        if ($entry1 != '.' && $entry1 != '..' && isImage($path1 . $entry1)) {
          $filelist[] = $entry . '/' . $entry1;
          $ii++;
        }
      }
    }
	}

	if ($showImages == 'byname') {
		usort ($filelist, 'artAICFileAscSort');
	} else {
		shuffle($filelist);
	}
  if ($numberOfImages && $numberOfImages > 0) {
    $filelist = array_slice($filelist, 0, $numberOfImages);
	}
    reset ($filelist);
    if ($loadJQuery) {
      $document->addScript(JURI::root() . 'modules/mod_artimagecycle/js/jquery.js');
    }
      switch ($lightbox) {
        case 'artsexylightbox':
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/jquery.easing.1.3.js');
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/sexylightbox.v2.3.4.jquery.min.js');
          $document->addStyleSheet( JURI::root() . 'plugins/content/artsexylightbox/css/sexylightbox.css' );
          $document->addScript(JURI::root() . 'plugins/content/artsexylightbox/js/jquery.nc.js');
          ?>
          <script type="text/javascript" charset="utf-8">asljQuery(function(){asljQuery(document).ready(function(){SexyLightbox.initialize({"imagesdir": "<?php echo JURI::BASE(); ?>plugins/content/artsexylightbox/images","find": "imagecycle"});})});</script>
          <?php
        break;
        case 'artcolorbox':
          $document->addScript( JURI::root() . 'plugins/content/artcolorbox/js/jquery.colorbox-min.js' );
          $document->addScript( JURI::root() . 'plugins/content/artcolorbox/js/jquery.nc.js' );
          $document->addStyleSheet( JURI::root() . 'plugins/content/artcolorbox/css/themes/1/colorbox.css' );
          ?>
          <script type="text/javascript" charset="utf-8">acbjQuery(document).ready(function(){acbjQuery("a[rel^='imagecycle']").colorbox({});});</script>
          <?php
        break;
        case 'artprettyphoto':
          $document->addScript(JURI::root() . 'plugins/content/artprettyphoto/js/jquery.prettyPhoto.js');
          $document->addStyleSheet( JURI::root() . 'plugins/content/artprettyphoto/css/prettyPhoto.css');
          ?>
          <script type="text/javascript" charset="utf-8">jQuery(document).ready(function(){jQuery("a[rel^='imagecycle']").prettyPhoto({theme:"facebook"});});</script>
          <?php
        break;
        case 'artnicebox':
          $document->addScript( JURI::root() . 'plugins/content/artnicebox/js/jquery.nc.js' );
          $document->addScript( JURI::root() . 'plugins/content/artnicebox/js/nflightbox.js' );
          $document->addStyleSheet( JURI::root() . 'plugins/content/artnicebox/css/nf.lightbox.css' ); 
          ?>
          <script type="text/javascript" charset="utf-8">anbjQuery(document).ready(function(){anbjQuery("a[rel^='imagecycle']").lightBox({})});</script>
          <?php
        break;
        default:
        break;
      }
    $document->addScript(JURI::root() . 'modules/mod_artimagecycle/js/script.js');
    $document->addStylesheet(JURI::root() . 'modules/mod_artimagecycle/css/style.css');
    if (!$showArrows) {
      $document->addCustomTag('<script>jQuery(document).ready(function(){jQuery("#artimagecycle_container' . $moduleId . '").cycle({fx: "' . $effect . '", timeout: ' . $timeout . ', speed: ' . $speed . ' });});</script>');
    } else {
      $document->addCustomTag('<script>jQuery(document).ready(function(){jQuery("#artimagecycle_container' . $moduleId . '").cycle({fx: "' . $effect . '", timeout: ' . $timeout . ', speed: ' . $speed . ' , prev: "#artimagecycle_prev' . $moduleId . '", next: "#artimagecycle_next' . $moduleId . '"});});</script>');
    }
?>
<style type="text/css">
<?php echo $style; ?>
</style>
<div id="artimagecycle_container<?php echo $moduleId; ?>" class="artimagecycle_container pics">
<?php
  $rel ='';
  if ($lightbox) {
    $rel =' rel="imagecycle"';
  }
	while ((list ($key, $entry) = each ($filelist))) {
		if ($entry != '.' && $entry != '..' && isImage($path . $entry)) {
      if ($descriptionArray && $descriptionArray[$entry]) {
        if ($openLinksNewWindow) {
          echo '<a ' . $rel . ' href="' . $descriptionArray[$entry] . '" target="_blank"><img src="' . JURI::root() . $path . '/' . $entry . '" alt="image" /></a>';
        } else {
          echo '<a ' . $rel . ' href="' . $descriptionArray[$entry] . '"><img src="' . JURI::root() . $path . '/' . $entry . '" alt="image" /></a>';
        }
      } else if ($lightbox) {
        echo '<a href="' . JURI::root() . $path . '/' . $entry . '" rel="imagecycle"><img src="' . JURI::root() . $path . '/' . $entry . '" alt="image" /></a>';
      } else {
        echo '<img src="' . JURI::root() . $path . '/' . $entry . '" alt="image" />';
      }
		}
	}
?>
</div>
<?php
}
if ($showArrows) {
?>
<a href="#" id="artimagecycle_prev<?php echo $moduleId; ?>" class="artimagecycle_prev">
  <img src="<?php echo JURI::root() . 'modules/mod_artimagecycle/images/prev.png' ;?>" alt="previous" />
</a>
<a href="#" id="artimagecycle_next<?php echo $moduleId; ?>" class="artimagecycle_next">
  <img src="<?php echo JURI::root() . 'modules/mod_artimagecycle/images/next.png' ;?>" alt="next" />
</a>
<?php
}
?>