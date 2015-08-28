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
class PrayerCenterViewShowReq extends JViewLegacy
{
	function display( $tpl = null)
	{
		global $pcConfig, $prayercenter;
    $eid = JRequest::getVar('id',null,'request','int');
    $pop = JRequest::getVar('pop',null,'request','int');
    $prt = JRequest::getVar('prt',null,'request','int');
    $prv = JRequest::getVar('prv',null,'request','int');
    $sessionid = JRequest::getVar('sessionid',null,'request','string');
    $eid = JFilterOutput::cleanText($eid);
    $pop = JFilterOutput::cleanText($pop);
    $prt = JFilterOutput::cleanText($prt);
    $prv = JFilterOutput::cleanText($prv);
    $sessionid = JFilterOutput::cleanText($sessionid);
    if(($prv && is_numeric($eid) && $prayercenter->PCSIDvalidate($sessionid)) || !$prv){
      //Model
  		$db = JFactory::getDBO();
      $model = JModelLegacy::getInstance('prayercenter', 'prayercentermodel');
  		$results = $model->getEditData($eid);
      if(!$pop || ($pop && $prv)) {
        $session = JFactory::getSession();
        $reqid = 'pc_req_viewed'.$eid;
        if(!$session->has($reqid)){
      		$query = 'UPDATE #__prayercenter SET hits=hits+1 WHERE id='.(int)$eid;
      		$db->setQuery( $query );
      		$db->query();
          $session->set($reqid, '1');
        }
      }
    }
    $config_dformat = $pcConfig['config_date_format'];
    if($config_dformat == 0) $config_date_format = 'm-d-Y';
    if($config_dformat == 1) $config_date_format = 'd-m-Y';
    if($config_dformat == 2) $config_date_format = 'Y-m-d';
    $config_tformat = $pcConfig['config_time_format'];
    if($config_tformat == 0) $config_time_format = 'h:i:s A';
    if($config_tformat == 1) $config_time_format = 'H:i:s';
    $config_allowed_plugins = preg_split('/[,]/',$pcConfig['config_allowed_plugins'], -1, PREG_SPLIT_NO_EMPTY);
		// Set pathway information
    $pctitle = JText::_('PCTITLE');
		$this->assignRef('title', $pctitle);
    $pcintro = nl2br(JText::_('PCLISTINTRO'));
		$this->assignRef('intro',	$pcintro);
		$this->assignRef('config_show_page_headers',	$pcConfig['config_show_page_headers']);
		$this->assignRef('config_date_format',	$config_date_format);
		$this->assignRef('config_time_format',	$config_time_format);
		$this->assignRef('config_show_print',	$pcConfig['config_show_print']);
		$this->assignRef('config_show_bookmarks',	$pcConfig['config_show_bookmarks']);
		$this->assignRef('config_show_requester',	$pcConfig['config_show_requester']);
		$this->assignRef('config_show_translate',	$pcConfig['config_show_translate']);
		$this->assignRef('config_show_comprofile',	$pcConfig['config_show_comprofile']);
		$this->assignRef('config_enable_plugins',	$pcConfig['config_enable_plugins']);
		$this->assignRef('config_allowed_plugins',	$config_allowed_plugins);
		$this->assignRef('config_use_gb',	$pcConfig['config_use_gb']);
		$this->assignRef('config_use_wordfilter',	$pcConfig['config_use_wordfilter']);
		$this->assignRef('config_community',	$pcConfig['config_community']);
    $result = $results[0];
		$this->assignRef('results',	$result);
		$this->assignRef('pop',	$pop);
		$this->assignRef('prt',	$prt);
		$this->assignRef('prv',	$prv);
		$this->assignRef('sessionid', $sessionid);
		$this->assignRef('eid', $eid);
		parent::display($tpl);
	}
}
?>