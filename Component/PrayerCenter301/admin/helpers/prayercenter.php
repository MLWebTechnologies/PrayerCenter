  <?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_prayercenter
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
/**
 * Weblinks helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_prayercenter
 * @since       1.6
 */
class PrayerCenterHelper
{
	public static $extension = 'com_prayercenter';
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string	The name of the active view.
	 * @since   1.6
	 */
	public static function addSubmenu($vName = 'prayercenter')
	{
		JSubMenuHelper::addEntry(
			JText::_('CPanel'),
			'index.php?option=com_prayercenter',
			$vName == 'prayercenter'
		);
		JSubMenuHelper::addEntry(
			JText::_('Requests'),
			'index.php?option=com_prayercenter&task=manage_req',
			$vName == 'managereq'
		);
		JSubMenuHelper::addEntry(
			JText::_('Subscribers'),
			'index.php?option=com_prayercenter&task=manage_sub',
			$vName == 'managesub'
		);
		JSubMenuHelper::addEntry(
			JText::_('CSS'),
			'index.php?option=com_prayercenter&task=manage_css',
			$vName == 'managecss'
		);
		JSubMenuHelper::addEntry(
			JText::_('Files'),
			'index.php?option=com_prayercenter&task=manage_files',
			$vName == 'managefiles'
		);
		JSubMenuHelper::addEntry(
			JText::_('Language Files'),
			'index.php?option=com_prayercenter&task=manage_lang',
			$vName == 'managelang'
		);
		JSubMenuHelper::addEntry(
			JText::_('Devotionals'),
			'index.php?option=com_prayercenter&task=manage_dev',
			$vName == 'managedevotions'
		);
		JSubMenuHelper::addEntry(
			JText::_('Links'),
			'index.php?option=com_prayercenter&task=manage_link',
			$vName == 'managelink'
		);
		JSubMenuHelper::addEntry(
			JText::_('Categories'),
			'index.php?option=com_categories&extension=com_prayercenter',
			$vName == 'categories'
		);
		if ($vName == 'categories')
		{
			JToolbarHelper::title(
				JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('com_prayercenter')),
				'prayercenter-categories');
		}
	}
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  The category ID.
	 * @return  JObject
	 * @since   1.6
	 */
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		if (empty($categoryId))
		{
			$assetName = 'com_prayercenter';
			$level = 'component';
		}
		else
		{
			$assetName = 'com_prayercenter.category.'.(int) $categoryId;
			$level = 'category';
		}
		$actions = JAccess::getActions('com_prayercenter', $level);
		foreach ($actions as $action)
		{
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}
		return $result;
	}
}