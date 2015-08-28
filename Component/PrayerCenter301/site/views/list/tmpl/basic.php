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
    $prayercenter->PCgetAuth('view');
    echo '<script language="JavaScript" type="text/javascript" src="components/com_prayercenter/assets/js/pc.js"></script>';
    $itemid = $prayercenter->PCgetItemid();
  	JHTML::_('behavior.tooltip');
    jimport('joomla.environment.browser');
    $browser = JBrowser::getInstance();
    jimport('joomla.html.pagination');
    $JVersion = new JVersion();
    $dispatcher	= JDispatcher::getInstance();
  	$pageNav = new JPagination( $this->total, $this->limitstart, $this->limit  );
    if(JRequest::getVar( 'return_msg', null, 'get', 'string' )) $prayercenter->PCReturnMsg(JRequest::getVar( 'return_msg', null, 'get', 'string' ));
    echo '<div>';
    if($this->config_show_page_headers) echo '<div class="componentheading"><h2>'.htmlentities($this->title.' - '.JText::_('PCVIEWLIST')).'</h2></div>';
    echo '<div>';
    $prayercenter->buildPCMenu();
    echo '</div><div>';
    $prayercenter->writePCImage().'</div><div>';
    echo $prayercenter->writePCHeader($this->intro).'</div>';
    echo '<fieldset class="pcmod"><legend>'.htmlentities(JText::_('PCVIEWLIST')).'</legend>';
    if ($this->total < 1){
      echo '<br /><table class="modlist">';
      if($this->totalresults > 0){
        echo '<thead><tr><th style="text-align:left;width:20%;" colspan="2">';
        echo $prayercenter->PCgetSearchbox();
        echo '</th></tr></thead>';
        echo '<tbody><tr class="row1"><td colspan="3"><strong><center><br /><br />'.htmlentities(JText::_('PCNOREQUESTSORT')).'<br /><br /></center></strong><br /></td></tr></tbody>';
        echo '<tfoot><tr><td style="width:25%;font-size:x-small;">';
        echo $prayercenter->PCgetSortbox($this->action,$this->sort);
        echo '</td><td>&nbsp;';
        echo '</td></tr></tfoot>';
      } else {
        echo '<thead><tr><th>';
        echo '&nbsp;</th>';
        echo '</tr></thead>';
        echo '<tbody><tr class="row1"><td colspan="2"><strong><center><br /><br />'.htmlentities(JText::_('PCNOREQUEST')).'<br /><br /></center></strong><br /></td></tr></tbody>';
        echo '<tfoot><tr><td>&nbsp;<br />';
        echo '</td></tr></tfoot>';
      } 
      echo '</table><br />';
    } else {
    echo '<div><br /><span style="width:25%;float:left;">';
    echo $prayercenter->PCgetSearchbox();
    echo '</span><span style="text-align:left;">';
      if($this->config_show_dwprint){
        echo $prayercenter->PCgetDWPrintButtons();
      }
    echo '</span><span style="float:right;">';
     if($this->config_show_bookmarks){
       $prayercenter->PCgetSocialBookmarks(false);
     }
    echo '</span></div><br />';
    }
    echo '<div class="modlistbasic" style="height:auto;">';
    $i=1;
    if ($this->total>0 ){
      $showresults = array_slice($this->results,$this->limitstart,$this->limit);
      foreach($showresults as $showrequest){
        $evenodd = $i % 2;
        if ($evenodd == 1) {
          $usrl_class = "row1";
        } else {
          $usrl_class = "row0";
        }
        echo '<div style="text-align:left;" class="'.$usrl_class.'">';
        $showrequest->text = $prayercenter->PCgetSizeRequest($showrequest);
        if($this->config_enable_plugins && !empty($this->config_allowed_plugins)){
          foreach($this->config_allowed_plugins as $aplug){
        		JPluginHelper::importPlugin('content',$aplug);
          }
          $plugparams = new JObject();
          $tresults = $dispatcher->trigger('onContentPrepare', array ('text',&$showrequest,&$plugparams,0));
        }
        if($this->config_use_wordfilter > 0) $showrequest->text = $prayercenter->PCbadword_replace($showrequest->text);
        echo '<div class="titlebasic">';
        if($showrequest->title == ''){
          echo '<a href="'.JRoute::_("index.php?option=com_prayercenter&task=view_request&id=$showrequest->id&pop=0&Itemid=$itemid").'" />'.htmlentities(rtrim(JText::_('PCPRAYERREQUEST'),":")).'</a>';
        } else {
          echo '<a href="'.JRoute::_("index.php?option=com_prayercenter&task=view_request&id=$showrequest->id&pop=0&Itemid=$itemid").'" />'.ucfirst($showrequest->title).'</a>';
        }
        echo '<span style="float:right;">';
        $prayercenter->PCgetButtons($showrequest,true);
        echo '&nbsp;&nbsp;</span></div>';
        echo '<div class="contentbasic">';
        echo $showrequest->text;
        echo '</div>';
        $showcomments_link = $prayercenter->PCgetComments($showrequest);
        if($this->config_show_viewed || $this->config_show_commentlink) echo '<div class="viewedcommentbasic" style="float:left;padding-left:6px;">';
        if($this->config_show_viewed) echo htmlentities(JText::_('PCVIEWED')).'&nbsp;('.$showrequest->hits.')';
        if($this->config_show_viewed && $this->config_show_commentlink && $this->config_comments) echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        if($this->config_show_commentlink) echo $showcomments_link;
        if($this->config_show_viewed || $this->config_show_commentlink) echo '</div>';
        if($this->config_show_requester || $this->config_show_date) echo '<div class="viewedcommentbasic" style="text-align:right;font-size:x-small;padding-right:4px;">';
        if($this->config_show_requester) echo htmlentities(JText::_('PCPOSTEDBY')).'&nbsp;'.$prayercenter->PCgetProfileLink($showrequest,false);
        if($this->config_show_requester && $this->config_show_date) echo ', ';
        if($this->config_show_date) {
          $dateTime = $prayercenter->PCgetTimeZoneData($showrequest);
          echo $dateTime['date'].' - '.$dateTime['time'];
        }
        if($this->config_show_requester || $this->config_show_date) echo '</div>';
       $i++;
      echo '</div><br />';
      }
    echo '</fieldset>';
    echo '<div><span style="width:25%;float:left;padding-top:9px;">';
    echo $prayercenter->PCgetSortbox($this->action,$this->sort);
		echo '</span>';
    if($this->config_show_tz){
      echo '<span style="float:right;padding-top:9px;" class="date"><b>Timezone:</b> '.$dateTime['tzid'].' ('.$dateTime['tz'].')</span>';
    }
    echo '</div><div><br /><br /><br /><center><form method="post" action="'.$this->action.'" name="lboxlist" id="lboxlist">';
		echo '<span class="pcpagelinks">'.$pageNav->getListFooter().'</span>';
    echo '</span></form>';
    echo '</center></div>';
    echo '<div style="clear:both;"><br/><br/></div>';
   }
   echo '</div>';
  $prayercenter->writePCFooter();
?>