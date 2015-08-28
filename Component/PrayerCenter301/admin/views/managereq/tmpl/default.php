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
$newtopicarray = $prayercenteradmin->PCgetTopics();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_prayercenter.managereq');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_prayercenter&task=requests.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'pcplansList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
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
		Joomla.tableOrdering(order, dirn, 'manage_req');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_prayercenter&task=manage_req'); ?>" method="post" name="adminForm" id="adminForm">
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
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_PRAYERCENTER_SEARCH'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_LIVINGWORD_SEARCH'); ?>" />
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
		<table class="table table-striped" id="pcplansList">
	   <thead>
      	<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, 'manage_req', 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="hidden-phone">
						<?php echo JHTML::_('grid.sort',   'Requester', 'a.requester', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th class="hidden-phone">
						<?php echo JHTML::_('grid.sort',   'Email Address', 'a.email', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th class="hidden-phone">
						<?php echo JHTML::_('grid.sort',   'Topic', 'a.topic', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th class="hidden-phone">
						<?php echo JHTML::_('grid.sort',   'Title', 'a.title', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th class="hidden-phone">
						<?php echo JHTML::_('grid.sort',   'Request', 'a.request', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th class="hidden-phone">
						<?php echo JHTML::_('grid.sort',   'Date / Time', 'a.datetime', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th class="hidden-phone">
						<?php echo JHTML::_('grid.sort',   'Display', 'a.displaystate', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th width="5%" class="hidden-phone nowrap">
						<?php echo JHTML::_('grid.sort',   'Published', 'a.publishstate', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th width="5%" class="hidden-phone nowrap">
						<?php echo JHTML::_('grid.sort',   'Archived', 'a.archivestate', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
					<th width="5%" class="hidden-phone nowrap">
						<?php echo JHTML::_('grid.sort',   'ID', 'a.id', $listDirn, $listOrder, 'manage_req' ); ?>
					</th>
  	  	</tr>
      </thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
		<?php
		foreach ($this->items as $i => $item) {
        $request = $item->request;
        $request = strip_tags($request,"<i><strong><u><em><strike>");
        $request = stripslashes($request);
        if (strlen($request) > 50) $request = substr($request, 0 , 48) . " ...";
				$ordering   = ($listOrder == 'a.ordering');
				$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
				$canChange  = true;//$user->authorise('core.edit.state', 'com_livingword.manageplans.' . $item->catid) && $canCheckin;
			?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo md5($item->id);?>">
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = ' inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip<?php echo $disableClassName;?>" title="<?php echo $disabledLabel;?>">
							<i class="icon-menu"></i>
						</span>
				<!--		<input type="text" style="display:none" name="order[]" size="5" value="<?php //echo $item->ordering;?>" class="width-20 text-area-order " />     -->
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
				<td class="center hidden-phone" width="5">
        	<?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="small hidden-phone"><?php echo $item->requester; ?></td>
        <td class="small hidden-phone"><?php echo $item->email; ?></td>
        <td class="small hidden-phone"><?php echo $newtopicarray[$item->topic+1]['text']; ?></td>
        <td class="small hidden-phone"><?php echo JText::_($item->title); ?></td>
				<td class="small hidden-phone">
          <?php echo $this->escape(JText::_($request)); ?>
					<?php if ($item->checked_out) : ?><br />
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, '', $canCheckin);  ?>
          <?php endif; ?>
        </td>
				<td class="small hidden-phone"><?php echo $item->datetime; ?></td>
			<?php
      $displayimg = '';
      $displayimg[] = '<a class="btn btn-micro active" rel="tooltip"';
			if ($item->displaystate) {
  			$displayimg[] = ' href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\', \'prayercenter.hidereq\')"';
  			$displayimg[] = ' title="'.addslashes(htmlspecialchars(JText::_('Hide Request'))).'">';
  			$displayimg[] = '<i class="icon-publish">';
			} else {
  			$displayimg[] = ' href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\', \'prayercenter.displayreq\')"';
  			$displayimg[] = ' title="'.addslashes(htmlspecialchars(JText::_('Display Request'))).'">';
  			$displayimg[] = '<i class="icon-unpublish">';
			} 
			$displayimg[] = '</i>';
			$displayimg[] = '</a>';
      $publishimg = '';
      $publishimg[] = '<a class="btn btn-micro active" rel="tooltip"';
			if ($item->publishstate) {
  			$publishimg[] = ' href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\', \'prayercenter.unpublish\')"';
  			$publishimg[] = ' title="'.addslashes(htmlspecialchars(JText::_('Unpublish Request'))).'">';
  			$publishimg[] = '<i class="icon-publish">';
			} else {
  			$publishimg[] = ' href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\', \'prayercenter.publish\')"';
  			$publishimg[] = ' title="'.addslashes(htmlspecialchars(JText::_('Publish Request'))).'">';
  			$publishimg[] = '<i class="icon-unpublish">';
			} 
			$publishimg[] = '</i>';
			$publishimg[] = '</a>';
      $archiveimg = '';
      $archiveimg[] = '<a class="btn btn-micro active" rel="tooltip"';
			if ($item->archivestate) {
  			$archiveimg[] = ' href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\', \'prayercenter.unarchive\')"';
  			$archiveimg[] = ' title="'.addslashes(htmlspecialchars(JText::_('Unarchive Request'))).'">';
  			$archiveimg[] = '<i class="icon-publish">';
			} else {
  			$archiveimg[] = ' href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\', \'prayercenter.archive\')"';
  			$archiveimg[] = ' title="'.addslashes(htmlspecialchars(JText::_('Archive Request'))).'">';
  			$archiveimg[] = '<i class="icon-unpublish">';
			} 
			$archiveimg[] = '</i>';
			$archiveimg[] = '</a>';
    ?>           		
		<td class="small hidden-phone"><?php echo implode($displayimg); ?></td>
		<td class="small hidden-phone"><?php echo implode($publishimg); ?></td>
		<td class="small hidden-phone"><?php echo implode($archiveimg); ?></td>
		<td class="center hidden-phone">
			<?php echo (int)$item->id; ?>
		</td>
		</tr>
  <?php
  }
  ?>
    </tbody>  
	</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</div></div></div></form>
	<?php
  $prayercenteradmin->PCfooter();
?>