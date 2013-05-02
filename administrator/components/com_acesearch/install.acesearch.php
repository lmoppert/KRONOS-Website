<?php
/**
* @version		1.7.0
* @package		AceSearch
* @subpackage	Installer
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * 
 * This is the special installer addon based on the one created by Andrew Eddie and the team of JXtended.
 * We thank for this cool idea of extending the installation process easily
 */

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Import Libraries
jimport('joomla.filesystem.file');
jimport('joomla.installer.installer');
JLoader::register('JModuleHelper', JPATH_ROOT.'/libraries/joomla/application/module/helper.php');

$db =& JFactory::getDBO();

$status = new JObject();
$status->adapter = array();
$status->extensions = array();
$status->modules = array();
$status->plugins = array();

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* EXTENSION INSTALLATION SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$extensions = $this->manifest->xpath('extensions/extension');
if (!empty($extensions)) {
	foreach ($extensions as $extension) {
		$option	= $extension->getAttribute('option');
		$ordering = $extension->getAttribute('ordering');
		
		$file = $this->parent->getPath('source').'/admin/extensions/'.$option.'.xml';
		if (!file_exists($file)) {
			continue;
		}
		
		$manifest = JFactory::getXML($file);
		
		if (is_null($manifest)) {
			continue;
		}
		
		$name = (string)$manifest->name;
		
		$db->setQuery('SELECT id FROM #__acesearch_extensions WHERE extension = '.$db->Quote($option));
		$ext = $db->loadResult();
		
		if (empty($ext)) {
			$client = (string)$manifest->client;

			$prms = array();
			$prms['handler'] = '1';
			$prms['custom_name'] = '';
			$prms['access'] = '1';
			$prms['result_limit'] = '';
			
			$element = $manifest->install->defaultParams;
			if (is_a($element, 'JXMLElement') && count($element->children())) {
				$defaultParams = $element->children();
				
				if (count($defaultParams) != 0) {
					foreach ($defaultParams as $param) {
						$pname = $param->getAttribute('name');
						$value = $param->getAttribute('value');
						
						$prms[$pname] = $value;
					}
				}
			}
			
			$reg = new JRegistry($prms);
			$params = $reg->toString();
			
			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_acesearch/tables');
			$row =& JTable::getInstance('AcesearchExtensions', 'Table');	
			$row->name 			= $name;
			$row->extension 	= $option;
			$row->ordering 		= $ordering;
			$row->params 		= $params;
			$row->client 		= $client;
			$row->store();
		}
		
		$status->extensions[] = array('name' => $name);
	}
}

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* MODULE INSTALLATION SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$modules = $this->manifest->xpath('modules/module');
if (!empty($modules)) {
	foreach ($modules as $module) {
		$mpath		= $module->getAttribute('path');
		$mtitle		= $module->getAttribute('title');
		$mposition	= $module->getAttribute('position');
		$mordering	= $module->getAttribute('ordering');
		$mpublished	= $module->getAttribute('published');
		$mclient	= $module->getAttribute('client');
		
		$arr = array_reverse(explode('/', $mpath));
		$mmodule = $arr[0];
		
		$installer = new JInstaller();
		$installer->install($this->parent->getPath('source').'/'.$mpath);
		
		$db->setQuery("UPDATE #__modules SET position = '{$mposition}', ordering = '{$mordering}', published = '{$mpublished}' WHERE module = '{$mmodule}'");
		$db->query();


		$db->setQuery("SELECT `id` FROM `#__modules` WHERE `module` = '{$mmodule}'");
		$mod_id = $db->loadResult();

		$db->setQuery("REPLACE INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES ({$mod_id}, 0)");
		$db->query();

		$status->modules[] = array('name' => $mtitle, 'client' => $mclient);
	}
}

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* PLUGIN INSTALLATION SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$plugins = $this->manifest->xpath('plugins/plugin');
if (!empty($plugins)) {
	foreach ($plugins as $plugin) {
		$ppath		= $plugin->getAttribute('path');
		$ptitle		= $plugin->getAttribute('title');
		$pfolder	= $plugin->getAttribute('folder');
		$pordering	= $plugin->getAttribute('ordering');
		$ppublished	= $plugin->getAttribute('published');
		
		$arr = array_reverse(explode('/', $ppath));
		$pelement = $arr[0];
		
		$installer = new JInstaller();
		$installer->install($this->parent->getPath('source').'/'.$ppath);
		$db->setQuery("UPDATE #__extensions SET enabled = {$ppublished}, ordering = '{$pordering}' WHERE type = 'plugin' AND element = '{$pelement}' AND folder = '{$pfolder}'");
		$db->query();

		$status->plugins[] = array('name' => $ptitle, 'group' => ucfirst($pfolder));
	}
}

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* ADAPTER INSTALLATION SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$adp_src = JPATH_ADMINISTRATOR.'/components/com_acesearch/adapters/acesearch_ext.php';
$adp_dst = JPATH_LIBRARIES.'/joomla/installer/adapters/acesearch_ext.php';
if (is_writable(dirname($adp_dst))) {
	JFile::copy($adp_src, $adp_dst);
	$status->adapter[] = 1;
}

$rows = 0;
?>
<img src="components/com_acesearch/assets/images/logo.png" alt="Joomla! Search Component" width="60" height="89" align="left" />

<h2>AceSearch Installation</h2>
<h2><a href="index.php?option=com_acesearch">Go to AceSearch Control Panel</a></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'AceSearch '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php
if (count($status->adapter)) : ?>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'AceSearch Adapter'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php
endif;
if (count($status->extensions)) : ?>
		<tr>
			<th colspan="3"><?php echo JText::_('AceSearch Extension'); ?></th>
		</tr>
	<?php foreach ($status->extensions as $extension) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key" colspan="2"><?php echo $extension['name']; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif;
if (count($status->modules)) : ?>
		<tr>
			<th><?php echo JText::_('Module'); ?></th>
			<th><?php echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->modules as $module) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif;
if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif;
 ?>

	</tbody>
</table>