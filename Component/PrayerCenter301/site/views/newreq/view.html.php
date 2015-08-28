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
class PrayerCenterViewNewReq extends JViewLegacy
{
	function display( $tpl = null)
	{
		global $pcConfig;
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
		$uri = JFactory::getURI();
		// Set pathway information
		$this->assign('action', 	$uri->toString());
    $pctitle = JText::_('PCTITLE');
		$this->assignRef('title', $pctitle);
		$this->assignRef('config_show_page_headers',	$pcConfig['config_show_page_headers']);
    $pcdirections = nl2br(JText::_('PCREQDIRECTIONS'));
		$this->assignRef('directions', $pcdirections);
    $this->assignRef('config_editor', $pcConfig['config_editor']);
    $this->assignRef('config_show_xtd_buttons', $pcConfig['config_show_xtd_buttons']);
    $this->assignRef('config_cols', $pcConfig['config_cols']);
    $this->assignRef('config_captcha', $pcConfig['config_captcha']);
    $this->assignRef('config_use_admin_alert', $pcConfig['config_use_admin_alert']);
    $this->assignRef('show_priv_option', $pcConfig['config_show_priv_option']);
    $this->assignRef('show_sub_praise', $pcConfig['config_show_sub_praise']);
    $this->assignRef('email_option', $pcConfig['config_email_option']);
    $this->assignRef('config_captcha_bypass', $pcConfig['config_captcha_bypass_4member']);
		parent::display($tpl);
	}
}
?>