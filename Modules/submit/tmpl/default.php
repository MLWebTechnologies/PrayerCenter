<?php
/********************************************************************************************
 Title          Mod_pc_submit_request PrayerCenter prayer request submit module for Joomla
 Author         Mike Leeper
 Version        3.0.0
 URL            http:www.mlwebtechnologies.com
 Email          web@mlwebtechnologies.com
 License        This is free software and you may redistribute it under the GPL.
                Mod_pc_submit_request comes with absolutely no warranty. For details, 
                see the license at http:www.gnu.org/licenses/gpl.txt
                YOU ARE NOT REQUIRED TO KEEP COPYRIGHT NOTICES IN
                THE HTML OUTPUT OF THIS SCRIPT. YOU ARE NOT ALLOWED
                TO REMOVE COPYRIGHT NOTICES FROM THE SOURCE CODE.
                Requires the PrayerCenter component v2.5.2 or higher
*********************************************************************************************/
defined( '_JEXEC' ) or die( 'Restricted access' );// no direct access
$user = &JFactory::getUser();
$access_gid =$user->get('gid');
$lang =& Jfactory::getLanguage();
$lang->load( 'com_prayercenter', JPATH_SITE);
if(file_exists(JPATH_ROOT."/administrator/components/com_prayercenter/config.xml")){
  require_once( JPATH_ROOT."/components/com_prayercenter/helpers/pc_includes.php" );
  require_once( JPATH_ROOT."/components/com_prayercenter/helpers/pc_class.php" );
  global $pcConfig;
  $prayercentermsr = new prayercenter();
  $pc_rights = $prayercentermsr->intializePCRights();
  $config_captcha = $pcConfig['config_captcha'];
  $config_captcha_bypass = $pcConfig['config_captcha_bypass_4member'];
  $show_email_option = $pcConfig['config_email_option'];
  $show_priv_option = $pcConfig['config_show_priv_option'];
  $maxattempts = $pcConfig['config_captcha_maxattempts'];
  $config_use_admin_alert = $pcConfig['config_use_admin_alert'];
  $JVersion = new JVersion();
  $livesite = JURI::base();
  ?><script language="JavaScript" type="text/javascript">
    var enter_req = '<?php echo JText::_('PCENTERREQ');?>';
    var confirm_enter_email = '<?php echo JText::_('PCCONFIRMENTEREMAIL');?>';
    var enter_sec_code = '<?php echo JText::_('PCENTERSECCODE');?>';
    var enter_valid_email = '<?php echo JText::_('PCINVALIDEMAIL');?>';
    var livesite = '<?php echo $livesite;?>';
  </script>
  <style type="text/css">
    div#pcmodreqsub input[type="text"] {
      width: 90%;
    }
    div#pcmodreqsub textarea {
      width: 90%;
    }
    div#pcmodreqsub select {
      width: 95%;
    }
  </style>
  <?php
  $document =& JFactory::getDocument();
  $document->addScript('components/com_prayercenter/assets/js/pc.js');
  if ($pc_rights->get('pc.post')){
    $id = $user->name;
    $email = $user->email;
    $sendpriv=1;
    $subpraise=0;
    $js_script = "";
    if(session_id() == "") session_start();
    echo '<div class="moduletable'.$moduleclasssfx.'" id="pcmodreqsub">';
    echo '<a name="pcmsr"></a>';
    echo '<form method="post" action="index.php?option=com_prayercenter&modtype=return_submsg&mod=pcmsr" name="pcmsr">';
    echo '<label for="newrequester">'.htmlentities(JText::_('PCNAME')).': ('.htmlentities(JText::_('PCOPTIONAL')).')</label>';
    echo '<input type="text" name="newrequester" id="newrequester" value="'.$id.'" />';
    if ($show_email_option == '1'){
      echo '<label for="newemail">'.htmlentities(JText::_('PCEMAIL')).': ';
      if($config_use_admin_alert != 1) echo '('.htmlentities(JText::_('PCOPTIONAL')).')';
      echo '</label>';
      echo '<input type="text" name="newemail" id="newemail" value="'.$email.'" />';
    }
    echo '<label for="newtitle">'.htmlentities(JText::_('PCREQTITLE')).'</label>';
    echo '<INPUT TYPE="TEXT" name="newtitle" id="newtitle" class="inputbox" value="" />';
    echo '<label for="newtopic">'.htmlentities(JText::_('PCREQTOPIC')).'</label>';
    $newtopicarray = $prayercentermsr->PCgetTopics();
    echo '<select name="newtopic" id="newtopic">';
    $topics = '<option value="">'.htmlentities(JText::_('PCSELECTTOPIC')).'</option>';
    foreach($newtopicarray as $nt){
      $topics .= '<option value="'.$nt['val'].'">'.$nt['text'].'</option>';
    }
    echo $topics;
    echo '</select><br /><br />';
    echo '<label for="newrequest">'.htmlentities(JText::_('PCREQUEST')).':</label>';
    echo '<textarea name="newrequest" id="mnewrequest" class="inputbox" rows="8" style="resize:none;"></textarea>';
    echo '<input type="hidden" name="sendpriv" size="5" class="inputbox" value="'.$sendpriv.'" />';
    echo '<span style="display:none;visibility:hidden;">';
    echo '<input type="text" name="temail" size="5" class="inputbox" value="" />';
    echo '<input type="text" name="formtime" size="5" class="inputbox" value="'.time().'\" />';
    echo '</span>';
    if ($show_priv_option == '1'){
      echo '&nbsp;<input type="checkbox" class=radio style="margin:0px;padding:0px;" name="msend" id="msend" onClick="javascript:if(document.adminForm.msend.checked){document.adminForm.sendpriv.value=0;}else{document.adminForm.sendpriv.value=1;}" />';
      echo '&nbsp;<span style="font-size:x-small;white-space:nowrap;">'.htmlentities(JText::_('PCPRIV')).'</span></label>';
    }
    $user = &JFactory::getUser();
    if((!$config_captcha_bypass && $config_captcha) || ($config_captcha_bypass && $user->get('id') == 0 && $config_captcha)){
      if($config_use_admin_alert == 1){
        $js_script = "document.getElementById('valreq').value=document.getElementById('mnewrequest').value;return validateNewE(".$config_captcha.", 'none', livesite, this.form, 'pcmsr');";
        } else {
        $js_script = "document.getElementById('valreq').value=document.getElementById('mnewrequest').value;return validateNew(".$config_captcha.", 'none', livesite, this.form, 'pcmsr');";
        }
      if ($config_captcha) {
        echo $prayercentermsr->PCgetCaptchaImg('pcmsr','pcmsr');
      }
    } else {
      if($config_use_admin_alert == 1){
        $js_script = 'document.getElementById(\'valreq\').value=document.getElementById(\'mnewrequest\').value;return validateNewE(0, \'none\', livesite, this.form, \'pcmsr\');';
        } else {
        $js_script = 'document.getElementById(\'valreq\').value=document.getElementById(\'mnewrequest\').value;return validateNew(0, \'none\', livesite, this.form, \'pcmsr\');';
        }
      echo '<br /><br />';
     }
    echo '&nbsp;<button type="button" onclick="javascript:'.$js_script.'">';
    echo htmlentities(JText::_('PCSUBMIT')).'</button>';
    echo '<input type="hidden" name="valreq" id="valreq" value="" />';
    echo '<input type="hidden" name="option" value="com_prayercenter" />';
    echo '<input type="hidden" name="controller" value="prayercenter" />';
    echo '<input type="hidden" name="task" value="newreqsubmit" />';
    $defaultcaptcha = JFactory::getConfig()->get('captcha');
    echo '<input type="hidden" name="jcap" id="jcap" class="inputbox" value="'.$defaultcaptcha.'" />';
    echo JHTML::_( 'form.token' );
    echo '</form>';
    $return_submsg = "";
    if(JRequest::getVar( 'return_submsg', null, 'get', '' )) $return_submsg = JRequest::getVar( 'return_submsg', null, 'get', '' );
    echo '<div style="text-align:center;"><font color="red"><b>'.wordwrap($return_submsg,22,"<br />").'</b></font></div>';
    echo '</div>';
  }
} else { 
  if(!defined('PCCOMNOTINSTALL')) define('PCCOMNOTINSTALL','PrayerCenter Component Not Installed');
  echo '<div><center><font color="red"><b>'.htmlentities(JText::_('PCCOMNOTINSTALL')).'</b></font></center></div>';
}
?>