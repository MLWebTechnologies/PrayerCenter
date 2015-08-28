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
    $document = JFactory::getDocument();
    $document->addScript('components/com_prayercenter/assets/js/pc.js');
    $document->addStyleSheet(JURI::base().'components/com_prayercenter/assets/css/prayercenter.css');
    jimport('joomla.filesystem.folder');
    if(!$this->prv) $prayercenter->PCgetAuth('view');
    $ulang = $prayercenter->PCgetUserLang();
    $itemid = $prayercenter->PCgetItemid();
    $JVersion = new JVersion();
    $dispatcher	= JDispatcher::getInstance();
    if(count($this->results)<1){
      echo '<div class="componentheading">'.htmlentities(JText::_('PCTITLE')).'</div>';
      echo '<h5><center>'.JText::_('JERROR_ALERTNOAUTHOR').'<br />'.JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST').'</center></h5>';
      echo '<br /><br /><br /><br />';
      echo '<div align="center"></div>';
    } else {
    $erequester = JText::_($this->results->requester);
    $eemail = $this->results->email;
    $etitle = JText::_($this->results->title);
    $etopicnum = $this->results->topic;
    $newtopicarray = $prayercenter->PCgetTopics();
    $etopic = $newtopicarray[$etopicnum+1]['text'];
    $dateTime = $prayercenter->PCgetTimeZoneData($this->results);
    if($etitle == "") $etitle = htmlentities(rtrim(JText::_('PCPRAYERREQUEST'),":"));
    if (!$this->pop){
      if(JRequest::getVar( 'return_msg', null, 'get', 'string' )) $prayercenter->PCReturnMsg(JRequest::getVar( 'return_msg', null, 'get', 'string' ));
      echo '<div>';
      if($this->config_show_page_headers) echo '<div class="componentheading"><h2>'.htmlentities($this->title.' - '.JText::_('PCVIEWREQUEST')).'</h2></div>';
      echo '<div>';
      $prayercenter->buildPCMenu();
      echo '</div><div>';
      $prayercenter->writePCImage().'</div><div>';
      echo $prayercenter->writePCHeader($prayercenter->PCkeephtml($this->intro)).'</div>';
    } else {
      echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    }
      echo '<fieldset class="pcmod">';
      echo '<legend>'.htmlentities(JText::_('PCVIEWREQUEST')).'</legend>';
      echo '<div class="showreqtable" style="width:100%;height="80px;"><div style="width:70%;float:left;">';
      echo '<div class="key">'.htmlentities(JText::_('PCDATE')).'</div><div class="key2">&nbsp;'.$dateTime['date'].'</div>';
      echo '<div class="key clr-left">'.htmlentities(JText::_('PCTIME')).'</div><div class="key2">&nbsp;'.$dateTime['time'].' ('.$dateTime['tz'].')</div>';
      if(empty($eemail)) $eemail = 'None';
      if($this->prv && $this->pop) echo '<div class="key">'.htmlentities(JText::_('PCPRAYERREQUESTEREMAIL')).'</div><div class="key2">&nbsp;'.$eemail.'</div>';
      if($this->config_show_requester && $this->config_show_comprofile && JFolder::exists('components/com_comprofiler') && $this->config_community == 1 || $this->config_show_requester && $this->config_show_comprofile && JFolder::exists('components/com_community') && $this->config_community == 2){
        echo '<div class="key clr-left">'.htmlentities(JText::_('PCPRAYERTOPIC')).'</div><div class="key2">&nbsp;'.$etopic.'</div>';
        echo '<div width="25%">&nbsp;</div><div width="200px">&nbsp;</div>';
      }
      if($this->config_show_requester && !$this->config_show_comprofile || $this->config_show_requester && !JFolder::exists('components/com_comprofiler') || $this->config_show_requester && !$this->config_community){
        echo '<div class="key clr-left">'.htmlentities(JText::_('PCPRAYERREQUESTER')).'</div><div class="key2">&nbsp;'.ucfirst($erequester).'</div>';
        echo '<div class="key clr-left">'.htmlentities(JText::_('PCPRAYERTOPIC')).'</div><div class="key2">&nbsp;'.$etopic.'</div>';
      }
      if(JFolder::exists('components/com_comprofiler') && $this->config_show_comprofile && !$this->pop && $this->config_community == 1){
        echo '</div><div class="profilebox">'.$prayercenter->PCgetProfileBox($this->results,true).'</div><p style="clear:left;line-height:0px;height:0px;"></p></div>';
      } elseif(JFolder::exists('components/com_community') && $this->config_show_comprofile && !$this->pop && $this->config_community == 2){
        echo '</div><div class="profilebox">'.$prayercenter->PCgetProfileBox($this->results,true).'</div><p style="clear:left;line-height:0px;height:0px;"></p></div>';
      } else {
        echo '</div><p style="clear:left;line-height:0px;height:0px;"></p></div>';
      }
  		$printimage = "";
      $print_link = "index.php?option=com_prayercenter&amp;task=view_request&amp;id=".$this->results->id."&amp;pop=1&amp;prt=1&amp;tmpl=component&amp;Itemid=".$itemid;
			$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=670,height=320,directories=no,location=no';
 			$image = JHTML::_('image',  JURI::base().'media/system/images/printButton.png', htmlentities(JText::_( 'PCPRINT' )), 'style="border:0;"');
      if($this->config_show_print && !$this->pop){
      		$attribs['title']	= htmlentities(JText::_( 'PCPRINT' ));
          if($this->config_use_gb){
            JHtml::_('behavior.modal');
            $attribs['rel'] = "{handler: 'iframe', size: {x: 800, y: 450}}";
            $attribs['class'] = 'modal'; 
          } else {
        		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
        		$attribs['rel'] = 'nofollow';
          }
      		$attribs['style'] = 'float:right;vertical-align:middle;';
    		$printimage = JHTML::_('link', JRoute::_($print_link), $image, $attribs);
		  }
      echo '<div class="clr-left">&nbsp;</div>';
      echo '<div class="key3 clr-left">'.ucfirst($etitle);
      echo $printimage;
      if($this->config_show_bookmarks && !$this->pop){
        $prayercenter->PCgetSocialBookmarks(true);
      }
      echo '</div>';
      if($this->config_enable_plugins && !empty($this->config_allowed_plugins)){
        foreach($this->config_allowed_plugins as $aplug){
      		JPluginHelper::importPlugin('content',$aplug);
        }
        $plugparams = new JObject();
        $tresults = $dispatcher->trigger('onContentPrepare', array ('text',&$this->results,&$plugparams,0));
      }
      if($this->config_use_wordfilter > 0) $this->results->text = $prayercenter->PCbadword_replace($this->results->text);
      echo '<div class="requestbox clr-left" id="pcrequest">'.$prayercenter->PCkeephtml(rtrim(stripslashes($this->results->text))).'</div>';
      if($this->config_show_translate && !$this->pop){
        $prayercenter->getTranslation($ulang,$this->eid);
        echo "<div><script>showLanguageDropDown('tol','".$ulang."');</script></div>";
      }
      if(!$this->pop) echo $prayercenter->PCgetComments($this->results,true);
      echo '<div><br />';
      if($this->pop){
        ?>
    		<button type="button" onclick="javascript:void window.print();return false;">
    			<?php echo htmlentities(JText::_( 'PCPRINT' ));?></button>
        <?php
        if($this->config_use_gb && $this->prt){
      		?><button type="button" name="closeedit" onclick="javascript:window.parent.SqueezeBox.close();">
      		<?php
        } else {
      		?><button type="button" name="closeedit" onclick="javascript:void window.parent.close();">
          <?php
        }
    		echo htmlentities(JText::_( 'PCCANCEL' ))."</button>";
      }
      echo '</div></fieldset><br />';
    if(!$this->pop) $prayercenter->writePCFooter();
    echo '</div>';
    }
?>