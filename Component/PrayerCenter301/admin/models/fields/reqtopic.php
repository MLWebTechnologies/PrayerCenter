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
class JFormFieldReqTopic extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'ReqTopic';
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getOptions()
	{
    global $prayercenteradmin;
    $newtopicarray = $prayercenteradmin->PCgetTopics();
		$options = array();
    $topicarray = array();
    $topicarray[0]['value'] = "";
    $topicarray[0]['text'] = JText::_('PCSELECTTOPIC');
    for($i=1;$i<count($newtopicarray)+1;$i++){
       $topicarray[$i]['value'] = $newtopicarray[$i]['val'];
       $topicarray[$i]['text'] = $newtopicarray[$i]['text'];
    }
		$options = array_merge(parent::getOptions(), $topicarray);
		return $options;
	}
}
?>