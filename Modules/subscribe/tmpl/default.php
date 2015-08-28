<?php
/*****************************************************************************************
 Title          Mod_pc_subscribe PrayerCenter prayer chain subscription module for Joomla
 Author         Mike Leeper
 Version        3.0.0
 URL            http:www.mlwebtechnologies.com
 Email          web@mlwebtechnologies.com
 License        This is free software and you may redistribute it under the GPL.
                Mod_pc_subscribe comes with absolutely no warranty. For details, 
                see the license at http:www.gnu.org/licenses/gpl.txt
                YOU ARE NOT REQUIRED TO KEEP COPYRIGHT NOTICES IN
                THE HTML OUTPUT OF THIS SCRIPT. YOU ARE NOT ALLOWED
                TO REMOVE COPYRIGHT NOTICES FROM THE SOURCE CODE.
                Requires the PrayerCenter component v2.5.2 or higher
******************************************************************************************/
defined( '_JEXEC' ) or die( 'Restricted access' );// no direct access
$user = &JFactory::getUser();
$lang =& Jfactory::getLanguage();
$lang->load( 'com_prayercenter', JPATH_SITE);
if(file_exists(JPATH_ROOT."/administrator/components/com_prayercenter/config.xml")){
  require_once( JPATH_ROOT."/components/com_prayercenter/helpers/pc_includes.php" );
  require_once( JPATH_ROOT."/components/com_prayercenter/helpers/pc_class.php" );
  global $pcConfig;
  $prayercentermsub = new prayercenter();
  $pc_rights = $prayercentermsub->intializePCRights();
  $config_captcha = $pcConfig['config_captcha'];
  $config_captcha_bypass = $pcConfig['config_captcha_bypass_4member'];
  $show_subscribe = $pcConfig['config_show_subscribe'];
  $JVersion = new JVersion();
  $livesite = JURI::base();
  ?><script language="JavaScript" type="text/javascript">
    var enter_email = '<?php echo htmlentities(JText::_('PCENTEREMAIL'));?>';
    var enter_sec_code = '<?php echo htmlentities(JText::_('PCENTERSECCODE'));?>';
    var enter_valid_email = '<?php echo htmlentities(JText::_('PCINVALIDEMAIL'));?>';
    var livesite = '<?php echo $livesite;?>';
  </script>
  <style type="text/css">
    div#pcmodsub input[type="text"] {
      width: 170px;
    }
  </style>
  <?php
  $document =& JFactory::getDocument();
  $document->addScript('components/com_prayercenter/assets/js/pc.js');
  $js_script = "";
  if(session_id() == "") session_start();
  if ($show_subscribe == 1 && $pc_rights->get( 'pc.subscribe' ) == 1){
    echo '<div class="moduletable'.$moduleclasssfx.'" id="pcmodsub">';
    echo '<a name="pcmsub"></a>';
    echo '<form method="post" action="index.php?option=com_prayercenter&modtype=return_subscribmsg&mod=pcmsub" name="msub">';
    echo '<label for="newsubscribe">'.htmlentities(JText::_('PCEMAIL')).': </label>';
    echo '<input type="text" name="newsubscribe" id="newsubscribe" class="inputbox" value="'.$user->email.'" />';
    echo '<span style="white-space:nowrap;"><input type="radio" class="radio" style="padding-top:0px;padding-left:0px !important;" name="subscribe" value="subscribesubmit" onClick="javascript:document.msub.task.value=this.value;" checked="checked" />'.htmlentities(JText::_('PCSUBSCRIBE')).'</span>';
    echo '<span style="white-space:nowrap;"><input type="radio" class="radio" name="subscribe" value="unsubscribesubmit" style="padding-top:0px;padding-left:6px !important;" onClick="javascript:document.msub.task.value=this.value;" />'.htmlentities(JText::_('PCUNSUBSCRIBE')).'</span>';
    if((!$config_captcha_bypass && $config_captcha) || ($config_captcha_bypass && $user->get('id') == 0 && $config_captcha)){
      $js_script = 'return validateSub('.$config_captcha.', livesite, this.form, \'pcmsub\');';
      echo $prayercentermsub->PCgetCaptchaImg('pcmsub','msub');
    } else {
      $js_script = 'return validateSub(0, livesite, this.form, \'pcmsub\');';
      echo '<br /><br />';
    }
    echo '&nbsp;<button type="button" onclick="javascript:'.$js_script.'">';
    echo htmlentities(JText::_('PCSUBMIT')).'</button>';
    echo '<input type="hidden" name="option" value="com_prayercenter" />';
    echo '<input type="hidden" name="controller" value="prayercenter" />';
    echo '<input type="hidden" name="task" value="subscribesubmit" />';
    $defaultcaptcha = JFactory::getConfig()->get('captcha');
    echo '<input type="hidden" name="jcap" id="jcap" class="inputbox" value="'.$defaultcaptcha.'" />';
    echo JHTML::_( 'form.token' );
    echo '</form>';
    $return_subscribmsg = "";
    if(JRequest::getVar( 'return_subscribmsg', null, 'get', '' )) $return_subscribmsg = JRequest::getVar( 'return_subscribmsg', null, 'get', '' );
    echo '<div><font color="red"><b>'.wordwrap($return_subscribmsg,22,"<br />").'</b></font></div>';
    echo '</div>'; 
  }
} else { 
  if(!defined('PCCOMNOTINSTALL')) define('PCCOMNOTINSTALL','PrayerCenter Component Not Installed');
  echo '<div><center><font color="red"><b>'.htmlentities(JText::_('PCCOMNOTINSTALL')).'</b></font></center></div>';
}
?>