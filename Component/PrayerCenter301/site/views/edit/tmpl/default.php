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
  global $prayercenter, $editorcontent, $pc_rights;
    $JVersion = new JVersion();
		$user = JFactory::getUser();
		$edit_own = false;
    if($user->get('id') == (int)$this->editreq->requesterid) $edit_own = true; 
    $prayercenter->PCgetAuth('edit',$edit_own);
    $document = JFactory::getDocument();
    $document->addScript('components/com_prayercenter/assets/js/pc.js');
    $itemid = $prayercenter->PCgetItemid();
    $config_editor = $this->config_editor;
    $editorenabled = $prayercenter->PCcheckEditor($config_editor);
    if(!$editorenabled) $config_editor = 'none';
    $eid = JRequest::getVar('id',null,'request','int');
    $eid = JFilterOutput::cleanText($eid);
		$print_link = "index.php?option=com_prayercenter&amp;task=view_request&amp;id=".$eid."&amp;pop=1&amp;tmpl=component&amp;Itemid=".$itemid;
		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=670,height=320,directories=no,location=no';
 		$imgpath =  JURI::base().'media/system/images/';
		$image = JHTML::_('image',  $imgpath.'printButton.png', JText::_( 'PCPRINT' ),'style=border:0;' );
 		$attribs['title']	= JText::_( 'PCPRINT' );
    if($this->config_use_gb){
      JHtml::_('behavior.modal');
      $attribs['rel'] = "{handler: 'iframe', size: {x: 800, y: 450}}";
      $attribs['class'] = 'modal'; 
    } else {
   		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
   		$attribs['rel'] = 'nofollow';
    }
 		$attribs['style'] = 'float:right;margin-left:35px;vertical-align:middle;';
    $elast = JRequest::getVar('last',null,'get','string'); 
    $erequest = stripslashes(JText::_($this->editreq->request));
    $erequester = JText::_($this->editreq->requester);
    $eemail = $this->editreq->email;
    $dateTime = $prayercenter->PCgetTimeZoneData($this->editreq);
    $etime = $dateTime['time'];
    if($this->config_show_tz) $etime .= ' ('.$dateTime['tz'].')';
    $edate = $dateTime['date'];
    $etitle = JText::_($this->editreq->title);
    $etopicnum = $this->editreq->topic;
    $newtopicarray = $prayercenter->PCgetTopics();
    $etopic = $newtopicarray[$etopicnum+1]['text'];
    if($etitle == "") $etitle = htmlentities(JText::_('PCPRAYERREQUEST'));
    ?>
    <script type="text/javascript">
      var confirm_act = "<?php echo JText::_('USRLCONFIRMACT');?>";
      var ertask = "editrequest";
      var cetask = "closeedit";
      var edrtask = "editdelrequest";
      var uprtask = "unpubrequest";
    </script>
    <?php
    if($this->config_show_page_headers) echo '<div class="componentheading"><h2>'.htmlentities($this->title).'</h2></div>';
    echo '<div>';
    $prayercenter->buildPCMenu();
    echo '</div><div>';
    $prayercenter->writePCImage().'</div><div>';
    echo $prayercenter->writePCHeader($this->intro).'</div>';
    echo '<form method="post" action="'.$this->action.'" name="adminForm" id="adminForm">';
    echo "<input type=\"hidden\" name=\"requester\" size=\"30\" class=\"inputbox\" value=\"$erequester\" readonly=\"readonly\" />";
    echo "<input type=\"hidden\" name=\"date\" size=\"20\" class=\"inputbox\" value=\"$edate\" readonly=\"readonly\" />";
    echo "<input type=\"hidden\" name=\"time\" size=\"20\" class=\"inputbox\" value=\"$etime\" readonly=\"readonly\" />";
    echo "<input type=\"hidden\" name=\"id\" size=\"30\" class=\"inputbox\" value=\"$eid\">";
    echo "<input type=\"hidden\" name=\"last\" size=\"30\" class=\"inputbox\" value=\"$elast\">";
    echo '<fieldset class="pcmod">';
    if($this->editblock) {
      echo '<legend>'.htmlentities(JText::_('PCVIEWREQUEST')).'</legend>';
    } else {
      echo '<legend>'.htmlentities(JText::_('PCEDITREQUEST')).'</legend>';
    }
    echo '<div class="edittable">';
    echo '<div class="key">'.htmlentities(JText::_('PCDATE')).'</div><div class="key2">&nbsp;'.$edate.'</div>';
    echo '<div class="key">'.htmlentities(JText::_('PCTIME')).'</div><div class="key2">&nbsp;'.$etime.'</div>';
    echo '<div class="key">'.htmlentities(JText::_('PCPRAYERREQUESTER')).'</div><div class="key2">&nbsp;'.ucfirst($erequester).'</div>';
    if(empty($eemail)) $eemail = 'None';
    echo '<div class="key" nowrap>'.htmlentities(JText::_('PCPRAYERREQUESTEREMAIL')).'</div><div class="key2">&nbsp;'.$eemail.'</div>';
    echo '<div class="key">'.htmlentities(JText::_('PCPRAYERTOPIC')).'</div><div class="key2">&nbsp;'.$etopic.'</div>';
    echo '<div>&nbsp;</div>';
    echo '<div class="key3">'.ucfirst($etitle);
    echo JHTML::_('link', JRoute::_($print_link), $image, $attribs);
    echo '</div><div class="pcrequestbox">';
    echo $prayercenter->PCgetEditorBox($erequest);
    echo '</div>';
    if(!$this->editblock){
      echo "<div style=\"padding-left:10px;\"><button type=\"button\" onclick=\"javascript:document.adminForm.task.value=ertask;return validateEdit(this);return false;\">";
  		echo JText::_('PCSAVE').'</button>&nbsp;';
      echo "<button type=\"button\" onclick=\"javascript:document.adminForm.task.value=edrtask;return validateEdit(this);return false;\">";
  		echo JText::_('PCDELETE').'</button>&nbsp;';
      if(($elast != 'moderate') && $pc_rights->get('pc.moderate')){
        echo "<button type=\"button\" onclick=\"javascript:document.adminForm.task.value=uprtask;return validateEdit(this);return false;\">";
    		echo JText::_('PCUNPUBLISH').'</button>&nbsp;';
    	}
      echo "<button type=\"button\" onclick=\"javascript:document.adminForm.task.value=cetask;document.adminForm.submit();\">";
  		echo JText::_('PCCANCEL').'</button></div>';
		}
    echo '<input type="hidden" name="option" value="com_prayercenter" />';
    echo '<input type="hidden" name="controller" value="prayercenter" />';
    echo '<input type="hidden" name="task" value="" />';
	  echo JHTML::_( 'form.token' );
    echo '</fieldset></form>';
    echo '</div><br />';
    $prayercenter->writePCFooter();
?></div>