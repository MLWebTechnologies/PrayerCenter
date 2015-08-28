<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class PrayerCenterViewUtilities extends JViewLegacy
{
	function display( $tpl = null)
	{
		$this->addToolbar();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
    global $prayercenteradmin;
    JToolBarHelper::title( 'PrayerCenter - Utilities' );
	}
}
?>