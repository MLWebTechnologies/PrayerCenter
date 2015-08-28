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
class JFormFieldLinkTarget extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'LinkTarget';
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		$options = array();
    $tarray = array(
          1 => array ('value' => '0', 'text' => 'Parent Window With Browser Navigation'),
          2 => array ('value' => '1', 'text' => 'New Window With Browser Navigation'),
          3 => array ('value' => '2', 'text' => 'New Window Without Browser Navigation')
          );
		$options = array_merge(parent::getOptions(), $tarray);
		return $options;
	}
}