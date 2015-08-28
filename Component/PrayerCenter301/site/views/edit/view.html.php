<?php
/* *************************************************************************************
Title          PrayerCenter Component for Joomla
Author         Mike Leeper
License        This program is free software: you can redistribute it and/or modify
               it under the terms of the GNU General Public License as published by
               the Free Software Foundation, either version 3 of the License, or
               (at your option) any later version.
               This program is distributed in the hope that it will be useful,
               but WITHOUT ANY WARRANTY; without even the implied warranty of
               MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
               GNU General Public License for more details.
               You should have received a copy of the GNU General Public License
               along with this program.  If not, see <http://www.gnu.org/licenses/>.
Copyright      2006-2014 - Mike Leeper (MLWebTechnologies) 
****************************************************************************************
No direct access*/
defined( '_JEXEC' ) or die( 'Restricted access' );
class PrayerCenterViewEdit extends JViewLegacy
{
	function display( $tpl = null)
	{
		global $pcConfig;
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $editblock = 0;
    $eid = JRequest::getVar('id',null,'request','int');
    $eid = JFilterOutput::cleanText($eid);
    //Model
    $model = JModelLegacy::getInstance('prayercenter', 'prayercentermodel');
		$edit = $model->getEditData($eid);
		$moduser = $model->getCOData($edit[0]->checked_out);
    	if ($model->isCheckedOut( $user->get('id') )) {
    		$coerror = JText::_('PCCHECKEDOUT').' '.$moduser[0]->name;
    			JError::raiseWarning(0, $coerror );
    			$editblock = 1;
    	}
    	else {
      if ( $eid ) {
    		$model->checkout( $user->get('id') );
        }
      }
		// Set pathway information
		$this->assign('action', 	$uri->toString());
    $title =  JText::_('PCTITLE');
		$this->assignRef('title', $title);
    $intro = nl2br(JText::_('PCLISTINTRO'));
		$this->assignRef('intro', $intro);
		$this->assignRef('config_show_page_headers',	$pcConfig['config_show_page_headers']);
    $this->assignRef('config_show_xtd_buttons', $pcConfig['config_show_xtd_buttons']);
    $this->assignRef('config_cols', $pcConfig['config_cols']);
		$this->assignRef('config_show_tz',	$pcConfig['config_show_tz']);
		$this->assignRef('config_editor',	$pcConfig['config_editor']);
		$this->assignRef('config_use_gb',	$pcConfig['config_use_gb']);
		$this->assignRef('editreq',	$edit[0]);
		$this->assignRef('editblock',	$editblock);
		parent::display($tpl);
	}
}
?>