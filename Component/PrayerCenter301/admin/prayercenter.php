<?php
/**
* PrayerCenter Component for Joomla
* By Mike Leeper
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
if (!JFactory::getUser()->authorise('core.manage', 'com_prayercenter')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
require_once (JPATH_ADMINISTRATOR.'/components/com_prayercenter/controller.php');
require( JPATH_ADMINISTRATOR.'/components/com_prayercenter/helpers/admin_pc_includes.php' );
// require helper file
JLoader::register('PrayerCenterHelper', dirname(__FILE__) . '/helpers/prayercenter.php');
$controller	= JControllerLegacy::getInstance('PrayerCenter');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();