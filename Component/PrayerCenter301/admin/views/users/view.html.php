<?php
/**
 * @package		PrayerCenter
 * @copyright	Copyright (C) 2006 - 2012 MLWebTechnologies All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
class PrayerCenterViewUsers extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	public function display($tpl = null)
	{
  $app = JFactory::getApplication('administrator');
  jimport('joomla.application.component.modellist');
 	$db	= JFactory::getDBO();
  $modellist = new JModelList();
  $option = JRequest::getCmd('option');
  $filter_order = $app->getUserStateFromRequest($option.'.ordercol', 'filter_order', 'a.name', 'cmd');
  $modellist->setState('list.ordering', $filter_order);
  $filter_order_Dir = $app->getUserStateFromRequest($option.'.orderdirn', 'filter_order_Dir', 'ASC', 'word');
  $modellist->setState('list.direction', $filter_order_Dir);
  $search = $app->getUserStateFromRequest( $option.'.filter.search', 'filter_search', '', 'string' );
  $modellist->setState('filter.search', $search);
  $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
  $modellist->setState('list.limit', $limit);
  $value = $app->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0);
  $limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
  $modellist->setState('list.start', $limitstart);
  $groupId = $app->getUserStateFromRequest($option.'.filter.group_id', 'filter_group_id', '', 'int');
  $modellist->setState('filter.group_id', $groupId);
  $groups = $app->getUserStateFromRequest($option.'.filter.groups', 'filter_groups', '', 'string');
  $modellist->setState('filter.groups', $groups);
	$search = JString::strtolower( $search );
	$where = array();
	if ($filter_order == 'ordering'){
		$orderby 	= ' ORDER BY a.name';
	} else {
		$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;
	}
	$db =& JFactory::getDBO();
	$query = "SELECT a.* FROM #__users AS a";
	if($groupId || !empty($groups)) {
		$query .= ' LEFT JOIN #__user_usergroup_map AS map2 ON (map2.user_id=a.id)';
		if ($groupId) {
			$query .= ' WHERE map2.group_id='.(int)$groupId;
		}
		if (!empty($groups)) {
			$query .= ' WHERE map2.group_id IN ('.implode(',', $groups).')';
		}
	}
	if(!$groupId && empty($groups)) { 
    $query .= ' WHERE';
  } else {
    $query .= ' AND';
  }
	$query .= ' a.block=0';
	$query .= ' AND LENGTH(a.activation)<=1';
	$searches = array();
	if($search) {
		$searches[] = '(a.name LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    $searches[] = 'a.username LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    $searches[] = 'a.email LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ).')';
	}
	$wheresearch = (count($searches) ? ' AND ' . implode( ' OR ', $searches ) : '' );
  $query .= ' '.$wheresearch.$orderby;
	$db->setQuery($query);
	$total = count($db->loadObjectList());
	jimport('joomla.html.pagination');
	$this->pagination = new JPagination( $total, $limitstart, $limit );
  $db->setQuery($query, $this->pagination->limitstart, $this->pagination->limit);
  $this->items = $db->loadObjectList();
	$userIds = array();
	foreach ($this->items as $item)
	{
		$userIds[] = (int) $item->id;
		$item->group_count = 0;
		$item->group_names = '';
	}
	$query	= $db->getQuery(true);
	$query->select('map.user_id, COUNT(map.group_id) AS group_count')
		->from('#__user_usergroup_map AS map')
		->where('map.user_id IN ('.implode(',', $userIds).')')
		->group('map.user_id')
		->select('GROUP_CONCAT(g2.title SEPARATOR '.$db->Quote("\n").') AS group_names')
		->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');
	$db->setQuery($query);
	$userGroups = $db->loadObjectList('user_id');
	foreach ($this->items as &$item)
	{
		if (isset($userGroups[$item->id])) {
			$item->group_count = $userGroups[$item->id]->group_count;
			$item->group_names = $userGroups[$item->id]->group_names;
		}
	}
	if (count($errors = $this->get('Errors'))) {
		JError::raiseError(500, implode("\n", $errors));
		return false;
	}
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']	= $filter_order;
	$lists['search'] = $search;
  $this->groupId = $groupId;
	$this->assignRef('option', $option);
	$this->assignRef('lists', $lists);
	parent::display($tpl);
 }
}