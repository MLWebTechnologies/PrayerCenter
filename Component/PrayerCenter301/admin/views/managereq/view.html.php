<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class PrayerCenterViewManageReq extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	public function display( $tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->state		= $this->get('State');
  	$this->pagination	= $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
		foreach ($this->items as &$item) {
			$item->order_up = true;
			$item->order_dn = true;
		}
		$this->addToolbar();
  	$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
		JToolbarHelper::title(JText::_('PrayerCenter - Manage Requests'));
		JToolBarHelper::publishList("prayercenter.publish", 'Publish');
		JToolBarHelper::unpublishList("prayercenter.unpublish", 'Unpublish');
  	JToolBarHelper::archiveList("prayercenter.archive", 'Archive');
		JToolBarHelper::unarchiveList("prayercenter.unarchive", 'Unarchive');
		JToolBarHelper::editList("edit", 'Edit');
		JToolBarHelper::deleteList( "Remove Request(s)?", "prayercenter.remove_req", 'Remove' );
		$cb = JToolBar::getInstance('toolbar');
		$cb->appendButton( 'confirm', 'Do you wish to proceed with the purging of old prayer requests?  Requests that are older than the request/archive retention settings will be removed from the PrayerCenter database table.', 'apply', 'Purge', 'prayercenter.purge', false);
		JHtmlSidebar::setAction('index.php?option=com_prayercenter');
	}
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.requester' => JText::_('Requester'),
			'a.request' => JText::_('Request'),
			'a.publishstate' => JText::_('JPUBLISHED'),
			'a.archivestate' => JText::_('Archive State'),
			'a.displaystate' => JText::_('Display State'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
	public function display2( $tpl = null)
	{
  $app = JFactory::getApplication('administrator');
  jimport('joomla.application.component.modellist');
  $version = new JVersion();
 	$db	=& JFactory::getDBO();
  if( (real)$version->RELEASE == 1.5 ) {
  	global $mainframe, $option;
  	$filter_order	= $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'id',	'cmd' );
  	$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'DESC', 'word' );
  	$search = $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
  	$limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
  	$limitstart	= $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
  } elseif( (real)$version->RELEASE >= 1.6 ){
    $modellist = new JModelList();
    $option = JRequest::getCmd('option');
  	$filter_order = $app->getUserStateFromRequest($option.'.ordercol', 'filter_order', 'id', 'cmd');
  	$modellist->setState('list.ordering', $filter_order);
  	$filter_order_Dir = $app->getUserStateFromRequest($option.'.orderdirn', 'filter_order_Dir', 'DESC', 'word');
  	$modellist->setState('list.direction', $filter_order_Dir);
  	$search = $modellist->getUserStateFromRequest( $option.'.filter.search', 'filter_search', '', 'string' );
  	$modellist->setState('filter.search', $search);
  	$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
  	$modellist->setState('list.limit', $limit);
  	$value = $app->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0);
  	$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
  	$modellist->setState('list.start', $limitstart);
  }
	$search = JString::strtolower( $search );
	$where = array();
	if ( $search ) {
		$where[] = 'request LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    $whare[] .= ' OR requester LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
	}
	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' OR ', $where ) : '' );
	if ($filter_order == 'ordering'){
		$orderby 	= ' ORDER BY id';
	} else {
		$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', id';
	}
	$db->setQuery( "SELECT COUNT(*) FROM #__prayercenter AS a $where" );
	$total = $db->loadResult();
	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );
	$sql = "SELECT *, DATE_FORMAT(CONCAT_WS(' ',date,time),'%Y-%m-%d %T') AS datetime"
	. "\nFROM #__prayercenter"
	. $where
	. $orderby
  ;
	$db->setQuery( $sql, $pageNav->limitstart, $pageNav->limit );
	$rows = $db->loadObjectList();
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']	= $filter_order;
	$lists['search'] = $search;
		$this->assignRef('rows', $rows);
		$this->assignRef('pageNav', $pageNav);
		$this->assignRef('option', $option);
		$this->assignRef('lists', $lists);
		$this->assignRef('JVersion', $version);
		parent::display($tpl);
	}
}
?>