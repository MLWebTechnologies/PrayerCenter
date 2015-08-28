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
defined('_JEXEC') or die('Restricted access');
    global $prayercenter, $editorcontent;
    jimport('joomla.filesystem.folder');
    $JVersion = new JVersion();
    $livesite = JURI::base();
    $prayercenter->PCgetAuth('post');
    $document = JFactory::getDocument();
    $document->addScript('components/com_prayercenter/assets/js/pc.js');
		$conf = JFactory::getConfig();
    $config_editor = $this->config_editor;
    if($config_editor == 'default'){
      $config_editor = $conf->getCfg('editor');
      $user = JFactory::getUser();
      $userparams	= $user->getParameters(true);
      $usereditor = $userparams->get('editor');
      if(!empty($usereditor)) $config_editor = $userparams->get('editor');
    }
    $editorenabled = $prayercenter->PCcheckEditor($config_editor);
    if(!$editorenabled) $config_editor = 'none';
		$user = JFactory::getUser();
    $config_use_admin_alert = $this->config_use_admin_alert;
    $erequired = "";
    if($config_use_admin_alert == 1) $erequired = "javascript:PCchgClassNameOnBlur('newemail');";
    $js_script = "";
      ?>
      <script type="text/javascript">
        var enter_req = "<?php echo JText::_('PCENTERREQ');?>";
        var confirm_enter_email = "<?php echo JText::_('PCCONFIRMENTEREMAIL');?>";
        var enter_sec_code = "<?php echo JText::_('PCENTERSECCODE');?>";
        var editor = "<?php echo $config_editor;?>";
        var livesite = "<?php echo $livesite;?>";
      </script>
      <?php
    if(session_id() == "") session_start();
    if(JRequest::getVar( 'return_msg', null, 'get', 'string' )) $prayercenter->PCReturnMsg(JRequest::getVar( 'return_msg', null, 'get', 'string' ));
    echo '<div>';
    echo '<form method="post" action="'.$this->action.'" name="adminForm">';
    if($this->config_show_page_headers) echo '<div class="componentheading"><h2>'.htmlentities($this->title).' - '.htmlentities(JText::_('PCSUBMITREQUEST')).'</h2></div>';
    echo '<div>';
    $prayercenter->buildPCMenu();
    echo '</div><div>';
    $prayercenter->writePCImage().'</div><div>';
    echo $prayercenter->writePCHeader($this->directions).'</div>';
    echo '<fieldset class="pcmod"><legend>'.htmlentities(JText::_('PCSUBMITREQUEST')).'</legend>';
  	echo '<div>';
    echo '<label for="newrequester">'.JText::_('PCNAME').': ('.htmlentities(JText::_('PCANONMSG')).')</label><br />';
    echo '<input type="text" name="newrequester" id="newrequester" size="54" class="inputbox" value="'.$user->get('name').'" /></div>';
    if ($this->email_option == '1'){
      echo '<div style="padding-top:4px;"><label for="newemail">'.htmlentities(JText::_('PCEMAIL')).':'; 
      if($config_use_admin_alert != 1) echo ' ('.htmlentities(JText::_('PCOPTIONAL')).')';
      echo '</label><br />';
      echo '<input type="text" name="newemail" id="newemail" size="54" class="inputbox" value="'.$user->get('email').'" onBlur="'.$erequired.'" /></div>';
    }
    echo '<div style="padding-top:4px;"><label for="newtitle">'.htmlentities(JText::_('PCREQTITLE')).': </label><br />';
    echo '<input type="text" name="newtitle" id="newtitle" size="40" class="inputbox" value="" /></div>';
    echo '<div style="padding-top:4px;"><label for="newtopic">'.htmlentities(JText::_('PCREQTOPIC')).': </label><br />';
    $newtopicarray = $prayercenter->PCgetTopics();
    echo '<select name="newtopic">';
    $topics = '<option value="">'.htmlentities(JText::_('PCSELECTTOPIC')).'</option>';
    foreach($newtopicarray as $nt){
      $topics .= '<option value="'.$nt['val'].'">'.$nt['text'].'</option>';
    }
    echo $topics;
    echo '</select></div>';
    echo '<div style="padding-top:10px;"><label for="newrequest">'.htmlentities(JText::_('PCREQUEST')).':</label></div>';
    echo '<div>';
    echo $prayercenter->PCgetEditorBox();
    echo '</div>';
    if ($this->show_priv_option == '1'){
      echo '<div style="white-space:nowrap;margin-left:5px;padding-left:0;padding-bottom:8px;text-align:left;font-weight:bold;">';
      echo '<br /><input type="checkbox" class="radio" name="psend" id="psend" onClick="javascript:if(document.adminForm.psend.checked){document.adminForm.sendpriv.value=0;}else{document.adminForm.sendpriv.value=1;}" />';
      echo '<font size="1">'.htmlentities(JText::_('PCPRIV')).'</font>';
      echo '</div>';
    }
    if(!$this->config_captcha_bypass || ($this->config_captcha_bypass && $user->guest)){
        echo $prayercenter->PCgetCaptchaImg();
        if($this->config_use_admin_alert == 1){
          $js_script = "return validateNewE(".$this->config_captcha.",'".$config_editor."', livesite, this.form, 'pccomp')";
        }else{
          $js_script = "return validateNew(".$this->config_captcha.",'".$config_editor."', livesite, this.form, 'pccomp')";
        }
    } else {
        if($this->config_use_admin_alert == 1){
          $js_script = "return validateNewE(0,'".$config_editor."', livesite, this.form, 'pccomp')";
        }else{
          $js_script = "return validateNew(0,'".$config_editor."', livesite, this.form, 'pccomp')";
        }
      }
    if($config_editor == 'none' || !$editorenabled){
      echo "<div style=\"padding-left:10px;\"><br /><button type=\"button\" onclick=\"javascript:document.adminForm.valreq.value=document.adminForm.newrequest.value;".$js_script.";return false;\">";
    } else {
      echo "<div style=\"padding-left:10px;\"><br /><button type=\"button\" onclick=\"javascript:document.adminForm.valreq.value=".$editorcontent.$js_script.";return false;\">";
		}
    echo JText::_('PCSEND').'</button>';
    echo '</div>';
    echo '</fieldset>';
    echo '<input type="hidden" name="sendpriv" id="sendpriv" size="5" class="inputbox" value="1" />';
    echo '<span style="display:none;visibility:hidden;">';
    echo '<input type="text" name="temail" size="5" class="inputbox" value="" />';
    echo '<input type="text" name="formtime" size="5" class="inputbox" value="'.time().'" />';
    echo '</span>';
    echo '<input type="hidden" name="valreq" size="5" class="inputbox" value="" />';
    echo '<input type="hidden" name="requesterid" size="5" class="inputbox" value="'.$user->get('id').'" />';
    if((real)$JVersion->RELEASE >= 2.5 ){
      $defaultcaptcha = JFactory::getConfig()->get('captcha');
    } else {
      $defaultcaptcha = "";
    }
    echo '<input type="hidden" name="jcap" id="jcap" class="inputbox" value="'.$defaultcaptcha.'" />';
    echo '<input type="hidden" name="option" value="com_prayercenter" />';
    echo '<input type="hidden" name="controller" value="prayercenter" />';
    echo '<input type="hidden" name="task" value="newreqsubmit" />';
	  echo JHTML::_( 'form.token' );
    echo '</form>';
    echo '</div><br />';
    $prayercenter->writePCFooter();
?>