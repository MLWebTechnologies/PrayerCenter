<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class PrayerCenterViewEditLang extends JViewLegacy
{
	function display( $tpl = null)
	{
    $editor = JFactory::getEditor('none');
  	$edit	= JRequest::getVar('edit',true);
    $files = JRequest::getVar( 'cid', array(0), '', 'array' );
    if($edit){
    $file = $files[0];
    } else {
    $file = "";
    }
    $this->assignRef('edit', $edit);
    $this->assignRef('editor', $editor);
    $this->assignRef('file', $file);
		$this->assignRef('option', $option);
		$this->addToolbar();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
    global $prayercenteradmin;
		JFactory::getApplication()->input->set('hidemainmenu', true);
    JToolBarHelper::title( 'PrayerCenter - Edit Language' );
    JToolBarHelper::help( 'language.help.html', true);
  	JToolBarHelper::save('prayercenter.savelang', 'Save' );
  	if (!$this->edit)  {
  		JToolBarHelper::cancel('prayercenter.canceleditlang');
  	} else {
  		JToolBarHelper::cancel( 'prayercenter.canceleditlang', 'Close' );
  	}
	}
}
?>