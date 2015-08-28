<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class PrayerCenterViewManageCss extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	public function display( $tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->state		= $this->get('State');
  	$this->pagination	= $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
//		foreach ($this->items as &$item) {
//			$item->order_up = true;
//			$item->order_dn = true;
//		}
		$this->addToolbar();
//  	$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
    JToolBarHelper::title( 'PrayerCenter - Manage CSS' );
		JToolBarHelper::apply('prayercenter.savecss');
		$cb = JToolBar::getInstance('toolbar');
		$cb->appendButton( 'confirm', 'Do you wish to reset the PrayerCenter CSS file to default settings?', 'undo', 'Reset', 'prayercenter.resetcss', false);
		JToolBarHelper::cancel('prayercenter.cancelsettings' );
		JHtmlSidebar::setAction('index.php?option=com_prayercenter');
	}
}
?>