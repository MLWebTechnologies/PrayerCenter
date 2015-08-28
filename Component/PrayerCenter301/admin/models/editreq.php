<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_prayercenter
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
class PrayerCenterModelEditReq extends JModelAdmin
{
	protected $text_prefix = 'COM_PRAYERCENTER';
	protected function populateState()
	{
		parent::populateState();
		$input = JFactory::getApplication()->input;
		$user = JFactory::getUser();
		$this->setState('user.id', $user->get('id'));
//		$edit = $input->getVar('edit');
  	$edit	= JRequest::getVar('edit',true);
		$this->setState('edit', $edit);
		$cid = $input->getVar('cid');
		$this->setState('id', $cid[0]);
	}
	public function getTable($type = 'EditReq', $prefix = 'PrayerCenterTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();
		$form = $this->loadForm('com_prayercenter.editreq', 'request', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	protected function loadFormData()
	{
    $id = $this->getState('id');
    $edit = $this->getState('edit');
    $db =& JFactory::getDBO();
    if($edit){
    	$query = "SELECT * FROM #__prayercenter"
    	. "\n WHERE id=".$id.""
    	;
      $db->setQuery($query);
      $data = $db->loadObject();
      $data->requester = html_entity_decode($data->requester,ENT_COMPAT,'UTF-8');      
    } else {
			$data->id = 0;
			$data->name = "";
			$data->feed = "";
      $data->category = "";
			$data->published = 1;
			$data->ordering 	= 0;
    }
//		$data = JFactory::getApplication()->getUserState('com_prayercenter.edit.editplan.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);
		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}
		if (empty($table->id))
		{
			// Set the values
			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__prayercenter');
				$max = $db->loadResult();
				$table->ordering = $max + 1;
			}
			else
			{
				// Set the values
				$table->modified	= $date->toSql();
				$table->modified_by	= $user->get('id');
			}
			// Increment the content version number.
			$table->version++;
		}
	}
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'category = '.(int) $table->category;
		return $condition;
	}
}