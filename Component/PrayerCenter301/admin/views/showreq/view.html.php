<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class PrayerCenterViewShowReq extends JView
{
	function display( $tpl = null)
	{
  	global $option;
    $version = new JVersion();
    $lang =& Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $eid = JRequest::getVar('id',null,'get','int');
    $db		= JFactory::getDBO();
    $db->setQuery("SELECT * FROM #__prayercenter WHERE id='".$eid."'");
    $erow = $db->loadObjectList();
		$this->assignRef('erow', $erow);
		$this->assignRef('JVersion', $version);
		parent::display($tpl);
	}
}
?>