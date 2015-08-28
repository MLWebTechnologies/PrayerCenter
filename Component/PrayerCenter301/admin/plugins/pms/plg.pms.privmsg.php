<?php
/*********************************************************************************
 Title          PrayerCenter Component for Joomla
 Author         Mike Leeper
 Plugin         PrivMSG Private Messaging (requires PrivMSG 3.0.0 or above)
 License        This is free software and you may redistribute it under the GPL.
                PrayerCenter comes with absolutely no warranty. For details, 
                see the license at http://www.gnu.org/licenses/gpl.txt
                YOU ARE NOT REQUIRED TO KEEP COPYRIGHT NOTICES IN
                THE HTML OUTPUT OF THIS SCRIPT. YOU ARE NOT ALLOWED
                TO REMOVE COPYRIGHT NOTICES FROM THE SOURCE CODE.
***********************************************************************************/
defined( '_JEXEC' ) or die( 'Restricted access' );
class PCPrivmsgPMSPlugin extends PCPluginHelper {
  function pcpmsloadvars($newrequesterid){
    global $now, $senderid, $sender;
    jimport('joomla.date.date');
    $lang =& Jfactory::getLanguage();
    $lang->load( 'com_pms', JPATH_SITE); 
    $app =& JFactory::getApplication();
    $senderid = null;
    $sender = JText::_('PMSEMAILSENDER');
    $dateset = new JDate();
    $now = $dateset->format('Y-m-d H:i:s');
    return true;
  }
  function pcpmsloaddb($senderid, $recipid, $message, $now, $config, $prayerrequest=null, $subject=null, $time=null){
    $db	=& JFactory::getDBO();
    $message = addslashes(nl2br($message));
    $sql = "INSERT INTO #__pms (id,username,whofrom,datetime,readstate,subject,message,archivestate,deletestate,systemmsg) VALUES ('',".$db->quote($db->getEscaped($recipid->username),false).",".$db->quote($db->getEscaped($prayerrequest),false).",".$db->quote($db->getEscaped($now),false).",0,".$db->quote($db->getEscaped($subject),false).",".$db->quote($db->getEscaped($message),false).",0,0,0)";
		$db->setQuery($sql);
		if (!$db->query()) {
			die("SQL error" . $db->stderr(true));
		}	
  	$sql = "SELECT a.emailnotification AS emailnotification, c.time AS online"
    	. "\n FROM #__pms_emailnotify AS a"
    	. "\n LEFT JOIN #__users AS b ON (b.username = a.username)"
   	  . "\n LEFT JOIN #__session AS c ON b.id = c.userid AND c.time=(SELECT MAX(time) FROM #__session)"
    	. "\n WHERE a.username='".$recipid->username."'";
    $db->setQuery($sql);
    $emailnotifyresult = $db->loadObject();
    return $emailnotifyresult;
  }
  function pcpmsloadsmail($insID, $var_fromid, $var_toid, $var_message, $emn_option, $config){
    global $sender;
  	$mailer =& JFactory::getMailer();
    $app =& JFactory::getApplication();
    $sitename = $app->getCfg( 'sitename' );
    $livesite = $app->getCfg( 'live_site' );
    $mailfrom = $app->getCfg('mailfrom');
    $emailnotify = $insID->emailnotification;
    $online = $insID->online;
    if ($emailnotify == 1 || $emailnotify == 3){
  		$body = JText::_('PMSEMAILBODYWITHMESSAGE');
      }
    elseif ($emailnotify == 2 || $emailnotify == 4){
  		$body = JText::_('PMSEMAILBODYNOMESSAGE');
    }
    $pms_subject = sprintf( JText::_('PMSEMAILSUBJECT'), $sitename );
    $mailer->setSubject($pms_subject);
    $mailer->setSender(array($mailfrom, $sender));
    $mailer->IsHTML(0);
    if ($emailnotify == 1 && $online > 0 || $emailnotify == 2 && $online > 0){
      $body = sprintf( $body, $var_toid->username, $var_fromid, $sitename, $var_message );
      $mailer->setBody($body);
 			$mail_to = $var_toid->email;
    	$mailer->addRecipient($mail_to);
      $mailer->Send();
    } elseif ($emailnotify == 3 && $online == 0 || $emailnotify == 4 && $online == 0){
      $body = sprintf( $body, $var_toid->username, $var_fromid, $sitename );
      $mailer->setBody($body);
 			$mail_to = $var_toid->email;
    	$mailer->addRecipient($mail_to);
      $mailer->Send();
      }
    return true;
  }
}
?>