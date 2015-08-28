<?php
/**********************************************************************************
 Title          PrayerCenter Component for Joomla
 Author         Mike Leeper
 Plugin         Joomla Private Messaging (built-in Joomla messaging component)
 License        This is free software and you may redistribute it under the GPL.
                PrayerCenter comes with absolutely no warranty. For details, 
                see the license at http://www.gnu.org/licenses/gpl.txt
                YOU ARE NOT REQUIRED TO KEEP COPYRIGHT NOTICES IN
                THE HTML OUTPUT OF THIS SCRIPT. YOU ARE NOT ALLOWED
                TO REMOVE COPYRIGHT NOTICES FROM THE SOURCE CODE.
***********************************************************************************/
defined( '_JEXEC' ) or die( 'Restricted access' );
class PCJoomlaPMSPlugin extends PCPluginHelper {
  function pcpmsloadvars($newrequesterid){
    global $now, $senderid;
    jimport('joomla.date.date');
    $JVersion = new JVersion();
    $app =& JFactory::getApplication();
    $senderid = '130';
    if($newrequesterid) $senderid = $newrequesterid;
    $dateset = new JDate();
    $now = $dateset->format('Y-m-d H:i:s');
    return true;
  }
  function pcpmsloaddb($senderid, $recipid, $message, $now, $config, $prayerrequest=null, $subject=null, $time=null){
    $user =& JFactory::getUser($recipid->id);
    if($user->authorise( 'core', 'manage' )) {
  		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_messages/models', 'MessagesModel');
  		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_messages/tables');
      $message = str_replace("<br />","\n",$message);
  		$PMMessage = array(
  				'user_id_from'	=> $senderid,
  				'user_id_to'	=> $recipid->id,
  				'subject'		=> $subject,
  				'message'		=> $message
  		);
  		$model_message = JModelLegacy::getInstance('Message', 'MessagesModel');
  		$model_message->save($PMMessage);
    }
		return true;
  }
  function pcpmsloadsmail($insID, $var_fromid, $var_toid, $var_message, $emn_option, $config){
    return true;
  }
}
?>