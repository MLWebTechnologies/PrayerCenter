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
class PrayerCenterViewList extends JViewLegacy
{
	function display( $tpl = null)
	{
		global $pcConfig, $pc_rights;
    $app = JFactory::getApplication();
    $achecked = "";
    $pchecked = "";
    $rchecked = "";
    $searchword = "";
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
    $access_gid =$user->get('gid');
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $pcConfig['config_rows'], 'int');
    if (empty($limitstart)) $limitstart = 0;
    if(isset($_REQUEST['limitstart'])) $limitstart = JRequest::getVar('limitstart',null,'request','int');
    if (isset($_POST['sort']))
    {
      $sort = JRequest::getVar('sort',null,'post','int');
    } else {
      $sort = 99;
    }
    if(isset($_POST['searchword']))
    {
      $searchword = JRequest::getVar( 'searchword', null, 'post', 'string' );
    } else {
      $searchword = "";
    }
    if(isset($_REQUEST['searchrequester']))
    {
      $searchrequester = JRequest::getVar( 'searchrequester', null, 'request', 'string' );
    } else {
      $searchrequester = "";
    }
    if(isset($_REQUEST['searchrequesterid']))
    {
      $searchrequesterid = JRequest::getVar( 'searchrequesterid', null, 'request', 'int' );
    } else {
      $searchrequesterid = "";
    }
    //Model
    $model = JModelLegacy::getInstance('prayercenter', 'prayercentermodel');
		$results = $model->getData($sort,$searchword,$searchrequester,$searchrequesterid);
    $total = $model->getTotal($sort,$searchword,$searchrequester,$searchrequesterid);
    $totalresults = $model->getTotalData();
    $config_allowed_plugins = preg_split('/[,]/',$pcConfig['config_allowed_plugins'], -1, PREG_SPLIT_NO_EMPTY);
		// Set pathway information
		$this->assign('action', 	$uri->toString());
    $pctitle = JText::_('PCTITLE');
		$this->assignRef('title', $pctitle);
    $pcintro = nl2br(JText::_('PCLISTINTRO'));
		$this->assignRef('intro',	$pcintro);
		$this->assignRef('config_show_page_headers',	$pcConfig['config_show_page_headers']);
		$this->assignRef('config_view_template',	$pcConfig['config_view_template']);
		$this->assignRef('config_show_tz',	$pcConfig['config_show_tz']);
		$this->assignRef('config_show_pdf',	$pcConfig['config_show_pdf']);
		$this->assignRef('config_show_print',	$pcConfig['config_show_print']);
		$this->assignRef('config_show_email',	$pcConfig['config_show_email']);
		$this->assignRef('config_show_date',	$pcConfig['config_show_date']);
		$this->assignRef('config_show_requester',	$pcConfig['config_show_requester']);
		$this->assignRef('config_show_bookmarks',	$pcConfig['config_show_bookmarks']);
		$this->assignRef('config_show_dwprint',	$pcConfig['config_show_dwprint']);
		$this->assignRef('config_use_admin_alert',	$pcConfig['config_use_admin_alert']);
		$this->assignRef('config_enable_plugins',	$pcConfig['config_enable_plugins']);
		$this->assignRef('config_allowed_plugins',	$config_allowed_plugins);
		$this->assignRef('config_use_wordfilter', $pcConfig['config_use_wordfilter']);
		$this->assignRef('config_cb',	$pcConfig['config_cb']);
		$this->assignRef('config_req_length', $pcConfig['config_req_length']);
		$this->assignRef('config_show_viewed',	$pcConfig['config_show_viewed']);
		$this->assignRef('config_show_commentlink',	$pcConfig['config_show_commentlink']);
		$this->assignRef('config_comments',	$pcConfig['config_comments']);
		$this->assignRef('results',	$results);
		$this->assignRef('totalresults', $totalresults);
		$this->assignRef('total',	$total);
		$this->assignRef('sort', $sort);
		$this->assignRef('limit', $limit);
		$this->assignRef('limitstart', $limitstart);
    $pcmoderate = $pc_rights->get('pc.moderate');
    $this->assignRef('moderate', $pcmoderate);
		parent::display($tpl);
	}
}
?>