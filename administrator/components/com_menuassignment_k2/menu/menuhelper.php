<?php
/**
* @version 1.5.1
* @package DJ Menu
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
*
*
* DJ Menu is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Menu is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Menu. If not, see <http://www.gnu.org/licenses/>.
*
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modDJMenuHelper {

    var $name = null;
    
    var $params = null;
    
    var $tabakt = null;
	
	var $jversion = '15';
	
	

    
    function render($jversion) {
		
		$this->jversion = $jversion;
		$this->toolTip  = 'Please select an item:: if you want to assign it as the parent item';
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT menutype FROM #__menu_types ORDER BY id ASC ");
		$menutypes = $db->loadObjectList();
		
		$pathMenu = JURI::base().'components/com_menuassignment_k2/menu/';
		
		$html = '
		
		
		<script type="text/javascript" src="'.$pathMenu.'library/jquery-1.4.4.js"></script>
		<script type="text/javascript" src="'.$pathMenu.'library/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="'.$pathMenu.'library/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css"/>
		
		<script type="text/javascript">
			jQuery.noConflict();
		</script>
		
		
		<script type="text/javascript" src="'.$pathMenu.'jquery.checkboxtree.js"></script>
		
		<link rel="stylesheet" type="text/css" href="'.$pathMenu.'jquery.checkboxtree.css"/>
		
		';
		
		if($this->jversion == '16'){
			$html .= '
					<style>
						#tree1 label{
							clear: none !important;
							display: block !important;
							float: none !important;
							margin: 0px !important;
						}
						#tree1 li{
							 padding: 5px !important;	
						}
						#tree1 li ul li{
							margin-left: 18px;	
						}
					</style>
			
			';
		}
		
		$html .= '
		
		<script type="text/javascript">
		 
		(function($) { 
        //<!--
        $(document).ready(function() {
             
            
			$(\'#tree1\').checkboxTree({
            initializeChecked: \'expanded\',
            initializeUnchecked: \'collapsed\'
        });
             
        });
		})(jQuery);

        //-->
    	</script>';
		
		$html .= '
		
		<div class="tabs-1" >
		
		<ul id="tree1" >';
		
		
		$parent_id = '0';
		if($this->jversion == '16'){
			$parent_id = '1';
		}
		 
		foreach($menutypes as $menutype){
        
			
			
			$this->name = $menutype->menutype;
			
			$html .= "<li  >  <input  type='checkbox' name='menuid[]' id='$this->name".'_'."$parent_id' value='$this->name".'_'."$parent_id"."_"."$parent_id' onclick=\"javascript: if(this.checked) { document.getElementById('menuidCounter').value ++ ;} else { document.getElementById('menuidCounter').value -- }\" /> <label class='hasTip' title='$this->toolTip'  for='$this->name".'_'."$parent_id' > ".$this->name.'</label> ';
			 
				$html .= '<ul >';
				
				$html .= $this->ShowMenu();
				
				$html .= '</ul>';
			
			$html .= '</li>';	
		}
		
		$html .= '
			</ul>
		</div>';
		
		return $html;
        
    }
    
    function ShowMenu() {
    
		$app = JFactory::getApplication();
		$menu = $app->getMenu('site');
        //$menu = &JSite::getMenu('site');
        
        $user = &JFactory::getUser();
        
		$end = 0;
		
        //get menu items        
        $rows = $menu->getItems('menutype', $this->name);
		
		
		/*if($this->name == 'usermenu'){
			ob_clean();
			echo "<pre>";
			print_r($rows);
			exit;
		}*/
		
        
		if(!is_array($rows)) return;
		
        $children = array();
		
        foreach ($rows as $v) {
			
			if($this->jversion == '16'){
			    $v->parent = $v->parent_id  ;
				$v->sublevel = $v->level  ;
				$v->name = $v->title  ;
			}
			
        	if($end && $v->sublevel >= $end){
        		continue;
        	}
			
            if ($v->access > 0) {
                if ($user->id == '0') {
                    //continue;
                }
                if ($v->access == '2' && ($user->usertype == 'Registered')) {
                   // continue;
                }
            }
			
			
		 
			
            $pt = $v->parent;

            
            $list = @$children[$pt] ? $children[$pt] : array();

            
            array_push($list, $v);

            
            $children[$pt] = $list;

            
        }

        
        $this->tabakt = $this->aktywne($children);

        
       
		if($this->jversion == '16'){
			return $this->mosRecurseListMenu(1, 1, $children);		
		}
        
        return $this->mosRecurseListMenu(0, 0, $children);
        
    }

    
    function aktywne(&$children) {
    
        global $Itemid;

        
        foreach ($children as $tab) {
        
            foreach ($tab as $obj) {
            
                if ($obj->id == $Itemid)
                    return $obj->tree;

                    
            }
            
        }
        
        return array();
    }
    
    /**
     
     * Utility function for writing a menu link
     
     */
     
    function mosGetMenuLink($mitem, $level = 0, &$params, $havechild = null) {
    
        global $Itemid;
        
        $txt = '';
        
        // Menu Link is a special type that is a link to another item
        
        if ($mitem->type == 'menulink') {
        
            $menu = &JSite::getMenu();
            
            if ($tmp = $menu->getItem($mitem->query['Itemid'])) {
            
                $name = $mitem->name;
                
                $mid = $mitem->id;
                
                $parent = $mitem->parent;
                
                $mitem = clone ($tmp);
                
                $mitem->name = $name;
                
                $mitem->mid = $mid;
                
                $mitem->parent = $parent;
                
            } else {
            
                return;
                
            }
            
        }
        
        switch ($mitem->type) {
        
            case 'separator':
            
				$mitem->browserNav = 3;
                break;
                
            case 'url':
            
                if (preg_match('/index.php\?/', $mitem->link)) {
                
                    if (!preg_match('/Itemid=/', $mitem->link)) {
                    
                        $mitem->link .= '&amp;Itemid='.$mitem->id;
                        
                    }
                    
                }
                
                break;
                
            default:
            
                $mitem->link = 'index.php?Itemid='.$mitem->id;
                
                break;
                
        }
        
        // Active Menu highlighting
        
        $current_itemid = intval($Itemid);

        
        $classplus = '';

        
        if (in_array($mitem->id, $this->tabakt)) {
        
            $classplus = 'active ';
            
        }

        
        $class = 'class="'.$classplus.'"';
        
        if ($level == 0)
            $class = 'class="dj-up_a '.$classplus.'"';
            
        if ($havechild && $level != 0) {
        
            if ( empty($classplus))
                $class = ' class="dj-more" ';
                
            else
                $class = ' class="dj-more-'.$classplus.'" ';
                
        }

        
        // replace & with amp; for xhtml compliance
        
        $menu_params = new stdClass ();
        
        $menu_params = new JParameter($mitem->params);
        
        $menu_secure = $menu_params->def('secure', 0);
        
        if (strcasecmp(substr($mitem->link, 0, 4), 'http')) {
        
            $mitem->url = JRoute::_($mitem->link, true, $menu_secure);
            
        } else {
        
            $mitem->url = $mitem->link;
            
        }

        
        $spanclass = '';
        
        if ($havechild && $level == 0) {
        
            $spanclass = ' class="dj-drop" ';
            
        }

        // get image if selected
		
		$menu_img = '';
        $menu_image = $menu_params->get('menu_image');
        
        if ($menu_image != '-1') {
            $menu_img = '<img src="'.JURI::base().'images/stories/'.$menu_image.'" alt="'.$menu_image.'" />';
        }
		
        // replace & with amp; for xhtml compliance
        
        // remove slashes from excaped characters
        
        $mitem->name = stripslashes(htmlspecialchars($mitem->name));
        
        $mitemname = $menu_img.$mitem->name;
        
        if ($level == 0) {
        
            $mitemname = '<span '.$spanclass.'>'.$mitemname.'</span>';
            
        }
		
		$txt = $mitem->name;
        
       

        
        return $txt;
        
    }
    
    /**
     
     * Search for Itemid in link
     
     */
     
    function ItemidContained($link, $Itemid) {
    
        $link = str_replace('&amp;', '&', $link);
        
        $temp = explode("&", $link);
        
        $linkItemid = "";
        
        foreach ($temp as $value) {
        
            $temp2 = explode("=", $value);
            
            if ($temp2[0] == "Itemid") {
            
                $linkItemid = $temp2[1];
                
                break;
                
            }
            
        }
        
        if ($linkItemid != "" && $linkItemid == $Itemid) {
        
            return true;
            
        } else {
        
            return false;
            
        }
        
    }
    
    /**
     
     *  Module Menu
     
     */
     
    function mosRecurseListMenu($id, $level, &$children) {
    
        global $Itemid;
        
		$html = "";
		
        global $HTTP_SERVER_VARS,$mosConfig_live_site;
        
			 
        if (@$children[$id]) {
        
            $elements = count($children[$id]);
            
            $counter = 0;
            
            foreach ($children[$id] as $row) {
            
			
                $counter++;
                
                $separator = false;
                
                switch ($row->type) {
                
                    case 'separator':
                    
                        // do nothing
                        
                        $separator = true;
                        
                        break;
                        
                    case 'url':
                    
                        if (preg_match('/index.php\?/', $row->link)) {
                        
                            if (!preg_match('/Itemid=/', $row->link)) {
                            
                                $row->link .= '&Itemid='.$row->id;
                                
                            }
                            
                        }
                        
                        break;
                        
                    default:
                    
                        if (!preg_match('/Itemid=/', $row->link)) {
                        
                            $row->link .= "&Itemid=$row->id";
                            
                        }
                        
                        break;
                        
                }

                
                @$havechild = is_array($children[$row->id]);
                
                $classname = "";

                
                if ($level == 0) {
                
                    $classname .= "dj-up Itemid".$row->id." ";
                    
                }

                
                if ($counter == 1) {
                
                    $classname .= "first ";
                    
                } else if ($counter == $elements) {
                
                    $classname .= "last ";
                    
                }

                
                if (in_array($row->id, $this->tabakt)) {
                
                    $classname .= "active";
                    
                }
				
                if ($level > 0) {
                    $classname .= " Itemid".$row->id;
                }
				
                if ($separator) {
                    $classname .= " separator";
                }
                
                $class = "";
                
                if (! empty($classname)) {
                
                    $class = " class=\"".$classname."\"";
                    
                }

                if ($havechild) {
                
                    $html .= "<li > <input  type='checkbox' name='menuid[]' id='$this->name".'_'."$row->id' value='$this->name".'_'."$row->id".'_'."$row->sublevel' onclick=\"javascript: if(this.checked) { document.getElementById('menuidCounter').value ++ ;} else { document.getElementById('menuidCounter').value -- }\" /> <label class='hasTip' title='$this->toolTip'  for='$this->name".'_'."$row->id' > ".$this->mosGetMenuLink($row, $level, $this->params, 1) . "</label>";
                    
                    $html .= "\n";
                    
                    if ($level == 0) {
                    
                        $html .= "<ul  >\n";
						//$html .= "<li  > </li>\n";
                        
                        $html .=$this->mosRecurseListMenu($row->id, $level + 1, $children);
                        
						//$html .= "<li  > </li>\n";
                        $html .= "</ul>\n";
                        
                    } else {
                    
                        $html .= "<ul>\n";
                        //$html .= "<li  > </li>\n";
						
                        $html .=$this->mosRecurseListMenu($row->id, $level + 1, $children);
                        
						//$html .= "<li  > </li>\n";
                        $html .= "</ul>\n";

                        
                    }
                    
                    $html .= "</li>\n";
                    
                } else {
                
                    $html .= "<li > <input  type='checkbox' name='menuid[]' id='$this->name".'_'."$row->id' value='$this->name".'_'."$row->id".'_'."$row->sublevel' onclick=\"javascript: if(this.checked) { document.getElementById('menuidCounter').value ++ ;} else { document.getElementById('menuidCounter').value -- }\" /> <label class='hasTip' title='$this->toolTip'  for='$this->name".'_'."$row->id' > ".$this->mosGetMenuLink($row, $level, $this->params)." </label> </li>";
                    
                    $html .= "\n";
                    
                }
                
            }
            
        }
        
    
	
	return $html;
	}
}


?>