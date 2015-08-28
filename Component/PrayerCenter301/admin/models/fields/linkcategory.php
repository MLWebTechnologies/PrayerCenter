<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_livingword
 *
 * @copyright   Copyright (C) 2008 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('list');
/**
 * Supports an HTML select list of categories
 *
 * @package     Joomla.Administrator
 * @subpackage  com_livingword
 * @since       3.0
 */
class JFormFieldLinkCategory extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'LinkCategory';
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		$options = array();
		$db		= JFactory::getDbo();
    $query = "SELECT category AS text, category AS value FROM #__prayercenter_links AS a GROUP BY(a.category) ORDER BY a.category";
		// Get the options.
		$db->setQuery($query);
		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $db->getMessage());
		}
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}