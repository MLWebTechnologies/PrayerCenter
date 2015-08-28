<?php
/**
* PrayerCenter Component for Joomla
* By Mike Leeper
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
global $prayercenteradmin;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, 'manage_sub');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_prayercenter&task=manage_sub'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span12">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_PRAYERCENTER_FILTER_SEARCH_DESC');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_PRAYERCENTER_SEARCH'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_PRAYERCENTER_SEARCH'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>
		<div class="clearfix"> </div>
		<table class="table table-striped" id="pcsubsList">
  	<thead>
		<tr>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
  			<th class="hidden-phone">
  				<?php echo JHTML::_('grid.sort',   'Send Email', 'a.email', $listDirn, $listOrder, 'prayercentr.manage_sub' ); ?>
  			</th>
  			<th class="hidden-phone">
  				<?php echo JHTML::_('grid.sort',   'Date', 'a.date', $listDirn, $listOrder, 'prayercentr.manage_sub' ); ?>
  			</th>
        <th class="title" width="50">&nbsp;</th>
  			<th class="hidden-phone center">
  				<?php echo JHTML::_('grid.sort',   'Approved', 'a.approved', $listDirn, $listOrder, 'prayercentr.manage_sub' ); ?>
  			</th>
  			<th class="hidden-phone center">
  				<?php echo JHTML::_('grid.sort',   'ID', 'a.id', $listDirn, $listOrder, 'prayercentr.manage_sub' ); ?>
  			</th>
		</tr></thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
    	<tbody>
		<?php
		foreach ($this->items as $i => $item) {
			?>
		<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->id; ?>">
			<td class="center hidden-phone" width="5">
      	<?php echo JHtml::_('grid.id', $i, $item->id); ?>
      </td>
      <td class="small hidden-phone"><?php echo $item->email; ?></td>
      <td class="small hidden-phone"><?php echo $item->date; ?></td>
      <td align="center">&nbsp;</td>
      <?php
			$approvedimg = '';
      $approvedimg[] = '<a class="btn btn-micro active" rel="tooltip"';
			if ($item->approved) {
  			$approvedimg[] = ' href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\', \'prayercenter.unapprove\')"';
  			$approvedimg[] = ' title="'.addslashes(htmlspecialchars(JText::_('Unapprove'))).'">';
  			$approvedimg[] = '<i class="icon-publish">';
			} else {
  			$approvedimg[] = ' href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\', \'prayercenter.approve\')"';
  			$approvedimg[] = ' title="'.addslashes(htmlspecialchars(JText::_('Approve'))).'">';
  			$approvedimg[] = '<i class="icon-unpublish">';
			} 
			$approvedimg[] = '</i>';
			$approvedimg[] = '</a>';
      ?>
     <td class="center small hidden-phone"><?php echo implode($approvedimg); ?></td>
		<td class="center hidden-phone">
			<?php echo (int)$item->id; ?>
		</td>
    </tr>
				<?php
			}?>
		</tbody>
		</table>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	</div></form><br />
	<?php
  $prayercenteradmin->PCfooter();
?>