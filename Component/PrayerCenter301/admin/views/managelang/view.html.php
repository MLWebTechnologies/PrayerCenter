<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class PrayerCenterViewManageLang extends JViewLegacy
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
		$this->addToolbar();
//  	$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
    JToolBarHelper::title( JText::_('PrayerCenter - Manage Languages') );
		JToolBarHelper::addNew( "addlang");
		$cb = JToolBar::getInstance('toolbar');
		$cb->appendButton( 'confirm', 'Do you wish to reset the English PrayerCenter language file to default settings?', 'undo', 'Reset', 'prayercenter.resetlang', false);
		JToolBarHelper::editList( "editlang");
		JToolBarHelper::deleteList( "Remove Language File(s)?", "prayercenter.deleteLangfile", 'Remove' );
    JToolBarHelper::help( 'language.help.html', true);
		JHtmlSidebar::setAction('index.php?option=com_prayercenter');
	}
}
?>