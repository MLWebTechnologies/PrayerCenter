<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
class PrayerCenterModelManageReq extends JModelList
{
	/**
	 * @var int
	 */
	var $_id = null;
	/**
	 * @var array
	 */
	var $_data = null;
	var $_forms = null;
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'requesterid', 'a.requesterid',
        'requester', 'a.requester',
        'request', 'a.request',
				'date', 'a.date',
				'time', 'a.time',
				'publishstate', 'a.publishstate',
				'archivestate', 'a.archivestate',
				'displaystate', 'a.displaystate',
				'sendto', 'a.sendto',
        'email', 'a.email',
        'adminsendto', 'a.adminsendto',
				'checked_out_time', 'a.checked_out_time',
				'checked_out', 'a.checked_out',
				'sessionid', 'a.sessionid',
				'title', 'a.title',
				'topic', 'a.topic',
				'hits', 'a.hits'
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
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
		// List state information.
		parent::populateState('a.id', 'desc');
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
				'a.id, a.requesterid, a.requester, a.request, a.date, a.time,' .
				'a.publishstate, a.archivestate, a.displaystate, a.sendto, a.email, a.adminsendto,' .
				'a.checked_out_time, a.checked_out, a.sessionid, a.title, a.topic, a.hits'
			)
		);
		$query->select("DATE_FORMAT(CONCAT_WS(' ',a.date,a.time),'%Y-%m-%d %T') AS datetime");
		$query->from('#__prayercenter AS a');
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
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
		$published = $this->getState('filter.publishstate');
		if (is_numeric($published)) {
			$query->where('a.publishstate = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(a.publishstate = 0 OR a.publishstate = 1)');
		}
		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			elseif (stripos($search, 'name:') === 0) {
				$search = $db->Quote('%'.$db->escape(substr($search, 7), true).'%');
				$query->where('(uc.name LIKE '.$search.' OR uc.username LIKE '.$search.')');
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.requester LIKE '.$search.')');
			}
		}
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}
	/**
	 * Method to checkin a row.
	 *
	 * @param   integer  $pk  The numeric id of the primary key.
	 *
	 * @return  boolean  False on failure or error, true otherwise.
	 *
	 * @since   12.2
	 */
	public function checkin($pk = null)
	{
		// Only attempt to check the row in if it exists.
		if ($pk)
		{
			$user = JFactory::getUser();
			// Get an instance of the row to checkin.
			$table = $this->getTable('PrayerCenter','Table');
			if (!$table->load())
			{
				$this->setError($table->getError());
				return false;
			}
			// Check if this is the user having previously checked out the row.
			if ($table->checked_out > 0 && $table->checked_out != $user->get('id') && !$user->authorise('core.admin', 'com_checkin'))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_CHECKIN_USER_MISMATCH'));
				return false;
			}
			// Attempt to check the row in.
			if (!$table->checkin($pk))
			{
				$this->setError($table->getError());
				return false;
			}
		}
		return true;
	}
}
?>