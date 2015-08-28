<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
class PrayerCenterModelManageSub extends JModelList
{
	/**
	 * @var int
	 */
	var $_id = null;
	/**
	 * @var array
	 */
	var $_data = null;
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();
		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);
		if($edit)
			$this->setId((int)$array[0]);
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'email', 'email',
        'date', 'a.date',
        'approved', 'a.approved'
			);
    }
		parent::__construct($config);
	}
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');
  	$search = $this->getUserStateFromRequest( $this->context.'.filter.search', 'filter_search' );
  	$this->setState('filter.search', $search);
		$approved = $this->getUserStateFromRequest($this->context.'.filter.email', 'filter_approved', '');
		$this->setState('filter.approved', $approved);
		// List state information.
		parent::populateState('id', 'asc');
	}
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.email, a.date, a.approved'
			)
		);
		$query->from('#__prayercenter_subscribe AS a');
		// Join over the users for the checked out user.
//		$query->select('uc.name AS name');
//	$query->join('LEFT', '#__users AS uc ON uc.id=a.userid');
		// Join over the asset groups.
//		$query->select('ag.title AS access_level');
//		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// Filter by access level.
//		if ($access = $this->getState('filter.access')) {
//			$query->where('a.access = ' . (int) $access);
//		}
		// Implement View Level Access
//		if (!$user->authorise('core.admin'))
//		{
//			$groups	= implode(',', $user->getAuthorisedViewLevels());
//			$query->where('a.access IN ('.$groups.')');
//		}
		// Filter by published state
		$approved = $this->getState('filter.approved');
 	if (is_numeric($approved)) {
			$query->where('a.approved = ' . (int) $approved);
		}
		elseif ($approved === '') {
			$query->where('(a.approved = 0 OR a.approved = 1)');
		}
		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			elseif (stripos($search, 'email:') === 0) {
				$search = $db->Quote('%'.$db->escape(substr($search, 7), true).'%');
				$query->where('(a.email LIKE '.$search.')');
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.date LIKE '.$search.')');
			}
		}
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}
}