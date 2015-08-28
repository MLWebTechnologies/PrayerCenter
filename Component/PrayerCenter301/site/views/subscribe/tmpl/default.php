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
    global $prayercenter;
    $prayercenter->PCgetAuth('subscribe');
    echo '<script language="JavaScript" type="text/javascript" src="components/com_prayercenter/assets/js/pc.js"></script>';
    $document = JFactory::getDocument();
    $document->addScript('components/com_prayercenter/assets/js/pc.js');
		$user = JFactory::getUser();
    $js_script = "";
    $livesite = JURI::base();
    $JVersion = new JVersion();
      ?>
      <script type="text/javascript">
        var enter_email = "<?php echo JText::_('PCENTEREMAIL');?>";
        var enter_sec_code = "<?php echo JText::_('PCENTERSECCODE');?>";
        var livesite = '<?php echo $livesite;?>';
      </script>
      <?php
    if(JRequest::getVar( 'return_msg', null, 'get', 'string' )) $prayercenter->PCReturnMsg(JRequest::getVar( 'return_msg', null, 'get', 'string' ));
    echo '<div>';
    if($this->config_show_page_headers) echo '<div class="componentheading"><h2>'.htmlentities($this->title.' - '.JText::_('PCSUBSCRIBE')).'</h2></div>';
    echo '<div>';
    $prayercenter->buildPCMenu();
    echo '</div><div>';
    $prayercenter->writePCImage().'</div><div>';
    echo $prayercenter->writePCHeader($this->intro,false,htmlentities(JText::_('PCSUBPAGEMSG')));
    echo '<br /><br /></div>';
    echo '<fieldset class="pcmod"><legend>'.htmlentities(JText::_('PCSUBSCRIBE')).'</legend>';
    echo '<div>';
    echo '<form method="post" action="'.$this->action.'" name="adminForm" id="adminForm">';
    echo '<div><label for="newsubaddr">'.htmlentities(JText::_('PCEMAIL')).': </label>';
    echo "<div><input type=\"text\" name=\"newsubscribe\" id=\"newsubaddr\" size=\"60\" class=\"inputbox\" value=\"".$user->get('email')."\" onBlur=\"javascript:PCchgClassNameOnBlur('newsubaddr');\" /></div>";
    echo '<div style="padding-left:10px;"><input type="radio" name="subscribe" value="subscribesubmit" style="margin:0 2px 0 0" checked="checked" onclick="javascript:document.adminForm.task.value=this.value;"/>'.htmlentities(JText::_('PCSUBSCRIBE'),ENT_COMPAT,'UTF-8');
    echo '<input type="radio" name="subscribe" value="unsubscribesubmit" style="margin:0 2px 0 10px" onclick="javascript:document.adminForm.task.value=this.value;" />'.htmlentities(JText::_('PCUNSUBSCRIBE'),ENT_COMPAT,'UTF-8').'<br /><br /></div>';
    if(!$this->config_captcha_bypass || ($this->config_captcha_bypass && $user->guest)){
        echo $prayercenter->PCgetCaptchaImg();
        $js_script = 'return validateSub('.$this->config_captcha.', livesite, this.form, \'pccomp\')';
    } else {
        $js_script = 'return validateSub(0, livesite, this.form, \'pccomp\')';
        echo '<div><br /></div>';
    }
    echo '<div style="padding-left:10px;"><br /><button type="button" onclick="javascript:'.$js_script.';return false;">';
		echo htmlentities(JText::_('PCSUBMIT')).'</button>';
    if((real)$JVersion->RELEASE >= 2.5 ){
      $defaultcaptcha = JFactory::getConfig()->get('captcha');
    } else {
      $defaultcaptcha = "";
    }
    echo '<input type="hidden" name="jcap" id="jcap" class="inputbox" value="'.$defaultcaptcha.'" />';
    echo '<input type="hidden" name="option" value="com_prayercenter" />';
    echo '<input type="hidden" name="controller" value="prayercenter" />';
    echo '<input type="hidden" name="task" value="subscribesubmit" />';
	  echo JHTML::_( 'form.token' );
    echo '</form></div></fieldset>';
    echo '<br /></div>';
    $prayercenter->writePCFooter();
?>