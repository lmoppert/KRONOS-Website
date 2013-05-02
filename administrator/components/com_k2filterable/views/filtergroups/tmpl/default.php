<?php

defined('_JEXEC') or die;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$ordering 	= ($listOrder == 'a.id');
$saveOrder	= $listOrder=='a.ordering';

?>

<form action="<?php echo JRoute::_('index.php?option=com_k2filterable&view=filtergroups');?>" method="post" name="adminForm" id="adminForm">

	<div class="clr"> </div>

<?php if(!empty($this->items)): ?>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="left">
					<?php echo JHtml::_('grid.sort', 'Title', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'filtergroups.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th width="5%"></th>
				<th class="nowrap" width="3%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$item->max_ordering = 0; 
			$ordering	= ($listOrder == 'a.ordering');
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
				  <?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>					
					<a href="<?php echo JRoute::_('index.php?option=com_k2filterable&task=filtergroup.edit&id='.(int) $item->id); ?>" title="<?php echo JText::sprintf('COM_K2FILTERABLE_EDIT_GROUP', $this->escape($item->title)); ?>">
						<?php echo $this->escape($item->title); ?></a>				
				</td>				
				<td class="order">
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, true, 'filtergroups.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                                                <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'filtergroups.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, true, 'filtergroups.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'filtergroups.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
				</td>
				<td class="center">
					<a title="" onclick="return listItemTask('cb<?php echo $i;?>','filtergroups.delete')" href="javascript:void(0);" class="jgrid"><span class="state trash"><span class="text">Delete</span></span></a>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	      </table>
<?php else: ?>

<h3><?php echo JText::_('COM_K2FILTERABLE_GETTING_STARTED') ?></h3>

<p><?php echo JText::_('COM_K2FILTERABLE_GETTING_STARTED_MSG') ?></p>

<?php endif; ?>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
