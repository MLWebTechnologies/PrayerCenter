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
class PrayerCenterViewLinks extends JViewLegacy
{
	function display( $tpl = null)
	{
		global $pcConfig;
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $db		= JFactory::getDBO();
    $query = "SELECT a.name,a.url,a.descrip,a.alias,a.catid,c.title as category,c.lft FROM #__prayercenter_links AS a LEFT JOIN #__categories AS c ON (c.id = a.catid) WHERE a.published='1' ORDER BY c.lft,a.ordering";
    $db->setQuery($query);
    $link_array = $db->loadObjectList();
		// Set pathway information
		$this->assignRef('config_show_page_headers',	$pcConfig['config_show_page_headers']);
    $this->assignRef('link_array',$link_array);
    $this->assignRef('config_two_columns', $pcConfig['config_two_column']);
    $this->assignRef('config_show_linkcats', $pcConfig['config_show_linkcats']);
    $this->assignRef('config_use_gb', $pcConfig['config_use_gb']);
    $pctitle = JText::_('PCTITLE');
		$this->assignRef('title', $pctitle);
    $pcintro = nl2br(JText::_('PCLISTINTRO'));
		$this->assignRef('intro',	$pcintro);
		parent::display($tpl);
	}
}