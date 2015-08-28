<?php
/**
* @version		$Id: pc_plugin_class.php 17261 2010-08-25 15:06:51Z ml $
* @package		PrayerCenter
* @subpackage	Plugin Class
* @copyright	Copyright (C) 2006 - 2014 MLWebTechnologies. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
global $pcConfig;
/**
* PC Plugin class
*
*/
class PCPluginHelper
{
	function isEnabled( $type, $plugin = null )
	{
		$path	= JPATH_ROOT.'/administrator/components/com_prayercenter/plugins/'.$type.'/'.$plugin;
		$result = file_exists( $path ) ? true : false;
		return $result;
	}
	function importPlugin( $type, $plugin = null )
	{
		$path	= JPATH_ROOT.'/administrator/components/com_prayercenter/plugins/'.$type.'/'.$plugin;
    $checkpath = PCPluginHelper::isEnabled($type, $plugin);
		if($checkpath){
			require_once( $path );
			return true;
		} else {
			return false;
		}
	}
  function admin_private_messaging($newrequesterid, $newrequester, $newrequest, $newemail, $lastId, $sessionid, $sendpriv)
	 {
    global $db, $pcConfig, $now, $config, $senderid, $prayercenteradmin;
    $time = null;
    $this->pcpmsloadvars($newrequesterid);
    $lang =& JFactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $db		=& JFactory::getDBO();
    $livesite = JURI::base();
    $app =& JFactory::getApplication();
    $sitename = $app->getCfg( 'sitename' );
    $sender = JText::_('PCTITLE');
    $config_use_admin_alert = $pcConfig['config_use_admin_alert'];
    $config_email_inc_req = $pcConfig['config_email_inc_req'];
    $config_email_request = $pcConfig['config_email_request'];
    $config_moderator_list = trim($pcConfig['config_moderator_user_list']);
    $config_moderator_list = strip_tags($config_moderator_list);
    $moderatorArray = preg_split('/[,]/',$config_moderator_list, -1, PREG_SPLIT_NO_EMPTY);
    $link = $livesite.'index.php?option=com_prayercenter&task=confirm_adm&id='.$lastId.'&sessionid='.$sessionid;
		$slink = '<a href="'.$livesite.'" target="_blank">'.$sitename.'</a>';
    $clink = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
    $prayerrequest = htmlentities(JText::_('PCTITLE'),ENT_COMPAT,'UTF-8');
    $sendpriv ? $config_email_message = $this->PCkeephtml(JText::_('APPROVEEMAILMSG')) : $config_email_message = $this->PCkeephtml(JText::_('APPROVEEMAILNOMSG'));
    $approver_name = htmlentities(JText::_('PCAPPROVERNAME'),ENT_COMPAT,'UTF-8');
    $config_email_subject = $this->PCkeephtml(JText::_('APPROVEEMAILSUBJECT'));  
   	$subject = sprintf( $config_email_subject, $sitename );
    if ($newrequester == JText::_('USRLANONUSER')) {
      $newrequester = strtolower($newrequester);
      }
    if($newemail){
      $newrequester = $newrequester.' ('.$newemail.')';
      }
    if(!$sendpriv){
      $private = JText::_('PCPRIVATE').' ';
    } else {
      $private = "";
    }
    if ($config_use_admin_alert == 2) {
      $showrecips = $prayercenteradmin->PCgetAdminData();
    }
    elseif ($config_use_admin_alert == 3){
      $showrecips = array();
      foreach ($moderatorArray as $mod){
        $mod = strtolower(trim($mod));
        preg_match('#(\d+)[-]#',$mod, $matches);
        $modquery = "SELECT id,name,username,email FROM #__users WHERE id=".$matches[1]."";
        $db->setQuery( $modquery );
        $showrecipsq = $db->loadObjectList();
        if(is_array($showrecipsq) && !empty($showrecipsq)){
          $showrecips[] = $showrecipsq[0];
        } elseif(!empty($showrecipsq)) $showrecips[] = $showrecipsq; 
     }
    }
    elseif ($config_use_admin_alert == 4){
    $showrecips1 = $prayercenteradmin->PCgetAdminData();
    $showrecips2 = array();
    foreach ($moderatorArray as $mod){
    $mod = strtolower(trim($mod));
    preg_match('#(\d+)[-]#',$mod, $matches);
    $modquery = "SELECT id,name,username,email FROM #__users WHERE id=".(int)$matches[1]."";
    $db->setQuery( $modquery );
    $showrecipsq = $db->loadObjectList();
      if(is_array($showrecipsq) && !empty($showrecipsq)){
      $showrecips2[] = $showrecipsq[0];
      }
    elseif(!empty($showrecipsq)) $showrecips2[] = $showrecipsq; 
      }
    $showrecipsmerge = array_merge_recursive($showrecips1, $showrecips2);
    $showrecips = array_values($prayercenter->PCarray_unique($showrecipsmerge)); 
    }
   $count = count($showrecips);
   if ($count > 0)
    {
    foreach ($showrecips as $recip){
      $newrequest = wordwrap($newrequest,60,"\t\r\n");
      if($pcConfig['config_pms_plugin'] == 'privmsg') $newrequest = addslashes(nl2br(JText::_($newrequest)));
      if($config_email_inc_req == true){
        $message = sprintf( $config_email_message, $approver_name, $private, $newrequester, $sitename, $newrequest, $link, $sitename );
      } else {
        $message = sprintf( $config_email_message, $approver_name, $private, $newrequester, $sitename, $sitename );
      }
      $message = str_replace("\n","<br />",$message);
      $insID = $this->pcpmsloaddb($senderid, $recip, $message, $now, $config, $prayerrequest, $subject, $time);
      $this->pcpmsloadsmail($insID, $prayerrequest, $recip, $message, 0, $config);
      $message = "";
      }
    }
  }
  function send_private_messaging($newrequester, $newrequest, $newemail, $sendpriv, $lastid, $sessionid)
 	 {
    global $db, $pcConfig, $now, $config, $senderid, $prayercenteradmin;
    $time = null;
    $this->pcpmsloadvars();
    $lang =& Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $db		=& JFactory::getDBO();
    $livesite = JURI::root();
    $app =& JFactory::getApplication();
    $sitename = $app->getCfg( 'sitename' );
    $sender = JText::_('PCTITLE');
    $config_use_admin_alert = intval($pcConfig['config_use_admin_alert']);
    $config_email_request = intval($pcConfig['config_email_request']);
    $config_email_inc_req = $pcConfig['config_email_inc_req'];
    $config_pms_list = trim($pcConfig['config_pms_list']);
    $config_pms_list = strip_tags($config_pms_list);
    $pmsArray = preg_split('/[,]/',$config_pms_list, -1, PREG_SPLIT_NO_EMPTY);
		$slink = '<a href="'.$livesite.'" target="_blank">'.$sitename.'</a>';
    $prayerrequest = JText::_('PCTITLE');
    $config_email_message = $this->PCkeephtml(JText::_('PCEMAILMSG'));
    $sendpriv ? $config_email_nomessage = $this->PCkeephtml(JText::_('PCEMAILNOMSG')) : $config_email_nomessage = $this->PCkeephtml(JText::_('PCEMAILNOMSGPRIV'));
    $sendpriv ? $link = "" : $link = 'index.php?option=com_prayercenter&task=view_request&id='.$lastid.'&prv=1&pop=1&tmpl=component&sessionid='.$sessionid;
    $config_email_subject = $this->PCkeephtml(JText::_('PCEMAILSUBJECT'));
    $viewer_name = htmlentities(JText::_('PCVIEWERNAME'),ENT_COMPAT,'UTF-8');
    if($newemail){
      $newrequester = $newrequester.' ('.$newemail.')';
      }
    if($config_email_request == 0) {
      $showrecips = $prayercenteradmin->PCgetAdminData();
    }
    elseif($config_email_request == 1) {
      $db->setQuery("SELECT id,name,username,email FROM #__users");
      $showrecips = $db->loadObjectList();
    }
    elseif($config_email_request == 2) {
      $showrecips = array();
      foreach($pmsArray as $pms) {
        $pms = strtolower(trim($pms));
        preg_match('#(\d+)[-]#',$mod, $matches);
        $modquery = "SELECT id,name,username,email FROM #__users WHERE id=".(int)$matches[1]."";
        $db->setQuery($pmsquery);
        $pmrecip=$db->loadObjectList();
        if(is_array($pmrecip) && !empty($pmrecip)){
          $showrecips[] = $pmrecip[0];
        }
      }
    }
   $count = count($showrecips);
   $subject = sprintf( $config_email_subject, $newrequester );
   if ($count > 0)
    {
     foreach ($showrecips as $recip){
      if($config_email_inc_req == true){
        $message = sprintf( $config_email_message, $viewer_name, $newrequester, $sitename, $newrequest );
      } else {
        $message = sprintf( $config_email_nomessage, $viewer_name, $newrequester, $sitename, $livesite, $link );
      }
      $insID = $this->pcpmsloaddb((int)$senderid, $recip, $message, $now, $config, $prayerrequest, $subject, $time);
      $this->pcpmsloadsmail($insID, $prayerrequest, $recip, $message, 0, $config);
      $message = "";
     }
    }
  }
  function PCkeephtml($string){
    $res = htmlentities($string,ENT_COMPAT,'UTF-8');
    $res = str_replace("&lt;","<",$res);
    $res = str_replace("&gt;",">",$res);
    $res = str_replace("&quot;",'"',$res);
    $res = str_replace("&amp;",'&',$res);
    return $res;
  }
}
?>