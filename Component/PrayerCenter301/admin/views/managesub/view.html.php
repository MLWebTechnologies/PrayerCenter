<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class PrayerCenterViewManageSub extends JViewLegacy
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
  	$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
		JToolbarHelper::title(JText::_('PrayerCenter - Manage Subscribers'));
		JToolBarHelper::publishList("prayercenter.approve", 'Approve');
		JToolBarHelper::unpublishList("prayercenter.unapprove", 'Unapprove');
		JToolBarHelper::deleteList( "Remove Subscriber(s)?", "prayercenter.remove_sub", 'Remove' );
		JHtmlSidebar::setAction('index.php?option=com_prayercenter');
	}
	protected function getSortFields()
	{
		return array(
			'a.email' => JText::_('Email'),
			'a.date' => JText::_('Date'),
			'a.approved' => JText::_('Approved'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
?>