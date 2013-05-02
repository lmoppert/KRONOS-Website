<?php

/**
* Gavick GK Stock - layout
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="gks_main" id="<?php echo $this->config['auto_id']; ?>">
	<?php if($this->config['showChart']) : ?>
	<div class="gks_chart">
		<div class="gks_loader">Loading</div>
		<?php 
			
			$q = '';
			
			if(!isset($_COOKIE['gks_'.$this->config['module_unique_id']])){
				for($i = 0; $i < count($this->parsedData) && $i < $this->config['amount']; $i++) {
					$q .= $this->parsedData[$i][0].':'.$this->parsedData[$i][1].',';
				}
			}else{
				$cookie = htmlspecialchars(strip_tags($_COOKIE['gks_'.$this->config['module_unique_id']]));
				$array = explode(',',$cookie);
				
				for($i = 0; $i < count($array) && $i < $this->config['amount']; $i++)
				{
					if(count($this->parsedData) >= $array[$i]){
						$q .= $this->parsedData[$array[$i]][0].':'.$this->parsedData[$array[$i]][1].',';
					}
				}
			}
			
			$q = substr($q, 0, strlen($q) - 1);
			
		?>
		<img src="http://www.google.com/finance/chart?cht=c&amp;q=<?php echo $q; ?>" class="gks_img" alt="Chart" />
		<div class="gks_timeline"></div>
	</div>
	<?php endif; ?>
	<div class="gks_stocks">
	
		<?php for($i = 0; $i < count($this->parsedData);$i++) : ?>
		
		
		<?php
		
			$classCSS = '';
			
			if($i < $this->config['amount'])
			{	
				switch($i)
				{
					case 0:$classCSS = ' first';break;
					case 1:$classCSS = ' second';break;
					case 2:$classCSS = ' third';break;
					case 3:$classCSS = ' fourth';break;
				}
			}
			
		?>
		
		<div class="gks_stock">
			<?php if($this->config['showChart']) : ?>
			<span class="gks_trigger<?php echo $classCSS; ?>" title="<?php echo $this->parsedData[$i][0].':'.$this->parsedData[$i][1]; ?>">o</span>
			<?php endif; ?>
			<?php if($this->config['links'] == 1) : ?>
			<a href="http://www.google.com/finance?q=<?php echo $this->parsedData[$i][0].':'.$this->parsedData[$i][1]; ?>" class="gks_stock_name" title="<?php echo $this->parsedData[$i][0].':'.$this->parsedData[$i][1]; ?>"><?php echo htmlspecialchars($this->parsedData[$i][2]); ?></a>
			<?php else: ?>
			<span class="gks_stock_name" title="<?php echo $this->parsedData[$i][0].':'.$this->parsedData[$i][1]; ?>"><?php echo htmlspecialchars($this->parsedData[$i][2]); ?></span>
			<?php endif; ?>
			<span class="gks_value"><?php echo $this->parsedData[$i][3]; ?></span>
			<span class="gks_change<?php echo ((strpos($this->parsedData[$i][4], '+') !== false) ? ' plus' : ((strpos($this->parsedData[$i][4], '-') !== false) ? ' minus': '')); ?>"><?php echo str_replace('+','&#x25b2;',str_replace('-','&#x25bc;',$this->parsedData[$i][4])); ?> (<?php echo $this->parsedData[$i][5]; ?>%)</span>
		</div>
		<?php endfor; ?>
		<?php if($this->config['tooltips'] == 1) : ?>
		<div class="gks_tooltip_data">
			<?php for($i = 0; $i < count($this->parsedData);$i++) : ?>
			<div class="gks_tooltip_stock">		
				<div class="gks_tooltip_chart">
					<img src="http://www.google.com/finance/chart?cht=c&amp;q=<?php echo $this->parsedData[$i][0].':'.$this->parsedData[$i][1]; ?>" alt="<?php echo $this->parsedData[$i][0].':'.$this->parsedData[$i][1]; ?>" />
					<div class="gks_tooltip_timeline"></div>
				</div>
				<div class="gks_tooltip_datas">
					<h3><?php echo htmlspecialchars($this->parsedData[$i][2]); ?></h3>
					<sub><strong><?php echo JText::_('COMPANY_ID'); ?></strong> [<?php echo $this->parsedData[$i][0].':'.$this->parsedData[$i][1]; ?>]</sub>
					<span class="gks_tooltip_value"><strong><?php echo JText::_('LAST_TRADE'); ?></strong><?php echo $this->parsedData[$i][3]; ?></span>
					<span class="gks_tooltip_time"><strong><?php echo JText::_('TRADE_TIME'); ?></strong><?php echo $this->parsedData[$i][6]; ?></span>
					<span class="gks_tooltip_change<?php echo ((strpos($this->parsedData[$i][4], '+') !== false) ? ' plus' : ((strpos($this->parsedData[$i][4], '-') !== false) ? ' minus': '')); ?>"><strong><?php echo JText::_('VALUE_CHANGE'); ?></strong><?php echo str_replace('+','&#x25b2;',str_replace('-','&#x25bc;',$this->parsedData[$i][4])); ?> (<?php echo $this->parsedData[$i][5]; ?>%)</span>
				</div>
			</div>	
			<?php endfor; ?>
		</div>
		<div class="gks_tooltip ts-<?php echo $this->config['tooltip_position']; ?> tp-<?php echo $this->config['tooltip_layout']; ?>"></div>
		<?php endif; ?>
	</div>
</div>