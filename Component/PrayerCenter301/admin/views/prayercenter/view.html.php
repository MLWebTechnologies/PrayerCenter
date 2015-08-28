<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class PrayerCenterViewPrayerCenter extends JViewLegacy
{
	function display( $tpl = null)
	{
    $version = new JVersion();
		$this->assignRef('JVersion', $version);
		$this->addToolbar();
  	$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
		JToolbarHelper::title(JText::_('PrayerCenter - Control Panel'));
		JToolbarHelper::preferences('com_prayercenter');
		JHtmlSidebar::setAction('index.php?option=com_prayercenter');
//		$lb = & JToolBar::getInstance('toolbar');
//		$lb->appendButton( 'Popup', 'help', 'Help', 'index.php?option=com_prayercenter&amp;task=pchelp&amp;pop=1&amp;tmpl=component', 1000, 640 );
	}
}
?>