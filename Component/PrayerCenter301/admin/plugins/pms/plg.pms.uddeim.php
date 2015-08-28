<?php
/***********************************************************************************
 Title          PrayerCenter Component for Joomla
 Author         Mike Leeper
 Plugin         uddeIM Private Messaging (requires uddeIM 2.9 or above)
 License        This is free software and you may redistribute it under the GPL.
                PrayerCenter comes with absolutely no warranty. For details, 
                see the license at http://www.gnu.org/licenses/gpl.txt
                YOU ARE NOT REQUIRED TO KEEP COPYRIGHT NOTICES IN
                THE HTML OUTPUT OF THIS SCRIPT. YOU ARE NOT ALLOWED
                TO REMOVE COPYRIGHT NOTICES FROM THE SOURCE CODE.
************************************************************************************/
defined( '_JEXEC' ) or die( 'Restricted access' );
class PCUddeimPMSPlugin extends PCPluginHelper {
  function pcpmsloadvars($newrequesterid){
    global $now, $config, $senderid;
    $app =& JFactory::getApplication();
    if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
    	$ver = new JVersion();
      if (!strncasecmp($ver->RELEASE, "3.1", 3)) {
      	require_once(JPATH_SITE.'/components/com_uddeim/uddeimlib31.php');
      } elseif (!strncasecmp($ver->RELEASE, "3.0", 3)) {
      	require_once(JPATH_SITE.'/components/com_uddeim/uddeimlib30.php');
      }
    } else {
    	require_once($app->getCfg('absolute_path').'/components/com_uddeim/uddeimlib31.php');
    }
    $pathtoadmin = uddeIMgetPath('admin');
    $pathtouser  = uddeIMgetPath('user');
    $pathtosite  = uddeIMgetPath('live_site');
    require_once($pathtoadmin."/admin.shared.php");
    require_once($pathtouser.'/bbparser.php');
    require_once($pathtouser.'/includes.php');
    require_once($pathtouser.'/includes.db.php');
    require_once($pathtouser.'/crypt.class.php');
    require_once($pathtouser.'/getpiclink.php');
    require($pathtoadmin."/config.class.php");
    $config = new uddeimconfigclass();
    uddeIMcheckConfig($pathtouser, $pathtoadmin, $config);
    uddeIMloadLanguage($pathtoadmin, $config);
    if($config->timezone == 0){
    $offset = $app->getCfg( 'config.offset' );
   	$now = uddetime($offset);
   	} else {
    $now = uddetime($config->timezone);
     }
    $senderid = '130';//userid of sender(fromid)
    return true;
  }
  function pcpmsloaddb($senderid, $recipid, $message, $now, $config, $prayerrequest=null, $subject=null, $time=null){
    $insID = uddeIMsaveRAWmessage($senderid, $recipid->id, '', $message, $now, $config, $config->cryptmode, '');
    return $insID;
  }
  function pcpmsloadsmail($insID, $var_fromid, $var_toid, $var_message, $emn_option, $config){
    uddeIMdispatchEMN($insID, '', $config->cryptmode, $var_fromid, $var_toid->id, $var_message, $emn_option, $config);
    return true;
  }
  function removeBadTags($source)
  {
    $allowedTags = '<br>';
    $source = strip_tags($source, $allowedTags);
    return preg_replace('/<(.*?)>/ie', "'<'.removeBadAttributes('\\1').'>'", $source);
  }
  function removeBadAttributes($tagSource)
  {
    $stripAttrib = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup';
    return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden', $tagSource));
  }
}
?>