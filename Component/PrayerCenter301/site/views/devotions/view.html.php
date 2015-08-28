<?php
/* *************************************************************************************
Title          PrayerCenter Component for Joomla
Author         Mike Leeper
Enhancements   Douglas Machado 
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
class PrayerCenterViewDevotions extends JViewLegacy
{
	function display( $tpl = null)
	{
		global $pcConfig;
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $db		= JFactory::getDBO();
    $db->setQuery("SELECT feed FROM #__prayercenter_devotions WHERE published='1' ORDER BY ordering");
    $feed_array = $db->loadObjectList();
		// Set pathway information
    $this->assignRef('feed_array',$feed_array);
		$this->assignRef('config_show_page_headers',	$pcConfig['config_show_page_headers']);
    $this->assignRef('config_update_time',$pcConfig['config_update_time']);
    $this->assignRef('config_enable_cache',$pcConfig['config_enable_cache']);
    $this->assignRef('config_update_time',$pcConfig['config_update_time']);
    $this->assignRef('config_feed_image',$pcConfig['config_feed_image']);
    $this->assignRef('config_feed_descr',$pcConfig['config_feed_descr']);
    $this->assignRef('config_item_descr',$pcConfig['config_item_descr']);
    $this->assignRef('config_word_count',$pcConfig['config_word_count']);
    $this->assignRef('config_item_limit',$pcConfig['config_item_limit']);
    $this->assignRef('config_use_gb', $pcConfig['config_use_gb']);
    $pctitle = JText::_('PCTITLE');
		$this->assignRef('title', $pctitle);
    $pcintro = nl2br(JText::_('PCLISTINTRO'));
		$this->assignRef('intro',	$pcintro);
		parent::display($tpl);
	}
}