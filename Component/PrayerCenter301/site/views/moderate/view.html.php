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
class PrayerCenterViewModerate extends JViewLegacy
{
	function display( $tpl = null)
	{
		global $pcConfig;
    $app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $pcConfig['config_rows'], 'int');
    if (empty($limitstart)) $limitstart = 0;
    if(isset($_GET['limitstart'])) $limitstart = JRequest::getVar('limitstart',null,'get','int');
    if (isset($_POST['sort']))
    {
    $sort = JRequest::getVar('sort',null,'post','int');
    } else $sort = "";
    if ($sort=="0" or $sort==""){
    $achecked = 'checked';}
    if  ($sort=="1"){
    $pchecked = 'checked';}
    else if  ($sort=="2"){
    $rchecked = 'checked';}
    //Model
    $model = JModelLegacy::getInstance('prayercenter', 'prayercentermodel');
		$newresults = $model->getNewData();
    $newtotal = $model->getNewTotal();
		// Set pathway information
		$this->assign('action', 	$uri->toString());
		$this->assignRef('config_show_page_headers',	$pcConfig['config_show_page_headers']);
    $pctitle = JText::_('PCTITLE');
		$this->assignRef('title', $pctitle);
    $pcintro = nl2br(JText::_('PCLISTINTRO'));
		$this->assignRef('intro',	$pcintro);
		$this->assignRef('config_show_tz',	$pcConfig['config_show_tz']);
		$this->assignRef('newresults',	$newresults);
		$this->assignRef('newtotal',	$newtotal);
		$this->assignRef('limit', $limit);
		$this->assignRef('limitstart', $limitstart);
		parent::display($tpl);
	}
}
?>