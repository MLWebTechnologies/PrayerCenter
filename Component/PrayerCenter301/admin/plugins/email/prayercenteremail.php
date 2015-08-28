<?php
/**
 * @Component - PrayerCenter
 * @Plugin - Prayer request email
 * @copyright Copyright (C) MLWebtechnologies.com
 * @license GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.plugin.plugin' );
class plgSystemPrayerCenterEmail extends JPlugin {
  var $pcConfig;
	public function plgSystemPrayerCenterEmail(& $subject, $config){
		parent::__construct($subject, $config);
  }
  public function onAfterRoute(){                   
    $pcParams = JComponentHelper::getParams('com_prayercenter');
    $pcParamsArray = $pcParams->toArray();
    foreach($pcParamsArray['params'] as $name => $value){
      $pcConfig[(string)$name] = (string)$value;
    }
    $this->pcConfig = $pcConfig;
    $lang = JFactory::getLanguage();
    $lang->load('com_prayercenter', JPATH_ADMINISTRATOR);
    self::pcEmailTask('PCadmin_email_notification');
    self::pcEmailTask('PCemail_notification');
  }
  public function pcEmailTask($pctask, $cid=array()){
    if($pctask == 'PCconfirm_sub_notification') self::$pctask($cid);
    if($pctask == 'PCconfirm_unsub_notification') self::$pctask($cid);
    if($pctask == 'PCemail_subscribe') self::$pctask($cid);
    if($pctask == 'PCemail_unsubscribe') self::$pctask($cid);
    if($pctask == 'PCadmin_email_subscribe_notification') self::$pctask($cid);
    $send = self::PCchecksend($pctask);
    if($pctask == 'PCadmin_email_notification') {
      $results = self::PCgetUnsent($cid,true);
    } else {
      $results = self::PCgetUnsent($cid,false);
    }
    if($send && (count($results)>0))                 
    {
      if($pctask == 'PCadmin_email_notification') {
        self::PCsetSendto($results,true);
      } else {
        self::PCsetSendto($results,false);
      }
      self::$pctask($results);
    }
  }
  private function PCgetUnsent($cid,$admin)
  {
    $db = JFactory::getDBO();
  	count($cid)>0 ? $idstr = ' AND id IN ("'.$cid[0].'")' : $idstr = "";
    if($admin){
      $db->setQuery ("SELECT * FROM #__prayercenter WHERE adminsendto='0000-00-00 00:00:00' AND archivestate=0".$idstr);
    } else {
      $db->setQuery ("SELECT * FROM #__prayercenter WHERE sendto='0000-00-00 00:00:00' AND archivestate=0".$idstr);
    }
    $results = $db->loadObjectList();
    return $results;
  }
  private function PCsetSendto($results,$admin)
  { 
    $db = JFactory::getDBO();
    $timenow = gmdate ( 'Y-m-d H:i:s' );
    foreach($results as $result){
      $cids[] = $result->id;
    }
    $cids = implode( ',', $cids );
    if($admin){
      $db->setQuery ("UPDATE #__prayercenter SET adminsendto='$timenow' WHERE id IN($cids)");
    } else {
      $db->setQuery ("UPDATE #__prayercenter SET sendto='$timenow' WHERE id IN($cids)");
    }
    $db->query();
    $updateresult = $db->getAffectedRows();
    if ($updateresult > 0) {return true;} else {return false;}
  }
  private function PCchecksend($pctask)
  {                              
    jimport('joomla.filesystem.folder');
    if($pctask == 'PCconfirm_notification') return true;
    if($pctask == 'PCconfirm_sub_notification') return true;
    if($pctask == 'PCconfirm_unsub_notification') return true;
    if($pctask == 'PCemail_subscribe') return true;
    if($pctask == 'PCemail_unsubscribe') return true;
  	$mediaPath = JPATH_ROOT.'/media';
  	$checkfileName='plg_pcemail_checkfile';
  	$now = time();
  	$dateCheckFile = $checkfileName;	
  	$okToContinue = true;
    $filearray = JFolder::files($mediaPath, $checkfileName.'*.*');
    if(count($filearray)>0) {
      $lastsent = filemtime($mediaPath.'/'.$filearray[0]);
    }
    $timenow = date( 'Y-m-d H:i:s' ); 
    $freq = $this->pcConfig['config_sendfreq']; 
    if ($freq == 0)
    {
      return true;
    }
    if ($freq == 1)
    {
      $timeadd = 60 * 60;
      if(isset($lastsent)) {
        $sendstring = $lastsent + $timeadd;
      } else {
        $sendstring = time();
      }
      $newdate = date('Y-m-d H:i:s', $sendstring);
      foreach($filearray as $matchfile){
        if ($timenow > $newdate) {
          @unlink($mediaPath.'/'.$matchfile);
          }
      }
    }
    if ($freq == 2)
    {
      $time = $this->pcConfig['config_sendtime'];  
      $time = date('H:i:s', strtotime($time));
      $nowday = JHTML::Date($timenow, 'w');
      $nowtime =  JHTML::Date($timenow, 'H:i:s');
      $nowdate = JHTML::Date($timenow, 'Y-m-d');
      $sentdate =  JHTML::Date($lastsent, 'Y-m-d');
      foreach($filearray as $matchfile){
        if ($nowdate != $sentdate && $nowtime > $time) {
          @unlink($mediaPath.'/'.$matchfile);
          }
      }
    }
    if ($freq == 3)
    {
      $day = $this->pcConfig['config_sendday'];
      $time = $this->pcConfig['config_sendtime'];
      $time = date('H:i:s', strtotime($time));
      $nowday = JHTML::Date($timenow, 'w');
      $nowtime =  JHTML::Date($timenow, 'H:i:s');
      $nowdate = JHTML::Date($timenow, 'Y-m-d');
      $sentdate =  JHTML::Date($lastsent, 'Y-m-d');          
      foreach($filearray as $matchfile){
        if ($nowdate != $sentdate && $nowday == $day && $nowtime > $time) {
          @unlink($mediaPath.'/'.$matchfile);
          }
      }
    }
    if (is_writable($mediaPath) )
  		{
  		if (is_file($mediaPath.'/'.$dateCheckFile) ) 
  			{
          $okToContinue = false;
        }
    		elseif (!touch($mediaPath.'/'.$dateCheckFile)) 
  			{
          $okToContinue = false;
  			}
  		} else {
        $okToContinue = false;
      }
    return $okToContinue;
  }
  private function PCerrorLog($msg){
  	jimport('joomla.error.log');	
  	$log_file_path = JPATH_ROOT.'/administrator/components/com_prayercenter/logs';
  	JLog::addLogger(
      array(
          'text_file' => 'pcerrorlog.php',
          'text_entry_format' => '{DATE} {TIME} {MESSAGE}',
  		  'text_file_path' => $log_file_path
      ),
  	 JLog::ALL & ~JLog::DEBUG,
         array('com_prayercenter')
  	); 
  	// not sure why, but in v3 need to include "category" (com_prayercenter) to avoid message showing on frontend
  	JLog::add($msg,JLog::ALL,'com_prayercenter');		
  }
  private function PCsendmail($mail_from, $config_sender_name, $toemail, $email_subject, $body, $config_email_mode, $email_intro=null, $email_footer=null)
  {
    $config_email_bcc = $this->pcConfig['config_email_bcc'];  	
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $root = JURI::root();
    $app = JFactory::getApplication();
    $sitename = $app->getCfg( 'sitename' );
    $livesite = JURI::root();
    if($config_email_mode) {
      $image = $root.'media/plg_prayercenteremail/images/prayer.jpg';
     	$slink = '<a href="'.$livesite.'" target="_blank">'.$sitename.'</a>';
      $filename = JPATH_ROOT.'/media/plg_prayercenteremail/css/pcemail.css';
      if(file_exists($filename))
      {	
  	    $csscontents=fopen($filename,"rb");
  	    $filecontent = fread($csscontents,filesize($filename));
  	    fclose($csscontents);
      }
      $message = '<style>'.$filecontent.'</style>'; 
      $message .= '<a href="'.$root.'" title="Visit the website"><img class="emailimage" src="'.$image.'"></a>';
      if(!is_null($email_intro)) $message .= '<div class="intro">'.$email_intro.'</div><div class="divider">	</div>';
      $message .= '<div>'.$body.'</div><div class="divider">    </div>';
      if(!is_null($email_footer)) {
        if($config_email_mode == true) $footer = str_replace(array("\n","\t"),array("<br />",'<span style="padding: 0 10px">&nbsp;</span>'),$email_footer);
        $message .= '<div>'.$footer.'</div>';
      }
    } else {
      $message = str_replace("\t","",$email_intro);
      $message .= $body;
      $message .= $email_footer;
    }
    $mailer = JFactory::getMailer();
  	$mailer->setSender(array($mail_from, html_entity_decode($config_sender_name,ENT_QUOTES,"UTF-8")));
  	$mailer->setSubject(html_entity_decode($email_subject,ENT_QUOTES,"UTF-8"));
  	$mailer->setBody(html_entity_decode($message,ENT_QUOTES,"UTF-8"));
  	$mailer->IsHTML($config_email_mode);
    if ( $config_email_bcc && count($toemail) > 1 ) {
   		$mailer->addBCC($toemail);
    	$mailer->addRecipient($mail_from);
    } else {
   		$mailer->addRecipient($toemail);
    }
    $count = count($toemail);
  	$rs	= $mailer->Send();
  	if($this->pcConfig['config_error_logging']){
    	if ( JError::isError($rs) && $this->pcConfig['config_error_logging'] > 0 ) {
    		$msg	= $rs->getError();
    	} elseif(!$rs && $this->pcConfig['config_error_logging'] > 0) {
    		$msg = JText::_('PCMAILNOTSENT');
    	} elseif($this->pcConfig['config_error_logging'] == 2) {
    		$msg = JText::_( 'PCMAILSENT' );
        $msg = sprintf( $msg, $count );
    	}
      if(isset($msg)) self::PCerrorLog($msg);
    }
  }
  private function PCconfirm_notification($items)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $livesite = JURI::root();
    $conf = JFactory::getConfig();
    $sitename = $conf->get( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $mail_from = self::getPCReturnAddress();
    $email_message = htmlentities(JText::_('PCCONFIRMEMAILMSG'));
    $email_subject = htmlentities(JText::_('PCCONFIRMEMAILSUBJECT'));
    $message = stripslashes(JText::_($message));
    $link = $livesite.'index.php?option=com_prayercenter&task=confirm&id='.$items[0]->id.'&sessionid='.$items[0]->sessionid;
    $clink = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
    $slink = '<a href="'.$livesite.'" target="_blank">'.$sitename.'</a>';
    $toname = stripslashes(JText::_($items[0]->requester));
    $toemail = $items[0]->email;
    if($config_email_mode == true){
      $body = sprintf( $email_message, $toname, $slink, $message, $clink );
      $body = str_replace("\n","<br />",$body);
    } else {
      $body = sprintf( $email_message, $toname, $livesite, $message, $link );
    }
    $mail_to[] = $toemail;
    self::PCsendmail($mail_from, $config_sender_name, $mail_to, $email_subject, $body, $config_email_mode);
  }
  private function PCconfirm_sub_notification($item)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $livesite = JURI::root();
    $conf = JFactory::getConfig();
    $sitename = $conf->get( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $config_admin_approve_subscribe = $this->pcConfig['config_admin_approve_subscribe'];
    $mail_from = self::getPCReturnAddress();
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $email_message = htmlentities(JText::_('PCSUBCONFIRMEMAILMSG'));
    $email_subject = htmlentities(JText::_('PCSUBCONFIRMEMAILSUBJECT'));
    $subemail = $item[0];
    $link = $livesite.'index.php?option=com_prayercenter&task=confirm_sub&id='.$item[1].'&sessionid='.$item[2];
    $clink = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
    if ($config_admin_approve_subscribe == 1){
      $email_message = htmlentities(JText::_('PCAPPROVESUBEMAILMSG'));
      $mail_to = self::getPCAdminModAddress();
    } else {
    	$mail_to[] = $item[0];
    }
    if($config_email_mode == true){
      $body = sprintf( $email_message, $subemail, $sitename, $clink );
      $body = str_replace("\n","<br />",$body);
    } else {
      $body = sprintf( $email_message, $subemail, $sitename, $link );
    }
    self::PCsendmail($mail_from, $config_sender_name, $mail_to, $email_subject, $body, $config_email_mode);
  }
  private function PCconfirm_unsub_notification($item)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $livesite = JURI::root();
    $conf = JFactory::getConfig();
    $sitename = $conf->get( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $mail_from = self::getPCReturnAddress();
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $email_message = htmlentities(JText::_('PCUNSUBCONFIRMEMAILMSG'));
    $email_subject = htmlentities(JText::_('PCUNSUBCONFIRMEMAILSUBJECT'));
    $link = $livesite.'index.php?option=com_prayercenter&task=confirm_unsub&id='.$item[1].'&sessionid='.$item[2];
    $clink = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
    if($config_email_mode == true){
      $body = sprintf( $email_message, $sitename, $clink );
      $body = str_replace("\n","<br />",$body);
    } else {
      $body = sprintf( $email_message, $sitename, $link );
    }
    $mail_to[] = $item[0];
    self::PCsendmail($mail_from, $config_sender_name, $mail_to, $email_subject, $body, $config_email_mode);
  }
  private function PCemail_notification($items)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $db	= JFactory::getDBO();
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $livesite = JURI::root();
    $conf = JFactory::getConfig();
    $sitename = $conf->get( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $config_email_inc_req = $this->pcConfig['config_email_inc_req'];
    $config_use_admin_alert = $this->pcConfig['config_use_admin_alert'];
    $config_distrib_type = $this->pcConfig['config_distrib_type'];
    $config_email_list = $this->pcConfig['config_email_list'];
    $config_email_list = strip_tags($config_email_list);
    $emailArray = preg_split('/[,]/',$config_email_list, -1, PREG_SPLIT_NO_EMPTY);
    $config_email_request = $this->pcConfig['config_email_request'];
    $email_intro = htmlentities(JText::_('PCEMAILINTRO'));
    $email_message = htmlentities(JText::_('PCEMAILMSG'));
    $email_subject = htmlentities(JText::_('PCEMAILSUBJECT'));
    $viewer_name = htmlentities(JText::_('PCVIEWERNAME'));
    $email_footer = JText::_('PCEMAILFOOTER');
    $email_footer =  sprintf(str_replace("\t","",$email_footer),$livesite);
    $mail_from = self::getPCReturnAddress();
    $body = "";
    $mail_to = array();
    if($config_email_request == '0') {
      $resultusers = self::PCPlggetAdminData();
      if(!empty($resultusers)){
        foreach($resultusers as $results) {
          $mail_to[] = $results->email;
        }
      }
    }
    if($config_email_request == '1'){
      $db->setQuery("SELECT name,email FROM #__users");
      $resultusers = $db->loadObjectList();
      if(!empty($resultusers)){
        foreach($resultusers as $results) {
          $mail_to[] = $results->email;
        }
      }
    }
    if($config_email_request == '2'){
        if(!empty($emailArray)){
          foreach($emailArray as $email) {
            $mail_to[] = trim($email);
          }
        }
    }
    if($config_email_mode == true) $body .= '<ul>';
    foreach($items as $item){
      $reqdate = JHtml::date($item->date.' '.$item->time, 'Y-m-d G:i:s', false);
      $fdate = date("D M j, Y", strtotime($reqdate));
      $ftime = date("g:ia (T)", strtotime($reqdate));
      if($config_email_mode == true) { $body .= '<li>'; } else { $body .= "\t"; }
      if(!$item->displaystate){
        $private = htmlentities(JText::_('PCPRIVATE'))." ";
        $email_nomessage = htmlentities(JText::_('PCEMAILNOMSGPRIV'));
      } else {
        $private = "";
        $email_nomessage = htmlentities(JText::_('PCEMAILNOMSG'));
      }
      $message = stripslashes(JText::_($item->request));
//    	$message = wordwrap($message,60,"\t\r\n");
      if($item->requester == JText::_('PCANONUSER')){
        $item->requester = htmlentities(strtolower($item->requester));
      }
      $item->displaystate ? $link = "" : $link = 'index.php?option=com_prayercenter&task=view_request&prv=1&pop=1&tmpl=component&id='.$item->id.'&sessionid='.$item->sessionid;
    	$slink = '<a href="'.$livesite.'" target="_blank">'.$sitename.'</a>';
      if(!empty($item->email) && $config_email_mode == true) $from_id = '<a href="mailto:'.$item->email.'">'.$item->requester.'</a>';
      if(!empty($item->email) && $config_email_mode == false)
      {
        $from_id = $item->requester.' ('.$item->email.')';
      } elseif(empty($item->email)) {
        $from_id = $item->requester;
      }
      if($config_email_mode == true){
        if($config_email_inc_req == true){
          $body .= sprintf( $email_message, $private, $from_id, $fdate, $ftime, $message, $slink );
        } else {
          $body .= sprintf( $email_nomessage, $private, $from_id, $slink, $livesite, $link );
        }
        $body = str_replace("\n","<br />",$body);
      } else {
        if($config_email_inc_req == true){
          $body .= sprintf( $email_message, $private, $from_id, $fdate, $ftime, $message, $sitename );
        } else {
          $body .= sprintf( $email_nomessage, $private, $from_id, $sitename, $livesite, $link );
        }
      }
      $body .= "\n\n";
      if($config_email_mode == true) $body .= '</li>';
    }
    if($config_email_mode == true) $body .= '</ul>';
    if($config_email_mode == true){
      $email_intro = sprintf( $email_intro, $viewer_name, $slink );
      $email_intro = str_replace(array("\n","\t"),array("<br />",'<span style="padding: 0 10px">&nbsp;</span>'),$email_intro);
      $body = str_replace("\n","<br />",$body);
    } else {
      $email_intro = sprintf( $email_intro, $viewer_name, $sitename );
    }
  	$subject = sprintf( $email_subject, $sitename );
    if(count($mail_to)>0) self::PCsendmail($mail_from, $config_sender_name, $mail_to, $email_subject, $body, $config_email_mode, $email_intro, $email_footer);
    self::PCemail_prayer_chain($items);
  }
  private function PCadmin_email_notification($items)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $db	= JFactory::getDBO();
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $livesite = JURI::root();
    $adminsite = $livesite."administrator";
    $app = JFactory::getApplication();
    $sitename = $app->getCfg( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $config_email_inc_req = $this->pcConfig['config_email_inc_req'];
    $config_use_admin_alert = $this->pcConfig['config_use_admin_alert'];
    $config_moderator_list = trim($this->pcConfig['config_moderator_user_list']);
    $config_moderator_list = strip_tags($config_moderator_list);
    $moderatorArray = preg_split('/[,]/',$config_moderator_list, -1, PREG_SPLIT_NO_EMPTY);
    $config_email_list = trim($this->pcConfig['config_email_list']);
    $config_email_list = strip_tags($config_email_list);
    $emailArray = preg_split('/[,]/',$config_email_list, -1, PREG_SPLIT_NO_EMPTY);
    $config_email_request = $this->pcConfig['config_email_request'];
    $mail_from = self::getPCReturnAddress();
    $email_intro = htmlentities(JText::_('PCAPPROVEEMAILINTRO'));
    $email_subject = htmlentities(JText::_('PCAPPROVEEMAILSUBJECT'));
    $email_message = htmlentities(JText::_('PCAPPROVEEMAILMSG'));
    $email_nomessage = htmlentities(JText::_('PCAPPROVEEMAILNOMSG'));
    $approver_name = htmlentities(JText::_('PCAPPROVERNAME'));
    $body = "";
    if ($config_use_admin_alert == 2) {
      $resultusers = self::PCPlggetAdminData();
    }
    elseif ($config_use_admin_alert == 3){
      $resultusers = array();
      foreach ($moderatorArray as $mod){
        preg_match('#(\d+)[-]#',$mod, $matches);
        $modquery = "SELECT name,email FROM #__users WHERE id=".(int)$matches[1]."";
        $db->setQuery( $modquery );
        $showrecipsq = $db->loadObjectList();
        if(is_array($showrecipsq) && !empty($showrecipsq)){
          $resultusers[] = $showrecipsq[0];
        }
     }
    } elseif($config_use_admin_alert == 4){
      $showrecips1 = self::PCPlggetAdminData();
      $showrecips2 = array();
      foreach ($moderatorArray as $mod){
        preg_match('#(\d+)[-]#',$mod, $matches);
        $modquery = "SELECT name,email FROM #__users WHERE id=".(int)$matches[1]."";
        $db->setQuery( $modquery );
        $showrecipsq = $db->loadObjectList();
        if(is_array($showrecipsq) && !empty($showrecipsq)){
          $showrecips2[] = $showrecipsq[0];
        }
      }
     $showrecipsmerge = array_merge_recursive($showrecips1, $showrecips2);
     $resultusers = array_values(self::PCarray_unique($showrecipsmerge)); 
    }
    $mail_to = array();
    foreach($resultusers as $results) {
      $mail_to[] = $results->email;
    }
    if($config_email_mode == true) $body .= '<ul>';
    foreach($items as $item){
      if($config_email_mode == true) { $body .= '<li>'; } else { $body .= "\t"; }
      if(!$item->displaystate){
        $private = htmlentities(JText::_('PCPRIVATE'))." ";
      } else {
        $private = "";
      }
      if($item->requester == JText::_('PCANONUSER')){
        $item->requester = htmlentities(strtolower($item->requester));
      }
      $message = '"'.stripslashes(JText::_($item->request)).'"';
      $approvelink = $livesite.'index.php?option=com_prayercenter&task=confirm_adm&id='.$item->id.'&sessionid='.$item->sessionid;
      $dellink = $livesite.'index.php?option=com_prayercenter&task=delreq_adm&id='.$item->id.'&sessionid='.$item->sessionid;
    	$slink = '<a href="'.$livesite.'" target="_blank">'.$sitename.'</a>';
      $clink = '<a href="'.$approvelink.'" target="_blank">'.htmlentities(JText::_('PCAPPROVE')).'</a> | <a href="'.$dellink.'" target="_blank">'.htmlentities(JText::_('PCDELETE')).'</a>';
      $plink = "\n\n".htmlentities(JText::_('PCAPPROVE'))."\n".$approvelink."\n\n".htmlentities(JText::_('PCDELETE'))."\n".$dellink;
      $reqdate = JHtml::date($item->date.' '.$item->time, 'Y-m-d G:i:s', false);
      $fdate = date("D M j, Y", strtotime($reqdate));
      $ftime = date("g:ia (T)", strtotime($reqdate));
//    	$message = wordwrap($message,60,"\t\r\n");
    	if(!empty($item->email) && $config_email_mode == true) $from_id = '<a href="mailto:'.$item->email.'">'.$item->requester.'</a>';
      if(!empty($item->email) && $config_email_mode == false)
      {
        $from_id = $item->requester.' ('.$item->email.')';
      } elseif(empty($item->email)) {
        $from_id = $item->requester;
      }
      if($config_email_mode == true){
        if($config_email_inc_req == true){
          $body .= sprintf( $email_message, $private, $from_id, $fdate, $ftime, $message, $clink, $slink );
        } else {
          $body .= sprintf( $email_nomessage, $private, $from_id, $slink, $slink );
        }
      } else {
        if($config_email_inc_req == true){
          $body .= sprintf( $email_message, $private, $from_id, $fdate, $ftime, $message, $plink, $livesite );
        } else {
          $body .= sprintf( $email_nomessage, $private, $from_id, $sitename, $livesite );
        }
      }
      $body .= "\n\n";
      if($config_email_mode == true) $body .= '</li>';
    }
    if($config_email_mode == true) $body .= '</ul>';
    if($config_email_mode == true){
      $email_intro = sprintf( $email_intro, $approver_name, $slink );
      $email_intro = str_replace(array("\n","\t"),array("<br />",'<span style="padding: 0 10px">&nbsp;</span>'),$email_intro);
      $body = str_replace("\n","<br />",$body);
    } else {
      $email_intro = sprintf( $email_intro, $approver_name, $sitename );
    }
  	$subject = sprintf( $email_subject, $sitename );
    $email_footer = JText::_('PCAPPROVEEMAILFOOTER');
    $email_footer =  sprintf(str_replace("\t","",$email_footer),$adminsite);
    if(count($mail_to)>0) self::PCsendmail($mail_from, $config_sender_name, $mail_to, $subject, $body, $config_email_mode, $email_intro, $email_footer);
  }
  private function PCemail_prayer_chain($items)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $db	= JFactory::getDBO();
    $livesite = JURI::root();
    $conf = JFactory::getConfig();
    $sitename = $conf->get( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $config_email_inc_req = $this->pcConfig['config_email_inc_req'];
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $email_intro = htmlentities(JText::_('PCEMAILINTRO'));
    $config_email_message = htmlentities(JText::_('PCCHAINEMAILMSG'));
    $config_email_nomessage = htmlentities(JText::_('PCCHAINEMAILNOMSG'));
    $email_footer = JText::_('PCEMAILFOOTER');
    $subject = htmlentities(JText::_('PCEMAILSUBJECT'));
    $db->setQuery("SELECT * FROM #__prayercenter_subscribe WHERE approved='1'");
    $resultsubscribers = $db->loadObjectList();
    $subscriber_name = htmlentities(JText::_('PCSUBSCRIBERNAME'));
    $slink = '<a href="'.$livesite.'" target="_blank">'.$sitename.'</a>';
    $mail_from = self::getPCReturnAddress();
    $mail_to = array();
    $body = "";
    $k = 0;
    foreach($resultsubscribers as $subscribers){
      $mail_to[] = $subscribers->email;
    }
    if($config_email_mode == true) $body .= '<ul>';
    foreach($items as $item){
      $reqdate = JHtml::date($item->date.' '.$item->time, 'Y-m-d G:i:s', false);
      $fdate = date("D M j, Y", strtotime($reqdate));
      $ftime = date("g:ia (T)", strtotime($reqdate));
      if($item->displaystate){
        if($config_email_mode == true) { $body .= '<li>'; } else { $body .= "\t"; }
        $message = stripslashes(JText::_($item->request));
        if($item->requester == JText::_('PCANONUSER')){
          $item->requester = htmlentities(strtolower($item->requester));
        }
        if($config_email_mode == true){
          if($config_email_inc_req == true){
            $body .= sprintf( $config_email_message, $item->requester, $fdate, $ftime, $message, $slink );
          } else {
            $body .= sprintf( $config_email_nomessage, $item->requester, $slink, $livesite, $slink );
          }
          $body = str_replace("\n","<br />",$body);
        } else {
          if($config_email_inc_req == true){
            $body .= sprintf( $config_email_message, $item->requester, $fdate, $ftime, $message, $sitename );
          } else {
            $body .= sprintf( $config_email_nomessage, $item->requester, $sitename, $livesite, $link );
          }
        }
        $body .= "\n\n";
        if($config_email_mode == true) $body .= '</li>';
        $k++;
      }
    }
    if($config_email_mode == true) $body .= '</ul>';
    if($config_email_mode == true){
      $email_intro = sprintf( $email_intro, $subscriber_name, $slink );
      $email_intro = str_replace(array("\n","\t"),array("<br />",'<span style="padding: 0 10px">&nbsp;</span>'),$email_intro);
      $body = str_replace("\n","<br />",$body);
    } else {
      $email_intro = sprintf( $email_intro, $subscriber_name, $sitename );
    }
  	$subject = sprintf( $subject, $sitename );
    $email_footer = JText::_('PCEMAILFOOTER');
    $email_footer =  sprintf(str_replace("\t","",$email_footer),$livesite);
    if(count($mail_to)>0 && $k>0) self::PCsendmail($mail_from, $config_sender_name, $mail_to, $subject, $body, $config_email_mode, $email_intro, $email_footer);
  }
   private function PCadmin_email_subscribe_notification($item)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE);
    $livesite = JURI::root();
    $conf = JFactory::getConfig();
    $sitename = $conf->get( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $config_subscribe_email_message = htmlentities(JText::_('PCADMINSUBSCRIBEMSG'));
    $subject = htmlentities(JText::_('PCADMINSUBSCRIBESUBJECT'));
    $link = $livesite.'administrator/';
    $slink = '<a href="'.$livesite.'administrator/" target="_blank">'.$livesite.'index.php?option=com_prayercenter&task=unsubscribe</a>';
    if($config_email_mode == true) {
      $body = sprintf( $config_subscribe_email_message, $item[0], $sitename, $slink );
      $body = str_replace("\n","<br />",$body);
    } else {
      $body = sprintf( $config_subscribe_email_message, $item[0], $sitename, $link );
  	}
    $mail_from = self::getPCReturnAddress();
    $mail_to = self::getPCAdminModAddress();
    self::PCsendmail($mail_from, $config_sender_name, $mail_to, $subject, $body, $config_email_mode);
  }
  private function PCemail_subscribe($item)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $livesite = JURI::base();
    $conf = JFactory::getConfig();
    $sitename = $conf->get( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $config_subscribe_email_message = htmlentities(JText::_('PCSUBSCRIBEMSG'));
    $subject = htmlentities(JText::_('PCSUBSCRIBESUBJECT'));
    $link = $livesite.'index.php?option=com_prayercenter&task=subscribe';
    $slink = '<a href="'.$livesite.'index.php?option=com_prayercenter&task=unsubscribe" target="_blank">'.$livesite.'index.php?option=com_prayercenter&task=unsubscribe</a>';
    if($config_email_mode == true) {
      $body = sprintf( $config_subscribe_email_message, $item[0], $sitename, $slink );
      $body = str_replace("\n","<br />",$body);
    } else {
      $body = sprintf( $config_subscribe_email_message, $item[0], $sitename, $link );
  	}
    $mail_from = self::getPCReturnAddress();
    $mail_to[] = $item[0];
    self::PCsendmail($mail_from, $config_sender_name, $mail_to, $subject, $body, $config_email_mode);
  }
  private function PCemail_unsubscribe($item)
  {
    jimport( 'joomla.mail.helper' );
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $livesite = JURI::base();
    $conf = JFactory::getConfig();
    $sitename = $conf->get( 'sitename' );
    $config_email_mode = $this->pcConfig['config_email_mode'];
    $config_sender_name = htmlentities(JText::_('PCEMAILSENDER'));
    $config_unsubscribe_email_message = htmlentities(JText::_('PCUNSUBSCRIBEMSG'));
    $subject = htmlentities(JText::_('PCSUBSCRIBESUBJECT'));
    $link = $livesite.'index.php?option=com_prayercenter&task=subscribe';
    $slink = '<a href="'.$livesite.'index.php?option=com_prayercenter&task=subscribe" target="_blank">'.$livesite.'index.php?option=com_prayercenter&task=subscribe</a>';
    if($config_email_mode == true) {
      $body = sprintf( $config_unsubscribe_email_message, $item[0], $sitename, $slink );
      $body = str_replace("\n","<br />",$body);
    } else {
      $body = sprintf( $config_unsubscribe_email_message, $item[0], $sitename, $link );
  	}
    $mail_from = self::getPCReturnAddress();
    $mail_to[] = $item[0];
    self::PCsendmail($mail_from, $config_sender_name, $mail_to, $subject, $body, $config_email_mode);
  }
  private function getPCReturnAddress(){
    $lang = JFactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $app = JFactory::getApplication();
    $config_custom_ret_addr = $this->pcConfig['config_custom_ret_addr'];
    $config_return_addr = $this->pcConfig['config_return_addr'];
    $mailfrom = $app->getCfg('mailfrom');
    if( $config_return_addr == 0 || $config_return_addr == 2 ){
      $pc_mf = $mailfrom;
     } elseif( $config_return_addr == 1 ) {
      $pc_mf = $config_custom_ret_addr;
     } 
  	$valid = preg_match( '/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $pc_mf );
    if(!$valid){
      return htmlentities(JText::_('PCMAILFROM'));
    } else {
      return $pc_mf; 
    }     
  }
  private function getPCAdminModAddress()
	{
	 $config_use_admin_alert = $this->pcConfig['config_use_admin_alert'];
    $config_moderator_list = trim($this->pcConfig['config_moderator_user_list']);
    $config_moderator_list = strip_tags($config_moderator_list);
    $moderatorArray = preg_split('/[,]/',$config_moderator_list, -1, PREG_SPLIT_NO_EMPTY);
    $config_email_list = trim($this->pcConfig['config_email_list']);
    $config_email_list = strip_tags($config_email_list);
    $emailArray = preg_split('/[,]/',$config_email_list, -1, PREG_SPLIT_NO_EMPTY);
    $config_email_request = $this->pcConfig['config_email_request'];
	// get admins or moderators (from admin request approval function)
		if ($config_use_admin_alert == 2) {
			$resultusers = self::PCPlggetAdminData();
		}
		elseif ($config_use_admin_alert == 3){
			$resultusers = array();
			foreach ($moderatorArray as $mod){
				preg_match('#(\d+)[-]#',$mod, $matches);
				$modquery = "SELECT name,email FROM #__users WHERE id=".(int)$matches[1]."";
				$db->setQuery( $modquery );
				$showrecipsq = $db->loadObjectList();
				if(is_array($showrecipsq) && !empty($showrecipsq)){
					$resultusers[] = $showrecipsq[0];
				}
			}
		} elseif($config_use_admin_alert == 4){
			$showrecips1 = self::PCPlggetAdminData();
			$showrecips2 = array();
			foreach ($moderatorArray as $mod){
				preg_match('#(\d+)[-]#',$mod, $matches);
				$modquery = "SELECT name,email FROM #__users WHERE id=".(int)$matches[1]."";
				$db->setQuery( $modquery );
				$showrecipsq = $db->loadObjectList();
				if(is_array($showrecipsq) && !empty($showrecipsq)){
					$showrecips2[] = $showrecipsq[0];
				}
			}
			$showrecipsmerge = array_merge_recursive($showrecips1, $showrecips2);
			$resultusers = array_values(self::PCarray_unique($showrecipsmerge));
		}
		$mail_to = array();
		foreach($resultusers as $results) {
			$mail_to[] = $results->email;
		}
		return $mail_to;
	}
  private function PCarray_unique(&$old){ 
      $new = array(); 
      foreach($old as $key => $value){ 
          if(!in_array($value, $new)) $new[$key] = $value; 
      } 
      return $new; 
  } 
  private function PCPlgArray_flatten($array) { 
    if (!is_array($array)) { 
      return FALSE; 
    } 
    $result = array(); 
    foreach ($array as $key => $value) { 
      if (is_array($value)) { 
//        $result = array_merge($result, $this->PCPlgArray_flatten($value)); 
  		  $result = array_merge($result, self::PCPlgArray_flatten($value));
      } 
      else { 
        $result[$key] = $value; 
      } 
    } 
    return $result; 
  } 
  private function PCPlggetAdminData(){
    $db = JFactory::getDBO();
    $access = JFactory::getACL();
    $db->setQuery("SELECT id FROM #__usergroups");
    $groups = $db->loadObjectList();
    foreach($groups as $group){
      if($access->checkGroup($group->id, 'core.manage') || $access->checkGroup($group->id, 'core.admin')){
        $adminusers[] = $access->getUsersByGroup($group->id);
      }
    }
    $result = self::PCPlgArray_flatten($adminusers);
 		$result = implode(',', $result);
    $db->setQuery("SELECT name,email FROM #__users WHERE id IN (".$result.")");
    $resultusers = $db->loadObjectList();
    return $resultusers;
  }
}
?>