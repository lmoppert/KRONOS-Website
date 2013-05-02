<?php
/**
* @version		1.7.0
* @package		AceSearch
* @subpackage	Uninstaller
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * 
 * This is the special installer addon based on the one created by Andrew Eddie and the team of JXtended.
 * We thank for this cool idea of extending the installation process easily
 */

// No Permission
defined('_JEXEC') or die('Restricted Access');

JLoader::register('JModuleHelper', JPATH_ROOT.'/libraries/joomla/application/module/helper.php');

// Import Libraries
jimport('joomla.application.helper');
jimport('joomla.filesystem.file');
jimport('joomla.installer.installer');

$db = &JFactory::getDBO();

$status = new JObject();
$status->adapter = array();
$status->extensions = array();
$status->modules = array();
$status->plugins = array();

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * DATABASE REMOVAL SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$db->setQuery('DROP TABLE IF EXISTS `#__acesearch_extensions_backup`');
$db->query();
$db->setQuery('RENAME TABLE `#__acesearch_extensions` TO `#__acesearch_extensions_backup`');
$db->query();

$db->setQuery('DROP TABLE IF EXISTS `#__acesearch_filters_backup`');
$db->query();
$db->setQuery('RENAME TABLE `#__acesearch_filters` TO `#__acesearch_filters_backup`');
$db->query();

$db->setQuery('DROP TABLE IF EXISTS `#__acesearch_filters_groups_backup`');
$db->query();
$db->setQuery('RENAME TABLE `#__acesearch_filters_groups` TO `#__acesearch_filters_groups_backup`');
$db->query();

$db->setQuery('DROP TABLE IF EXISTS `#__acesearch_search_results_backup`');
$db->query();
$db->setQuery('RENAME TABLE `#__acesearch_search_results` TO `#__acesearch_search_results_backup`');
$db->query();

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* ADAPTER REMOVAL SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$adapter = JPATH_LIBRARIES.'/joomla/installer/adapters/acesearch_ext.php';
if (JFile::exists($adapter)) {
	JFile::delete($adapter);
	$status->adapter[] = 1;
}

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* EXTENSION REMOVAL SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$db =& JFactory::getDBO();
$db->setQuery("SELECT name FROM #__acesearch_extensions_backup WHERE name != ''");
$extensions = $db->loadResultArray();

if (!empty($extensions)) {
	foreach ($extensions as $extension) {
		$status->extensions[] = array('name' => $extension);
	}
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * MODULE REMOVAL SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$modules = $this->manifest->xpath('modules/module');
if (!empty($modules)) {
	foreach ($modules as $module) {
		$mtitle		= $module->getAttribute('title');
		$mpath		= $module->getAttribute('path');
		$mclient	= $module->getAttribute('client');
		
		$arr = array_reverse(explode('/', $mpath));
		$mmodule = $arr[0];
		
		$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'module' AND element = '{$mmodule}' LIMIT 1");
		$id = $db->loadResult();
		if ($id) {
			$installer = new JInstaller();
			$installer->uninstall('module', $id);
			$status->modules[] = array('name' => $mtitle, 'client' => $mclient);
		}
	}
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * PLUGIN REMOVAL SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$plugins = $this->manifest->xpath('plugins/plugin');
if (!empty($plugins)) {
	foreach ($plugins as $plugin) {
		$ppath		= $plugin->getAttribute('path');
		$ptitle		= $plugin->getAttribute('title');
		$pfolder	= $plugin->getAttribute('folder');
		
		$arr = array_reverse(explode('/', $ppath));
		$pelement = $arr[0];
		
		$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = '{$pelement}' LIMIT 1");
		$id = $db->loadResult();
		if ($id) {
			$installer = new JInstaller();
			$installer->uninstall('plugin', $id);
			$status->plugins[] = array('name' => $ptitle, 'group' => $pfolder);
		}
	}
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
 $rows = 0;
?>

<h2>AceSearch Removal</h2>
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
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
	<?php
if (count($status->adapter)) : ?>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'AceSearch Adapter'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
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
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
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
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
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
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif;
?>
	</tbody>
</table>