<?php
/* *************************************************************************************
Title          PrayerCenter Component for Joomla
Author         Mike Leeper
Enhancements   Christina Ishii
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
defined( '_JEXEC' ) or die( 'Restricted access' );
global $pc_rights;
class prayercenter {
  var $pc_rights = null;
  function intializePCRights(){
    global $pcConfig;
    $config_moderator_list = strip_tags($pcConfig['config_moderator_user_list']);
    $moderatorArray = preg_split('/[,]/',$config_moderator_list, -1, PREG_SPLIT_NO_EMPTY);
    $user = JFactory::getUser();
    $pc_rights = new JObject();
    foreach($moderatorArray as $mod){
     preg_match('#(\d+)[-]#',$mod, $matches);
     if((int)$matches[1] == $user->get('id')){
          $pc_rights->set('pc.moderate',	true);
     }
    }
    if(JAccess::check($user->get('id'), 'prayercenter.view', 'com_prayercenter')) $pc_rights->set('pc.view',	true);
    if(JAccess::check($user->get('id'), 'prayercenter.post', 'com_prayercenter')) $pc_rights->set('pc.post',	true);
    if(JAccess::check($user->get('id'), 'prayercenter.publish', 'com_prayercenter')) $pc_rights->set('pc.publish',	true);
    if(JAccess::check($user->get('id'), 'prayercenter.subscribe', 'com_prayercenter')) $pc_rights->set('pc.subscribe',	true);
    if(JAccess::check($user->get('id'), 'prayercenter.devotional', 'com_prayercenter')) $pc_rights->set('pc.view_devotional',	true);
    if(JAccess::check($user->get('id'), 'prayercenter.links', 'com_prayercenter')) $pc_rights->set('pc.view_links',	true);
    $this->pc_rights = $pc_rights;
    return $pc_rights;
  }
  function PCRedirect($str,$msg=null) {
		$app = JFactory::getApplication();
		$app->redirect($str,$msg);
  }
  function PCgetTopics(){
    $topicArray = array (
           1 => array ('val' => '0', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC0')).''),         
           2 => array ('val' => '1', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC1')).''),         
           3 => array ('val' => '2', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC2')).''),         
           4 => array ('val' => '3', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC3')).''),         
           5 => array ('val' => '4', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC4')).''),         
           6 => array ('val' => '5', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC5')).''),         
           7 => array ('val' => '6', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC6')).''),         
           8 => array ('val' => '7', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC7')).''),         
           9 => array ('val' => '8', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC8')).''),         
           10 => array ('val' => '9', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC9')).''),         
           11 => array ('val' => '10', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC10')).''),         
           12 => array ('val' => '11', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC11')).''),         
           13 => array ('val' => '12', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC12')).''),         
           14 => array ('val' => '13', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC13')).''),         
           15 => array ('val' => '14', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC14')).''),         
           16 => array ('val' => '15', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC15')).''),         
           17 => array ('val' => '16', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC16')).''),         
           18 => array ('val' => '17', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC17')).''),         
           19 => array ('val' => '18', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC18')).''),         
           20 => array ('val' => '19', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC19')).''),         
           21 => array ('val' => '20', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC20')).''), 
           22 => array ('val' => '21', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC21')).''),       
           23 => array ('val' => '22', 'text' => ''.htmlentities(JText::_('PCSELECTTOPIC22')).'')       
            );
    return $topicArray;
  }
  function getTranslation($ulang,$reqid){
    global $pcConfig;
    $translator = $pcConfig['config_show_translate'];
    ?><script type="text/javascript">var langtranmsg = "<?php echo JText::_('PCSELECTTRANS');?>";</script><?php
    if($translator == 1 || $translator == 4){
      //Google Translate v2
      echo "<br /><a href=\"http://translate.google.com\"><img src=\"http://www.google.com/intl/".$ulang."/images/logos/translate_logo_sm.png\" style=\"height:15px;margin-top:2px;vertical-align:middle;border:0;\" title=\"Google Translate\" /></a>&nbsp;&nbsp;";
      if($translator == 1){
        echo "<select style=\"font-size:7pt;\" id=\"tol\" value=\"\" onChange=\"javascript:getTranslator2('".$ulang."',".$reqid.",'".JURI::base()."');\" title=\"".JText::_('PCPOPUPBLOCKER')."\"></select>";
      } elseif($translator == 4){
        echo "<select style=\"font-size:7pt;\" id=\"tol\" value=\"\" onChange=\"javascript:getTranslator('".$ulang."');\"></select>";
      }
      $document = JFactory::getDocument();
      $document->addScript('components/com_prayercenter/assets/js/gtranslate.js');
    } elseif($translator == 2 || $translator == 5){
      //Microsoft Bing Translator
      echo "<br /><a href=\"http://www.bing.com/translator//\"><img src=\"".JURI::base()."components/com_prayercenter/assets/fe-images/bing-logo.png\" style=\"height:15px;margin-top:2px;vertical-align:middle;border:0;\" title=\"Bing Translator\" /></a><span style=\"color:orange;font-size:7pt;font-weight:bold;\">Translator</span>&nbsp;";
      if($translator == 2){
        echo "<select style=\"font-size:7pt;\" id=\"tol\" value=\"\" onChange=\"javascript:getTranslator2(".$reqid.",'".JURI::base()."');\" title=\"".JText::_('PCPOPUPBLOCKER')."\"></select>";
      } elseif($translator == 5){
        echo "<select style=\"font-size:7pt;\" id=\"tol\" value=\"\" onChange=\"javascript:getTranslator();\"></select>";
      }
      $document = JFactory::getDocument();
      $document->addScript('components/com_prayercenter/assets/js/mstranslate.js');
    }
  }
  function PCgetButtons($showrequest,$editonly=false){
    global $pcConfig;
    $user = JFactory::getUser();
    $itemid = $this->PCgetItemid();
    jimport('joomla.environment.browser');
    jimport('joomla.user.helper');
    $browser = JBrowser::getInstance();
    $app = JFactory::getApplication();
    $JVersion = new JVersion();
 		$imgpath =  '/media/system/images/';
    if(!$editonly){
    	$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
    	$pdf_link 	= 'index.php?option=com_prayercenter&amp;task=pdf&amp;listtype=0&amp;id='. $showrequest->id .'&amp;title='. htmlentities(JText::_('PCTITLE')).'&amp;format=pdf';
    	$image = JHTML::_('image', 'pdf_button.png', $imgpath, NULL, NULL, htmlentities(JText::_('PCPDF')),'style=vertical-align:top;border:0;');
      if($pcConfig['config_show_pdf']){
        $user_browser = $browser->getBrowser().$browser->getMajor();
        $user_browser = strtolower($user_browser);
          if ($user_browser != 'msie7') {
            $pdfattribs['target'] = '_blank';
          } else {                     
        		$pdfattribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
          }
    		$pdfattribs['title']	= htmlentities(JText::_( 'PCPDF' ));
    		$pdfattribs['rel']     = 'nofollow';
    		echo JHTML::_('link', JRoute::_($pdf_link), $image, $pdfattribs);
        echo '&nbsp;';
    	 }
    		$print_link = "index.php?option=com_prayercenter&amp;task=view_request&amp;id=".$showrequest->id."&amp;pop=1&amp;prt=1&amp;tmpl=component&amp;Itemid=".$itemid;
    		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
    		$image = JHTML::_('image',  'printButton.png', $imgpath, NULL, NULL, htmlentities(JText::_( 'PCPRINT' )),'style=vertical-align:top;border:0;');
        if($pcConfig['config_show_print']){
      		$prtattribs['title']	= htmlentities(JText::_( 'PCPRINT' ));
          if($pcConfig['config_use_gb']){
            JHtml::_('behavior.modal');
            $prtattribs['rel'] = "{handler: 'iframe', size: {x: 800, y: 450}}";
            $prtattribs['class'] = 'modal'; 
          } else {
         		$prtattribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
         		$prtattribs['rel'] = 'nofollow';
          }
      		echo JHTML::_('link', JRoute::_($print_link), $image, $prtattribs);
          echo '&nbsp;';
    		}
      $sitename = $app->getCfg('sitename');;
      $mailto = str_replace('%s',$sitename,htmlentities(JText::_('PCMAILTO')));
    	$status = 'width=400,height=300,menubar=yes,resizable=yes';
      $link = $mailto.htmlentities($showrequest->request, ENT_QUOTES);
    	$image = JHTML::_('image', 'emailButton.png', $imgpath, NULL, NULL, htmlentities(JText::_('PCSENDEMAIL')),'style=vertical-align:top;border:0;');
    	if($pcConfig['config_show_email']){
    		$mtattribs['title']	= htmlentities(JText::_( 'PCSENDEMAIL' ));
    		$mtattribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
    		echo JHTML::_('link', JRoute::_($link), $image, $mtattribs);
      }
  	}
    if ( ($pcConfig['config_use_admin_alert'] > 1 && $this->pc_rights->get('pc.moderate')) || ($showrequest->requesterid == $user->get('id') && $user->get('id') > 0) ){
  		$icon = $showrequest->publishstate ? 'edit.png' : 'edit_unpublished.png';
  		$link 	= 'index.php?option=com_prayercenter&task=edit&last=view&id='.$showrequest->id.'&Itemid='.$itemid;
  		$image 	= JHTML::_('image', $icon, $imgpath, NULL, NULL, htmlentities(ucfirst(JText::_('PCEDIT'))),'style=vertical-align:top;border:0;');
      $button = JHTML::_('link', JRoute::_($link), $image);
  		$output = '<span class="hasTip" title="'.ucfirst(JText::_('PCEDIT')).'">'.$button.'</span>';
      echo '&nbsp;'.$output;
      }
  }
  function PCgetProfileBox($requestarr,$showavatar=true){
    global $pcConfig;
    $user = JFactory::getUser();
    $db = JFactory::getDBO();
    $livesite = JURI::base();
    $requester = ucfirst($requestarr->requester);
    $reqemail = $requestarr->email;
    $reqid = $requestarr->requesterid;
    //Community Builder Profile
    if($pcConfig['config_community'] == 1){
      if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
        if ( ! file_exists( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' ) ) {
  //        echo 'Community Builder component is not installed';
          return;
        }
      }
      global $_CB_framework, $_CB_database, $ueConfig, $_SERVER;
      include_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' );
      cbimport( 'cb.tabs' );
  		cbimport( 'cb.database' );
      cbimport( 'language.front' );
      $db->setQuery("SELECT COUNT(*) FROM #__comprofiler_plugin WHERE element='profileflags'");
      $flagsplugin = $db->loadResult();
      if($reqid == 0 || $reqid == null){
        $db->setQuery("SELECT COUNT(*) FROM #__users WHERE name='".$requester."'");
        $reqrcount = $db->loadResult();
        if($reqrcount == 1){
          $reqid = $_CB_framework->getUserIdFrom('name',$requester);
          $db->setQuery("SELECT COUNT(*) FROM #__prayercenter WHERE requester='".$requester."'");
          $reqcount = $db->loadResult();
        }
        if($reqrcount > 1){ 
          $reqid = $_CB_framework->getUserIdFrom('email',$reqemail);
          if(count($reqid) < 1){
            $reqid = 0;
            $reqcount = 0;
          } else {
            $db->setQuery("SELECT COUNT(*) FROM #__prayercenter WHERE email='".$reqemail."'");
            $reqcount = $db->loadResult();
          } 
        } else $reqcount = 0;
      } else {
        $db->setQuery("SELECT COUNT(*) FROM #__prayercenter WHERE requesterid='".$reqid."'");
        $reqcount = $db->loadResult();
      }
      if($flagsplugin){
        $fcountry = ", #__flags_countries.Location AS countryloc, #__flags_countries.Flag AS countryflag ";
        $cjoin = "INNER JOIN #__flags_countries ON #__comprofiler.country=#__flags_countries.Location ";
      } else {
        $fcountry = "";
        $cjoin = "";
      }
      $db->setQuery("SELECT #__comprofiler.hits$fcountry FROM #__comprofiler $cjoin WHERE user_id='".$reqid."'");
      $cbresults = $db->loadObjectList();
      $cbcount = count($cbresults);
      if($reqid > 0){
        $cbUser	= CBuser::getInstance( $reqid );
      }
      $isOnline = $_CB_framework->userOnlineLastTime($reqid);
      $cbprofile_link = $_CB_framework->userProfileUrl($reqid);
      $db->setQuery("SELECT (SELECT accepted FROM #__comprofiler_members WHERE referenceid='".$reqid."' AND memberid='".$_CB_framework->myId()."') AS accepted,pending,membersince,type,description FROM #__comprofiler_members WHERE memberid='".$reqid."' AND referenceid='".$_CB_framework->myId()."'");
      $cbconnect = $db->loadObject();
      $results = '<b>'.htmlentities(JText::_('PCOVERLIBSUBBY')).'</b><br />';
      if(!$reqid){
        if($showavatar) {
          $noavatar = $livesite.'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';
          $results .= "<img src=\"".$noavatar."\" alt=\"".$requester."\" title=\"".$requester."\" class=\"profileimage\" />&nbsp;".$requester;
        } else {
          $results .= $requester;
        }
      } elseif($showavatar) {
        $cbAvatarPath	=	$cbUser->avatarFilePath();
        if ( $ueConfig['allowConnections'] && $_CB_framework->myId() != $reqid && $cbconnect && $_CB_framework->myId() > 0 ) {
    			$tipTitle	=	_UE_CONNECTEDDETAIL;
    			$tipField	=	"<b>"._UE_CONNECTEDSINCE."</b> : ".dateConverter( $cbconnect->membersince, 'Y-m-d', $ueConfig['date_format'] );
    			if ( getLangDefinition( $cbconnect->type ) != null ) {
    				$tipField	.=	"<br /><b>"._UE_CONNECTIONTYPE."</b>&nbsp;:&nbsp;".getConnectionTypes( $cbconnect->type );
    			}
    			if ( $cbconnect->description != null ) {
    				$tipField	.=	"<br /><b>"._UE_CONNECTEDCOMMENT."</b>&nbsp;:&nbsp;".htmlspecialchars( $cbconnect->description );
    			}
          $cbcount ? $tipField .= "<br /><b>"._UE_HITS_DESC."</b>&nbsp;:&nbsp;".$cbresults[0]->hits : $tipField .= "<br />";
    			$htmltext	=	"<img src=\"".$cbAvatarPath."\" class=\"profileimage\" />";
    			$tooltipAvatar = cbFieldTip( 1, $tipField, $tipTitle, '250', '', $htmltext, '', '', '', false );
          $results .= "&nbsp;&nbsp;".$tooltipAvatar.ucfirst($requester);
        } elseif ( $ueConfig['allowConnections'] && $_CB_framework->myId() != $reqid && !$cbconnect && $_CB_framework->myId() > 0 ) {
    			$tipTitle	=	_UE_CONNECTEDDETAIL;
    			$tipField	=	"<b>"._UE_NODIRECTCONNECTION."</b>";
          $cbcount ? $tipField .= "<br /><b>"._UE_HITS_DESC."</b>&nbsp;:&nbsp;".$cbresults[0]->hits : $tipField .= "<br />";
    			$htmltext	=	"<img src=\"".$cbAvatarPath."\" class=\"profileimage\" />";
    			$tooltipAvatar = cbFieldTip( 1, $tipField, $tipTitle, '250', '', $htmltext, '', '', '', false );
          $results .= "&nbsp;&nbsp;".$tooltipAvatar.ucfirst($requester);
        } elseif(!$ueConfig['allowConnections'] && $_CB_framework->myId() != $reqid && $_CB_framework->myId() > 0) {
          $results .= "&nbsp;&nbsp;<a href=\"".$cbprofile_link."\"><img src=\"".$cbAvatarPath."\" alt=\"".$requester."\" title=\"".ucfirst($requester)."\" class=\"profileimage\" />".ucfirst($requester)."</a>";
        } elseif($_CB_framework->myId() == $reqid && $_CB_framework->myId() > 0) {
          $results .= "&nbsp;&nbsp;<a href=\"".$cbprofile_link."\"><img src=\"".$cbAvatarPath."\" alt=\"".$requester."\" title=\"".ucfirst($requester)."\" class=\"profileimage\" />".ucfirst($requester)."</a>";
        } elseif($_CB_framework->myId() == 0) {
          $results .= "&nbsp;&nbsp;<img src=\"".$cbAvatarPath."\" alt=\"".$requester."\" title=\"".ucfirst($requester)."\" class=\"profileimage\" />".ucfirst($requester);
        }
        if ( $ueConfig['allow_onlinestatus'] == 1 && $_CB_framework->myId() > 0 ) {
          $isOnline ? $cbstatus = _UE_ISONLINE : $cbstatus = _UE_ISOFFLINE;
          $results .= "&nbsp;&nbsp;<span class=\"cb_".strtolower($cbstatus)."\" title=\"".ucfirst(strtolower($cbstatus))."\"><span>&nbsp;</span></span>";
        }
      if($flagsplugin && $cbcount && $showavatar){
        if(basename($cbresults[0]->countryflag) != 'none.gif' && basename($cbresults[0]->countryflag) != ''){
          $cimg = $livesite.'components/com_comprofiler/plugin/user/plug_cbprofileflags/countries/'.$cbresults[0]->countryflag;
          $results .= "<br />&nbsp;&nbsp;".$cbresults[0]->countryloc."&nbsp;<img src=\"".$cimg."\" title=\"".$cbresults[0]->countryloc."\" class=\"profileflag\" />";
        }
       }
      } else {
        $results .= "&nbsp;&nbsp;<a href=\"".$cbprofile_link."\">".$requester."</a>";
        if ( $ueConfig['allow_onlinestatus'] == 1 ) {
          $isOnline ? $cbstatus = _UE_ISONLINE : $cbstatus = _UE_ISOFFLINE;
          $results .= "&nbsp;&nbsp;<span class=\"cb_".strtolower($cbstatus)."\" title=\"".ucfirst(strtolower($cbstatus))."\"><span>&nbsp;</span></span>";
        }
      }
      $reqcount ? $results .= "<br />&nbsp;&nbsp;".ucfirst(strtolower(htmlentities(JText::_('PCPRAYERREQUESTS'))))."&nbsp;<a href=\"".JRoute::_('index.php?option=com_prayercenter&task=view&searchrequester='.$requester.'&searchrequesterid='.$reqid)."\">".$reqcount."</a>" : $results .= "<br />";
      if($flagsplugin && !$cbcount) $results .= "<br />";
      ?><script type="text/javascript">
      	function cbConnSubmReq() {
      		cClick();
      		document.connOverForm.submit();
      	}
      </script><?php	
      if($_CB_framework->myId() > 0){
      $results .= "<script type=\"text/javascript\" src=\"components/com_comprofiler/js/overlib_all_mini.js\"></script><script type=\"text/javascript\" src=\"components/com_comprofiler/js/overlib_anchor_mini.js\"></script><script type=\"text/javascript\" src=\"components/com_comprofiler/js/overlib_centerpopup_mini.js\"></script><br />";
        if($ueConfig['allowConnections'] && $_CB_framework->myId() != $reqid && $cbconnect){
          if($cbconnect->accepted && !$cbconnect->pending){
            $results .= "<a href=\"".$cbprofile_link."\"><img src=\"".$livesite."components/com_comprofiler/images/profiles.gif\" alt=\""._UE_VIEWPROFILE."\" title=\""._UE_VIEWPROFILE."\" /></a><span>&nbsp;</span>";
            $results .= "<a href=\"index.php?option=com_comprofiler&amp;act=connections&amp;task=removeConnection&amp;connectionid=".$reqid."\" onclick=\"return confirmSubmit();\" ><img src=\"".$livesite."components/com_comprofiler/images/publish_x.png\" border=\"0\" alt=\"" . _UE_REMOVECONNECTION . "\" title=\"" . _UE_REMOVECONNECTION . "\" /></a><span>&nbsp;</span>";
            if($ueConfig['allow_email'] == 1){
          		$cbemail = $cbUser->_cbuser->email;
              $linkItemImg = "<img src=\"".$livesite."components/com_comprofiler/images/email.gif\" border=\"0\" alt=\"" . _UE_SENDEMAIL . "\" title=\"" . _UE_SENDEMAIL . "\" />";
          		$linkItemSep = null;
          		$linkItemTxt = null;
          		switch ( $ueConfig['allow_email_display'] ) {
        					case 1:
        						$results .= moscomprofilerHTML::emailCloaking( htmlspecialchars( $cbemail ), 0 );
        						break;
        					case 2:
        						if ( ! $linkItemImg && $linkItemTxt == htmlspecialchars( $cbemail ) ) {
        							$results .= moscomprofilerHTML::emailCloaking( htmlspecialchars( $cbemail ), 1, '', 0 );
        						} elseif ( $linkItemImg && $linkItemTxt != htmlspecialchars( $cbemail ) ) {
        							$results .= moscomprofilerHTML::emailCloaking( htmlspecialchars( $cbemail ), 1, $linkItemImg . $linkItemSep . $linkItemTxt, 0, false );
        						} elseif ( $linkItemImg && $linkItemTxt == htmlspecialchars( $cbemail ) ) {
        							$results .= moscomprofilerHTML::emailCloaking( htmlspecialchars( $cbemail ), 1, $linkItemImg, 0, false ) . $linkItemSep;
        							$results .= moscomprofilerHTML::emailCloaking( htmlspecialchars( $cbemail ), 1, '', 0 );
        						} elseif ( ! $linkItemImg && $linkItemTxt != htmlspecialchars( $cbemail ) ) {
        							$results .= moscomprofilerHTML::emailCloaking( htmlspecialchars( $cbemail ), 1, $linkItemTxt, 0 );
        						}
        						break;
        					case 3:
        						$results .= "<a href=\""
        						. cbSef("index.php?option=com_comprofiler&amp;task=emailUser&amp;uid=" . $reqid . getCBprofileItemid(true))
        						. "\" title=\"" . _UE_MENU_SENDUSEREMAIL_DESC . "\">" . $linkItemImg . $linkItemSep;
        						if ( $linkItemTxt && ( $linkItemTxt != _UE_SENDEMAIL ) ) {
        							$results .= moscomprofilerHTML::emailCloaking( $linkItemTxt, 0 );
        						} else {
        							$results .= $linkItemTxt;
        						}
        						$results .=  "</a><span>&nbsp;</span>";
        						break;
        				}
            }
      			$pmIMG = '<img src="'.$livesite.'components/com_comprofiler/images/pm.gif" border="0" alt="' . _UE_PM_USER . '" title="' . _UE_PM_USER . '" />';
            $_CB_PMS = new cbPMS();
      			global $_CB_PMS;
      			$resultArray = $_CB_PMS->getPMSlinks($reqid, $_CB_framework->myId(),"","", 1);
          	$imgMode = 1;
        		if (count($resultArray) > 0) {
      				foreach ($resultArray as $res) {
      				 	if (is_array($res)) {
      						switch ($imgMode) {
      							case 0:
      								$linkItem=getLangDefinition($res["caption"]);
      							break;
      							case 1:
      								$linkItem=$pmIMG;
      							break;
      							case 2:
      								$linkItem=$pmIMG.' '.getLangDefinition($res["caption"]);
      							break;
      						}
      						$results .= "&nbsp;<a href=\"".cbSef($res["url"])."\" title=\"".getLangDefinition($res["tooltip"])."\">".$linkItem."</a>";
      				 	}
      				}
      			}			
          } elseif(!$cbconnect->accepted && $cbconnect->pending) {
            $results .= "<img src=\"".$livesite."components/com_comprofiler/images/pending.png\" title=\""._UE_CONNECTIONPENDING."\" /><span>&nbsp;</span>";
            $results .= "<a href=\"index.php?option=com_comprofiler&amp;act=connections&amp;task=removeConnection&amp;connectionid=".$reqid."\" onclick=\"return confirmSubmit();\" ><img src=\"".$livesite."components/com_comprofiler/images/publish_x.png\" border=\"0\" alt=\"" . _UE_REMOVECONNECTION . "\" title=\"" . _UE_REMOVECONNECTION . "\" /></a><span>&nbsp;</span>";
            $results .= "<a href=\"".$cbprofile_link."\"><img src=\"".$livesite."components/com_comprofiler/images/profiles.gif\" alt=\""._UE_VIEWPROFILE."\" title=\""._UE_VIEWPROFILE."\" /></a><span>&nbsp;</span>";
          }
        } elseif($ueConfig['allowConnections'] && $_CB_framework->myId() != $reqid && $reqid) {
          $results .= "<a href=\"javascript:void(0)\" onclick=\"return overlib('"._UE_CONNECTIONINVITATIONMSG."&lt;br /&gt;&lt;form action=&quot;".JURI::base()."/index.php?option=com_comprofiler&amp;act=connections&amp;task=addConnection&amp;connectionid=".$reqid."&amp;title=".rtrim(htmlentities(JText::_('PCPRAYERREQUEST')),':')."&quot; method=&quot;post&quot; id=&quot;connOverForm&quot; name=&quot;connOverForm&quot;&gt;"._UE_MESSAGE.":&lt;br /&gt;&lt;textarea cols=&quot;40&quot; rows=&quot;8&quot; name=&quot;message&quot;&gt;&lt;/textarea&gt;&lt;br /&gt;&lt;input type=&quot;button&quot; class=&quot;inputbox&quot; onclick=&quot;cbConnSubmReq();&quot; value=&quot;"._UE_SENDCONNECTIONREQUEST."&quot; /&gt;&nbsp;&nbsp;&lt;input type=&quot;button&quot; class=&quot;inputbox&quot; onclick=&quot;cClick();&quot;  value=&quot;"._UE_CANCELCONNECTIONREQUEST."&quot; /&gt;&lt;/form&gt;', STICKY, CAPTION,'".sprintf(_UE_CONNECTTO,$requester)."', CENTER,CLOSECLICK,CLOSETEXT,'CLOSE',WIDTH,350, ANCHOR,'cbAddConn',CENTERPOPUP,'LR','UR');\" name=\"cbAddConn\" title=\""._UE_ADDCONNECTIONREQUEST."\"><img src=\"".$livesite."components/com_comprofiler/images/newavatar.gif\" /></a><span>&nbsp;</span>";
          $results .= "<a href=\"".$cbprofile_link."\"><img src=\"".$livesite."components/com_comprofiler/images/profiles.gif\" alt=\""._UE_VIEWPROFILE."\" title=\""._UE_VIEWPROFILE."\" /></a><span>&nbsp;</span>";
        }
      }
      return $results;
      //JomSocial Profile
    } elseif($pcConfig['config_community'] == 2){  
      if($reqid == 0 || $reqid == null){
        $db->setQuery("SELECT COUNT(*) FROM #__users WHERE name='".$requester."'");
        $reqrcount = $db->loadResult();
        if($reqrcount == 1){
      		$db->setQuery("SELECT id FROM #__users WHERE name='".$requester."'");
  		    $reqid = $db->loadResult();
          $db->setQuery("SELECT COUNT(*) FROM #__prayercenter WHERE requester='".$requester."'");
          $reqcount = $db->loadResult();
        }
        if($reqrcount > 1){ 
      		$db->setQuery("SELECT id FROM #__users WHERE email='".$reqemail."'");
  		    $reqid = $db->loadResult();
          if(count($reqid) < 1){
            $reqid = 0;
            $reqcount = 0;
          } else {
            $db->setQuery("SELECT COUNT(*) FROM #__prayercenter WHERE email='".$reqemail."'");
            $reqcount = $db->loadResult();
          } 
        }
      } else {
        $db->setQuery("SELECT COUNT(*) FROM #__prayercenter WHERE requesterid='".$reqid."'");
        $reqcount = $db->loadResult();
      }
    	if ( file_exists( JPATH_BASE . '/components/com_community/libraries/core.php' ) ) 
      {
      	require_once( JPATH_ROOT . '/components/com_community/helpers/string.php' );
      	require_once( JPATH_BASE . '/components/com_community/libraries/core.php');
      	require_once( JPATH_ROOT . '/components/com_community/libraries/window.php' );
    	} else {
  //    	echo 'JomSocial component is not installed';
      	return;
      }
      if(!$reqid || $user->get('id') == 0){
        $results .= $requester;
      } else {
     		$jsuser = CFactory::getUser($reqid);
        $js_profile_link = CRoute::_('index.php?option=com_community&amp;view=profile&amp;Userid='.$reqid);
        if($showavatar && $user->get('id') > 0) {
          $jsavatar = '<img src="'.$jsuser->getThumbAvatar().'" alt="" border="0" title="'.$requester.'" class=\"profileimage\" />';
          $results = '<b>'.htmlentities(JText::_('PCOVERLIBSUBBY')).'</b><br />';
          $results .= "&nbsp;&nbsp;<a href=\"".$js_profile_link."\">".$jsavatar.ucfirst($requester)."</a>";
        } elseif(!$showavatar && $user->get('id') > 0) {
          $results = "&nbsp;&nbsp;<a href=\"".$js_profile_link."\">".$requester."</a>";
        }
        $isOnline = $jsuser->isOnline();
        $isOnline ? $jsstatus = 'online' : $jsstatus = 'offline';
        $results .= "&nbsp;<span class=\"cb_".$jsstatus."\" title=\"".ucfirst($jsstatus)."\"><span>&nbsp;</span></span>";
        $viewcount = $jsuser->getViewCount();
        $results .= "<br />&nbsp;&nbsp;Profile views: ".$viewcount;
        //Friend Count
        $friendcount = $jsuser->getFriendCount();
        $results .= "<br />&nbsp;&nbsp;Friends: ".$friendcount;
        //User Status (set by user)
        $userstatus = $jsuser->getStatus();
        $results .= "<br />&nbsp;&nbsp;User Status: ".$userstatus;
        //To retrieve any user-specific information from custom field 
  //      $data = $jsuser->getInfo('FIELD_CODE');
        $reqcount ? $results .= "<br />&nbsp;&nbsp;".ucfirst(strtolower(htmlentities(JText::_('PCPRAYERREQUESTS')))).":&nbsp;<a href=\"".JRoute::_('index.php?option=com_prayercenter&task=view&searchrequester='.$requester.'&searchrequesterid='.$reqid)."\">".$reqcount."</a>" : $results .= "<br />";
        //Send message
       	include_once( JPATH_ROOT . '/components/com_community/libraries/messaging.php' );
        $onclick = CMessaging::getPopup($reqid);
        $results .= '<br /><a href="javascript:void(0)" onclick="'. $onclick .'">Send message</a>';
      }
     return $results;
    }
  }
  function PCgetProfileLink($requestarr,$showavatar=true){
    global $pcConfig;
    jimport('joomla.filesystem.folder');
    $requester = ucfirst($requestarr->requester);
    $reqemail = $requestarr->email;
    $reqid = $requestarr->requesterid;
    $user = JFactory::getUser();
    $userid = $user->get('id');
    $db = JFactory::getDBO();
    $livesite = JURI::base();
    $cprofiler	= JFolder::exists('components/com_comprofiler');
    if($pcConfig['config_community'] == 1 && $cprofiler && $userid > 0){
      global $_CB_framework, $_CB_database, $ueConfig, $_SERVER;
      include_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' );
      cbimport( 'cb.tabs' );
  		cbimport( 'cb.database' );
      if($reqid == 0 || $reqid == null){
        $db->setQuery("SELECT COUNT(*) FROM #__users WHERE name REGEXP '".$requester."'");
        $reqrcount = $db->loadResult();
        if($reqrcount == 1){
          $reqid = $_CB_framework->getUserIdFrom('name',$requester);
        } elseif($reqrcount > 1){ 
          $reqid = $_CB_framework->getUserIdFrom('email',$reqemail);
        }
      }
      $cbprofile_link = $_CB_framework->userProfileUrl($reqid);
      $db->setQuery("SELECT COUNT(*) FROM #__comprofiler_plugin WHERE element='profileflags'");
      $flagsplugin = $db->loadResult();
      if($flagsplugin){
        $fcountry = ", #__flags_countries.Location AS countryloc, #__flags_countries.Flag AS countryflag ";
        $cjoin = "INNER JOIN #__flags_countries ON #__comprofiler.country=#__flags_countries.Location ";
      } else {
        $fcountry = "";
        $cjoin = "";
      }
      $db->setQuery("SELECT #__comprofiler.avatar$fcountry FROM #__comprofiler $cjoin WHERE user_id='".$reqid."' AND avatarapproved='1'");
      $cbresults = $db->loadObjectList();
      $cbcount = count($cbresults);
      if(!$reqid){
        if($showavatar) {
          $noavatar = $livesite.'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';
          $results = "<img src=\"".$noavatar."\" alt=\"".$requester."\" title=\"".$requester."\" class=\"profileimage\" />".$requester;
        } else {
          $results = $requester;
        }
      } elseif($showavatar) {
          if($cbcount){
            if($cbresults[0]->avatar == ''){
                $avatar = $livesite.'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';
            } else {
                $avatar = $livesite.'images/comprofiler/'.$cbresults[0]->avatar;
            }
          } else {
           $avatar = $livesite.'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';
          }           
         $results = "<a href=\"".$cbprofile_link."\"><img src=\"".$avatar."\" alt=\"".$requester."\" title=\"".$requester."\" class=\"profileimage\" />".$requester."</a>";
      } else {
        $results = "<a href=\"".$cbprofile_link."\">".$requester."</a>";
      }
      if($flagsplugin && $cbcount && $showavatar){
        if($cbresults[0]->countryflag != 'none.gif' && $cbresults[0]->countryflag != ''){
          $cimg = $livesite.'components/com_comprofiler/plugin/user/plug_cbprofileflags/countries/'.$cbresults[0]->countryflag;
          $results .= "<br /><b>".htmlentities(JText::_('PCREQLOCATION')).":</b>&nbsp;&nbsp;<img src=\"".$cimg."\" title=\"".$cbresults[0]->countryloc."\" class=\"profileflag\" />";
        }
       }
      return $results;
      //JomSocial
    } elseif($pcConfig['config_community'] == 2 && $userid > 0){  
      $db->setQuery("SELECT id FROM #__users WHERE name='".$requester."'");
      $reqid = $db->loadResult();
    	if ( file_exists( JPATH_BASE . '/components/com_community/libraries/core.php' ) ) 
      {
      	require_once( JPATH_ROOT . '/components/com_community/helpers/string.php' );
      	require_once( JPATH_BASE . '/components/com_community/libraries/core.php');
      	require_once( JPATH_ROOT . '/components/com_community/libraries/window.php' );
     		$jsuser = CFactory::getUser($reqid);
    	} else {
  //    	echo 'JomSocial component is not installed';
      	return;
      }
      if($showavatar) {
        $jsavatar = '<img src="'.$jsuser->getThumbAvatar().'" alt="" border="0" title="'.$requester.'" class=\"profileimage\" />';
        $js_profile_link = CRoute::_('index.php?option=com_community&amp;view=profile&amp;Userid='.$reqid);
        $results = '<b>'.htmlentities(JText::_('PCOVERLIBSUBBY')).'</b><br />';
        $results .= "&nbsp;&nbsp;<a href=\"".$js_profile_link."\">".$jsavatar.ucfirst($requester)."</a>";
      } else {
        $results = "<a href=\"".JRoute::_("index.php?option=com_comprofiler&task=userProfile&user=$reqid")."\">".$requester."</a>";
      }
      return $results;
    }
    return $requester;
  }
  function PCgetComments($showrequest,$showcomments=false){
    global $pcConfig;
    $return = "";
    $dispatcher	= JDispatcher::getInstance();
    $document = JFactory::getDocument();
    $user = JFactory::getUser();
    $itemid = $this->PCgetItemid();
    if($pcConfig['config_comments'] == 1){
      //JComments
      $jcomments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
      if (file_exists($jcomments)) {
        require_once($jcomments);
    		require_once (JCOMMENTS_BASE.'/jcomments.config.php');
    		require_once (JCOMMENTS_BASE.'/jcomments.class.php');
    		require_once (JCOMMENTS_HELPERS.'/content.php');
        include_once (JCOMMENTS_HELPERS.'/system.php');
    		$jcommentsconfig = JCommentsFactory::getConfig();
    		$jcommentsEnabled = JCommentsContentPluginHelper::isEnabled($showrequest, true);
    		$jcommentsDisabled = JCommentsContentPluginHelper::isDisabled($showrequest, true);
    		$jcommentsLocked = JCommentsContentPluginHelper::isLocked($showrequest, true);
    		$jcommentsconfig->set('comments_on', intval($jcommentsEnabled));
    		$jcommentsconfig->set('comments_off', intval($jcommentsDisabled));
    		$jcommentsconfig->set('comments_lock', intval($jcommentsLocked));
    		JCommentsContentPluginHelper::clear($showrequest, true);
        $commentsCount = JComments::getCommentsCount($showrequest->id, 'com_prayercenter');
        $showForm = ($jcommentsconfig->getInt('form_show') == 1) || ($jcommentsconfig->getInt('form_show') == 2 && $commentsCount == 0);
        $isEnabled = ($jcommentsconfig->getInt('comments_on', 0) == 1) && ($jcommentsconfig->getInt('comments_off', 0) == 0);
        $document->addScript(JCommentsSystemPluginHelper::getCoreJS());
        $document->addScript(JCommentsSystemPluginHelper::getAjaxJS());
        $tmpl = JCommentsFactory::getTemplate($showrequest->id, 'com_prayercenter');
        $tmpl->load('tpl_index');
        $tmpl->addVar('tpl_index', 'comments-css', 1);
        if ($jcommentsconfig->get('template_view') == 'tree') {
        	$tmpl->addVar('tpl_index', 'comments-list', $commentsCount > 0 ? JComments::getCommentsTree($showrequest->id, 'com_prayercenter') : '');
        } else {
        	$tmpl->addVar('tpl_index', 'comments-list', $commentsCount > 0 ? JComments::getCommentsList($showrequest->id, 'com_prayercenter') : '');
        }
        if($this->pc_rights->get('pc.post') == 1 && !$jcommentsLocked){
          $tmpl->addVar('tpl_index', 'comments-form', JComments::getCommentsForm($showrequest->id, 'com_prayercenter', $showForm));
        }
		    $tmpl->addVar('tpl_index', 'comments-gotocomment', 1);
        $result = '<br />'.$tmpl->renderTemplate('tpl_index');
        $tmpl->freeAllTemplates();
      }
      if(!$showcomments){
        if((file_exists($jcomments) && $jcommentsDisabled) || !file_exists($jcomments)){
          $jcomment = "";
        } else {
          $return = '<a href="'.JRoute::_("index.php?option=com_prayercenter&task=view_request&id=".$showrequest->id."&pop=0&Itemid=".$itemid).'#comments" />'.JText::_('PCCOMMENTS').'&nbsp;('. $commentsCount . ')</a>';
        }
      } else {
        $return = '<div>'.$result.'</div>';
      }
     return $return;
    }
    if($pcConfig['config_comments'] == 2){
      //JSiteComments
      $jsc = JPATH_SITE.'/components/com_jsitecomments/helpers/jsc_class.php';
      if(file_exists($jsc)) {
        require_once($jsc);
        $jsitecomments	= new jsitecomments();
        $commentsCount = $jsitecomments->JSCgetCommentsCount('com_prayercenter', $showrequest->id);
        if(!$showcomments){
          $return = '<a href="'.JRoute::_("index.php?option=com_prayercenter&task=view_request&id=".$showrequest->id."&pop=0&Itemid=".$itemid).'#comments" />'.JText::_('PCCOMMENTS').'&nbsp;('. $commentsCount . ')</a>';
        } else {
          $return = '<br /><a name="comments"></a>'.$jsitecomments->JSCshow('com_prayercenter', $showrequest->id);
        }
      }  
     return $return;
    }
  }
  function PCgetDWPrintButtons(){
    jimport('joomla.environment.browser');
    $browser = JBrowser::getInstance();
    $status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
    $pdf_link1 	= 'index.php?option=com_prayercenter&amp;task=pdf&amp;listtype=1';
    $pdf_link2 	= 'index.php?option=com_prayercenter&amp;task=pdf&amp;listtype=2';
    $JVersion = new JVersion();
    $image1 = JHTML::_('image', JURI::base().'media/system/images/printButton.png', htmlentities(JText::_('PCPRINTTODAY')),'style="border:0;"');
    $image2 = JHTML::_('image', JURI::base().'media/system/images/printButton.png', htmlentities(JText::_('PCPRINTWEEK')),'style="border:0;"');
    $user_browser = $browser->getBrowser().$browser->getMajor();
    $user_browser = strtolower($user_browser);
      if ($user_browser != 'msie7') {
        $attribs['target'] = '_blank';
      } else {                     
    		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
      }
  	$attribs['title']	= htmlentities(JText::_( 'PCPRINTTODAY' ));
  	$attribs['rel']     = 'nofollow';
  	$return = JHTML::_('link', JRoute::_($pdf_link1), $image1.'&nbsp;<small>'.htmlentities(JText::_('PCDAILY')).'</small>', $attribs);
    $return .= '&nbsp;&nbsp;&nbsp;';
  	$attribs['title']	= htmlentities(JText::_( 'PCPRINTWEEK' ));
  	$return .= JHTML::_('link', JRoute::_($pdf_link2), $image2.'&nbsp;<small>'.htmlentities(JText::_('PCWEEKLY')).'</small>', $attribs);
    return $return;
  }
  function PCgetSearchbox(){
    $return = '<div class="pcsearch" id="pcsearchbox"><form action="'.JRoute::_('index.php?option=com_prayercenter&task=view').'" name="searchPC" method="post">';
    $boxsize = strlen(htmlentities(JText::_('PCSEARCH...')));
    if ($boxsize <= 15) $boxsize = 15;
    $return .= '<span title="'.htmlentities(JText::_('PCSEARCHMSG')).'" class="popup"><input class="pc_search_inputbox" type="text" name="searchword" size="'.$boxsize.'" value="'.htmlentities(JText::_('PCSEARCH...')).'" onblur="if(this.value==\'\') this.value=\''.htmlentities(JText::_('PCSEARCH...')).'\';" onfocus="if(this.value==\''.htmlentities(JText::_('PCSEARCH...')).'\') this.value=\'\';" />';
    $return .= '</span></form></div>';
    return $return;
  }
  function PCgetSortbox($action,$sort){
    $return = '<div class="pcsort"><form method="post" action="'.$action.'" name="viewlist" id="viewlist">';
    $return .= "<input type=\"hidden\" id=\"sort\" name=\"sort\" size=\"5\" class=\"inputbox\" value=\"".$sort."\" />";
    $newtopicarray = $this->PCgetTopics();
    $return .= '<select name="sorter" onChange="var sortval=this.options[selectedIndex].value;sortingList(sortval);">';
    $topics = "";
    if($sort == 99) $topics = '<option value="-1">'.htmlentities(JText::_('PCSORTBY')).'</option>';
    foreach($newtopicarray as $nt){
      $tselected = "";
      if($sort == $nt['val']) $tselected = ' selected';
        $topics .= '<option value="'.$nt['val'].'"'.$tselected.'>'.$nt['text'].'</option>';
    }
    $topics .= '<option value="99">'.htmlentities(JText::_('PCSELECTTOPIC99')).'</option>';
    $return .= $topics;
    $return .= '</select>';
    $return .= '</form></div>';
    return $return;
  }
  function PCcheckEditor($config_editor){
    jimport( 'joomla.plugin.plugin' );
    $editorenabled = JPluginHelper::isEnabled('editors',$config_editor);
    return $editorenabled;
  }
  function PCgetEditorBox($text=null){
    global $pcConfig, $editorcontent;
		$conf = JFactory::getConfig();
    $config_show_xtd_buttons = $pcConfig['config_show_xtd_buttons'];
    $config_editor = $pcConfig['config_editor'];
    $config_editor_width = $pcConfig['config_editor_width'];
    $config_editor_height = $pcConfig['config_editor_height'];
		if (is_numeric( $config_editor_width )) {
			$config_editor_width .= 'px';
		}
		if (is_numeric( $config_editor_height )) {
			$config_editor_height .= 'px';
		}
    if($config_editor == 'default'){
      $config_editor = $conf->get('editor');
      $user = JFactory::getUser();
      $userid = $user->get('id');
      $juser = new JUser($userid);
      $usereditor = $juser->getParam('editor');
      if(!empty($usereditor)) $config_editor = $userparams->get('editor');
    }
    $editorenabled = $this->PCcheckEditor($config_editor);
    if($editorenabled && $config_editor != 'xinha'){ //Xinha editor is not currently supported.
      $editor = JEditor::getInstance($config_editor);
      $eparams = array('mode'=> $pcConfig['config_editor_mode']);
      if($config_editor == 'none'){
        $config_show_xtd_buttons = 0;
      }
      $return = $editor->display('newrequest', $text, $config_editor_width, $config_editor_height, '70', '15', $config_show_xtd_buttons, 'newrequest', '', '', $eparams);
      $editorcontent = $editor->getContent('newrequest');
    } else {
      $return = '<textarea name="newrequest" id="newrequest" cols="70" rows="15" style="width: '.$config_editor_width.'; height: '.$config_editor_height.';">'.$text.'</textarea>';
      $editorcontent = "document.getElementById('newrequest').value;";
    }
    return $return;
  }
  function PCstripslashes($str) {
    $cd1 = substr_count($str, "\"");
    $cd2 = substr_count($str, "\\\"");
    $cs1 = substr_count($str, "'");
    $cs2 = substr_count($str, "\\'");
    $tmp = strtr($str, array("\\\"" => "", "\\'" => ""));
    $cb1 = substr_count($tmp, "\\");
    $cb2 = substr_count($tmp, "\\\\");
    if ($cd1 == $cd2 && $cs1 == $cs2 && $cb1 == 2 * $cb2) {
      return strtr($str, array("\\\"" => "\"", "\\'" => "'", "\\\\" => "\\"));
    }
    return $str;
  }
  function PCgetSizeRequest($showrequest){
    global $pcConfig;
    $itemid = $this->PCgetItemid();
    $showrequest->text = preg_replace( "'<\/?p[^>]*>'si", '', $showrequest->text );
    if(($pcConfig['config_req_length'] > 0) && (strlen($showrequest->text) > $pcConfig['config_req_length'])){
      $showrequest->text = substr($showrequest->text, 0 , $pcConfig['config_req_length']-4) . " ...";
      $showrequest->text = $this->PCwordWrapIgnoreHTML($showrequest->text,65,'<br />');
      $return = '<div class="reqcontent">"'.$this->PCkeephtml(JText::_($this->PCstripslashes($showrequest->text))).'"<small>&nbsp;&nbsp;<a href="index.php?option=com_prayercenter&task=view_request&id='.$showrequest->id.'&Itemid='.$itemid.'" /><i><span style="white-space:nowrap;">'.htmlentities(JText::_('PCREADMORE')).'</span></i></a></small><br /><br />';
    } else {
      $return = '<div class="reqcontent">'.$this->PCkeephtml(JText::_($this->PCstripslashes($showrequest->text))).'<br /><br />';
    }
    $return .= '</div>';
    return $return;
  }
  function PCgetUserLang(){
    $user = JFactory::getUser();
    $userid = $user->get('id');
    $juser = new JUser($userid);
    $userfelang = $juser->getParam('language');
    if(!empty($userfelang)){
      preg_match("#([a-zA-Z])[^-]#",$userfelang,$felangmatches);
      $lcname = $felangmatches[0];
    } else {
      $langclient	= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
      $langparams = JComponentHelper::getParams('com_languages');
      $defaultfelang = $langparams->get($langclient->name, 'en-GB');
      preg_match("#([a-zA-Z])[^-]#",$defaultfelang,$felangmatches);
      $lcname = $felangmatches[0];
     }
    return $lcname;
  }
  function PCgetSocialBookmarks($bmshowreq){
    global $pcConfig;
    $service = $pcConfig['config_bm_service'];
    $serviceid = $pcConfig['config_bm_service_id'];
    $usegcode = $pcConfig['config_use_gcode'];
    $googleid = $pcConfig['config_google_id'];
    $bmlang = $this->PCgetUserLang();
    if($usegcode){
    ?>
    <!-- Google Analytics BEGIN --><script type="text/javascript">   var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));</script><script type="text/javascript"> try{ var pageTracker = _gat._getTracker("<?php echo $googleid;?>"); pageTracker._trackPageview(); } catch(err) {}</script><!-- Google Analytics END -->
    <?php
    }
    if($service == 1){
    //AddThis Service (http://www.addthis.com)
      $usegcode == 1 ? $addthisga = '<script type="text/javascript">var addthis_config={data_ga_tracker: pageTracker};</script>' : $addthisga = '';
      if($bmshowreq){
        echo '<div style="float:right;vertical-align:bottom;margin-right:5px;"><script type="text/javascript">var addthis_config = {ui_language:"'.$bmlang.'",services_exclude:"print,email"}</script><a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username='.$serviceid.'"><img src="http://s7.addthis.com/static/btn/v2/lg-share-'.$bmlang.'.gif" width="125" height="16" title="'.htmlentities(JText::_('PCBMSHAREREQ')).'" style="border:0;"/></a>'.$addthisga.'<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username'.$serviceid.'"></script></div>';
      } else {
        echo '<div style="float:right;vertical-align:bottom;"><script type="text/javascript">var addthis_config = {ui_language:"'.$bmlang.'",services_exclude:"print,email"}</script><a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username='.$serviceid.'"><img src="http://s7.addthis.com/static/btn/v2/lg-share-'.$bmlang.'.gif" width="125" height="16" title="'.htmlentities(JText::_('PCBMSHAREREQLIST')).'" style="border:0;"/>'.$addthisga.'</a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username'.$serviceid.'"></script></div>';
      }
    }
    if($service == 2){
    //AddToAny Service (http://www.addtoany.com)
      $usegcode == 1 ? $addtoanygq = '<script type="text/javascript">var a2a_config = a2a_config || {}; a2a_config.track_links = \'ga\';</script>' : $addtoanygq = '';
      if($bmshowreq){
       echo '<div style="float:right;vertical-align:bottom;"><style type="text/css">#a2apage_EMAIL {display:none !important;}</style><a class="a2a_dd" href="http://www.addtoany.com/share_save"><img src="http://static.addtoany.com/buttons/share_save_120_16.gif" width="120" height="16" border="0" title="'.htmlentities(JText::_('PCBMSHAREREQ')).'"/></a>'.$addtoanygq.'<script type="text/javascript" src="http://static.addtoany.com/menu/locale/'.$bmlang.'.js"></script><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script></div>';
      } else {
       echo '<div style="float:right;vertical-align:bottom;"><style type="text/css">#a2apage_EMAIL {display:none !important;}</style><a class="a2a_dd" href="http://www.addtoany.com/share_save"><img src="http://static.addtoany.com/buttons/share_save_120_16.gif" width="120" height="16" border="0" title="'.htmlentities(JText::_('PCBMSHAREREQLIST')).'"/></a>'.$addtoanygq.'<script type="text/javascript" src="http://static.addtoany.com/menu/locale/'.$bmlang.'.js"></script><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script></div>';
      }
    }
    if($service == 3){
    //ShareThis Service (http://www.sharethis.com)
      if($bmshowreq){
       echo '<div style="float:right;vertical-align:bottom;"><script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher='.$serviceid.'&amp;type=website&amp;buttonText='.htmlentities(JText::_('PCBMSHAREREQ')).'"></script></div>';
      } else {
       echo '<div style="float:right;vertical-align:bottom;"><script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher='.$serviceid.'&amp;type=website&amp;buttonText='.htmlentities(JText::_('PCBMSHAREREQLIST')).'"></script></div>';
      }
    }
    if($service == 4 && $serviceid){
    //TellAFriend/SocialTwist Service (http://tellafriend.socialtwist.com)
      $serviceid = $serviceid.'/';
      if($bmshowreq){
       echo '<div style="float:right;vertical-align:bottom;"><script type="text/javascript" src="http://cdn.socialtwist.com/'.$serviceid.'/script.js"></script><a class="st-taf" href="http://tellafriend.socialtwist.com:80" onclick="return false;" style="border:0;padding:0;margin:0;"><img alt="'.htmlentities(JText::_('PCBMSHAREREQ')).'" style="border:0;padding:0;margin:0;" src="http://images.socialtwist.com/'.$serviceid.'button.png"onmouseout="STTAFFUNC.hideHoverMap(this)" onmouseover="STTAFFUNC.showHoverMap(this, \''.$serviceid.'\', window.location, document.title)" onclick="STTAFFUNC.cw(this, {id:\''.$serviceid.'\', link: window.location, title: document.title });"/></a></div>';
      } else {
       echo '<div style="float:right;vertical-align:bottom;"><script type="text/javascript" src="http://cdn.socialtwist.com/'.$serviceid.'/script.js"></script><a class="st-taf" href="http://tellafriend.socialtwist.com:80" onclick="return false;" style="border:0;padding:0;margin:0;"><img alt="'.htmlentities(JText::_('PCBMSHAREREQLIST')).'" style="border:0;padding:0;margin:0;" src="http://images.socialtwist.com/'.$serviceid.'button.png"onmouseout="STTAFFUNC.hideHoverMap(this)" onmouseover="STTAFFUNC.showHoverMap(this, \''.$serviceid.'\', window.location, document.title)" onclick="STTAFFUNC.cw(this, {id:\''.$serviceid.'\', link: window.location, title: document.title });"/></a></div>';
      }
    }
  }
  function PCgetAuth($page=null,$edit_own=null){
    global $pcConfig;
    $itemid = $this->PCgetItemid();
    $user = JFactory::getUser();
    $returnmsg = JRequest::getVar( 'return_msg', null, 'get', 'string' );
    $user_allow_anonymous = false;
    if($page != null){
      $page = 'pc.'.$page;
      if (!$this->pc_rights->get($page) && !$edit_own && !$this->pc_rights->get('pc.moderate')) {
        if(empty($returnmsg)){
        	$returnurl = JRoute::_('index.php?option=com_prayercenter&Itemid='.$itemid);
        	$this->PCRedirect( $returnurl, JText::_('JERROR_ALERTNOAUTHOR') );
          return false;
        } else {
        	$returnurl = JRoute::_('index.php?option=com_prayercenter&Itemid='.$itemid.'&return_msg='.$returnmsg);
          $this->PCRedirect($returnurl);
          return true;
        }
     }
    }
   return true;
  }
  function PCReturnMsg($ret_msg){
    $return_msg = "";
    $return_msg = '<div class="return_msg"><hr><br /><center>'.$ret_msg.'</center><br /><hr></div>';
    echo $return_msg;
  }
  function PCgetCaptchaImg($action="pccomp",$form='adminForm'){
    global $pcConfig;
    $JVersion = new JVersion();
    $livesite = JURI::base();
    ?><script type="text/javascript">
      var livesite = "<?php echo $livesite;?>";
    </script><?php
    $insertimg = "";
    if ($pcConfig['config_captcha'] == 1) {
      if($action != 'pccomp'){
        $insertimg .= '<br /><br />';
        $insertimg .= htmlentities(JText::_('PCSECCODE')).'<br />';
        $imgid = $action.'_sec_image';
      }
      if($action == 'pccomp'){
         $insertimg .= '<div style="padding-left:10px;"><br />';
         $imgid = 'sec_image';
      }
      $insertimg .= '<img src="'.$livesite.'components/com_prayercenter/assets/captcha/prayercenter.captcha.inc.php?action='.$action.'" id="'.$imgid.'" border="1" />';
      $insertimg .= '&nbsp;&nbsp;';
      $imgpath = 'components/com_prayercenter/assets/fe-images/';
      $bparams = array();
      $bparams['width'] = '15';
      $bparams['border'] = '0';
      $attriba = array();
      $attriba['id'] = "reloadImage";
    	$imagea = JHTML::_('image', $imgpath.'redo.gif', htmlentities(JText::_('PCCAPTCHARELOAD')), $bparams );
      $linka = 'javascript:PCgetImage(livesite,\''.$action.'\');';
      $insertimg .= JHTML::_('link', JRoute::_($linka), $imagea, $attriba);
      if($action == 'pccomp'){
        $insertimg .= "</div>";
        $insertimg .= "<div style=\"padding-left:10px;padding-top:4px;\"><small>";
        $insertimg .= "<label for=\"security_code\" id=\"seccode\">".htmlentities(JText::_('PCCAPTCHAMSG'))."&nbsp;</label></small><input type=\"text\" id=\"security_code\" name=\"security_code\" size=\"10\" class=\"inputbox\" onBlur=\"javascript:PCchgClassNameOnBlur('security_code');\" /><br /></div>";
      } else {
        $insertimg .= '<br /><span title="'.htmlentities(rtrim(JText::_('PCCAPTCHAMSG'),":")).'" class="popup"><input type="text" id="security_code" name="security_code" size="13" /></span>';
        $insertimg .= '<br /><br />';
      }
      return $insertimg;
    } elseif ($pcConfig['config_captcha'] == 6 && $pcConfig['config_recap_pubkey'] != "" && $pcConfig['config_recap_privkey'] != ""){
      $config_recap_pubkey = $pcConfig['config_recap_pubkey'];
      $config_recap_theme = $pcConfig['config_recap_theme'];
      $recaplang = $this->PCgetUserLang();
      $insertimg .= '<div style="padding-left:10px;padding-top:4px;"><br />';
      if($config_recap_theme == 'clean') {
        $recapext = 'png';
      } else {
        $recapext = 'gif';
      }
      require_once(JPATH_ROOT.'/components/com_prayercenter/assets/captcha/recaptchalib.php');
      $insertimg .= '<style type="text/css">#recaptcha_image img {width:175px;border:1px solid #ccc;}</style>';
      $insertimg .= "<script type=\"text/javascript\">var widgetname = 'recaptcha_widget_".$action."';var RecaptchaOptions={theme:'custom', custom_theme_widget: widgetname, lang: '".$recaplang."'};</script>";
      $insertimg .= '<div id="recaptcha_widget_'.$action.'" style="display:none">';
      $insertimg .= '<div id="recaptcha_image"></div>';
      $insertimg .= '<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>';
      $insertimg .= '<span class="recaptcha_only_if_image">Enter the words above:</span>';
      $insertimg .= '<span class="recaptcha_only_if_audio">Enter the numbers you hear:</span><br />';
      $insertimg .= '<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />';
      $insertimg .= '<div><a href="javascript:Recaptcha.reload()"><img src="http://www.google.com/recaptcha/api/img/'.$config_recap_theme.'/refresh.'.$recapext.'" style="padding-left:5px;padding-top:4px;" title="Get another CAPTCHA" /></a><img style="float:right;padding-left:5px;position:absolute;padding-top:10px;" src="http://www.google.com/recaptcha/api/img/clean/logo.png" /></div>';
      $insertimg .= '<div class="recaptcha_only_if_image">';
      $insertimg .= "<a href=\"javascript:Recaptcha.switch_type('audio')\"><img src=\"http://www.google.com/recaptcha/api/img/".$config_recap_theme."/audio.".$recapext."\" style=\"padding-left:5px;\" title=\"Get an audio CAPTCHA\" /></a></div>";
      $insertimg .= '<div class="recaptcha_only_if_audio">';
      $insertimg .= "<a href=\"javascript:Recaptcha.switch_type('image')\"><img src=\"http://www.google.com/recaptcha/api/img/".$config_recap_theme."/text.".$recapext."\" style=\"padding-left:5px;\" title=\"Get an image CAPTCHA\" /></a></div>";
      $insertimg .= '<div><a href="javascript:Recaptcha.showhelp()"><img src="http://www.google.com/recaptcha/api/img/'.$config_recap_theme.'/help.'.$recapext.'" style="padding-left:5px;" title="Help" /></a></div></div>';
      $insertimg .= '<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k='.$config_recap_pubkey.'"></script>';
      $insertimg .= '<noscript><iframe src="http://www.google.com/recaptcha/api/noscript?k='.$config_recap_pubkey.'" height="300" width="500" frameborder="0"></iframe>';
      $insertimg .= '<br /><textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>';
      $insertimg .= '<input type="hidden" name="recaptcha_response_field" value="manual_challenge"></noscript>';
      $insertimg .= '</div>';
      if($action != 'pccomp') $insertimg .= '<br />';
      return $insertimg;
    } elseif($pcConfig['config_captcha'] == 7 && (real)$JVersion->RELEASE >= 2.5){
      if($action == 'pccomp' || ($action != 'pccomp' && JFactory::getConfig()->get('captcha') != 'recaptcha')){
        $plugin  = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
        $captcha = JCaptcha::getInstance($plugin, array('namespace' => 'adminForm'));
        echo '<br />'.$captcha->display('captcha', 'captcha');
      } elseif($action != 'pccomp' && JFactory::getConfig()->get('captcha') == 'recaptcha') {
    		$captchaplugin = JPluginHelper::getPlugin('captcha', JFactory::getConfig()->get('captcha'));
    		$captchaparams = new JRegistry($captchaplugin->params);
    		$captchaplugin->params = $captchaparams;
        $config_recap_pubkey = $captchaplugin->params->get('public_key');
        $config_recap_theme = $captchaplugin->params->get('theme');
        $recaplang = $this->PCgetUserLang();
        $insertimg .= '<div style="padding-left:10px;padding-top:4px;"><br />';
        if($config_recap_theme == 'clean') {
          $recapext = 'png';
        } else {
          $recapext = 'gif';
        }
        require_once(JPATH_ROOT.'/components/com_prayercenter/assets/captcha/recaptchalib.php');
        $insertimg .= '<style type="text/css">#recaptcha_image img {width:175px;border:1px solid #ccc;}</style>';
        $insertimg .= "<script type=\"text/javascript\">var widgetname = 'recaptcha_widget_".$action."';var RecaptchaOptions={theme:'custom', custom_theme_widget: widgetname, lang: '".$recaplang."'};</script>";
        $insertimg .= '<div id="recaptcha_widget_'.$action.'" style="display:none">';
        $insertimg .= '<div id="recaptcha_image"></div>';
        $insertimg .= '<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>';
        $insertimg .= '<span class="recaptcha_only_if_image">Enter the words above:</span>';
        $insertimg .= '<span class="recaptcha_only_if_audio">Enter the numbers you hear:</span><br />';
        $insertimg .= '<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />';
        $insertimg .= '<div><a href="javascript:Recaptcha.reload()"><img src="http://www.google.com/recaptcha/api/img/'.$config_recap_theme.'/refresh.'.$recapext.'" style="padding-left:5px;padding-top:4px;" title="Get another CAPTCHA" /></a><img style="float:right;padding-left:5px;position:absolute;padding-top:10px;" src="http://www.google.com/recaptcha/api/img/clean/logo.png" /></div>';
        $insertimg .= '<div class="recaptcha_only_if_image">';
        $insertimg .= "<a href=\"javascript:Recaptcha.switch_type('audio')\"><img src=\"http://www.google.com/recaptcha/api/img/".$config_recap_theme."/audio.".$recapext."\" style=\"padding-left:5px;\" title=\"Get an audio CAPTCHA\" /></a></div>";
        $insertimg .= '<div class="recaptcha_only_if_audio">';
        $insertimg .= "<a href=\"javascript:Recaptcha.switch_type('image')\"><img src=\"http://www.google.com/recaptcha/api/img/".$config_recap_theme."/text.".$recapext."\" style=\"padding-left:5px;\" title=\"Get an image CAPTCHA\" /></a></div>";
        $insertimg .= '<div><a href="javascript:Recaptcha.showhelp()"><img src="http://www.google.com/recaptcha/api/img/'.$config_recap_theme.'/help.'.$recapext.'" style="padding-left:5px;" title="Help" /></a></div></div>';
        $insertimg .= '<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k='.$config_recap_pubkey.'"></script>';
        $insertimg .= '<noscript><iframe src="http://www.google.com/recaptcha/api/noscript?k='.$config_recap_pubkey.'" height="300" width="500" frameborder="0"></iframe>';
        $insertimg .= '<br /><textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>';
        $insertimg .= '<input type="hidden" name="recaptcha_response_field" value="manual_challenge"></noscript>';
        $insertimg .= '</div>';
        $insertimg .= '<br />';
        return $insertimg;
      }
    }
  }
  function PCCaptchaValidate($usercode,$page){
    global $pcConfig;
    $session = JFactory::getSession();
    $maxattempts = $pcConfig['config_captcha_maxattempts'];
    $usercode = strtolower($usercode);
    $modtype = JRequest::getVar( 'modtype', null, 'get', 'string' );
    $mod = JRequest::getVar( 'mod', null, 'get', 'string' );
    $returnto = $_SERVER['HTTP_REFERER'];
    preg_match("!index.php\?!",$returnto,$match);
    if(!$match) $returnto = $returnto.'index.php?';
    if($session->get('pc_max_attempts')==$maxattempts) {
      if(isset($_GET['modtype'])){
        $this->PCRedirect($returnto.'&'.$modtype.'='.JText::_('PCCAPTCHAMAXATTEMPTS'));
        return false;
      } else {
      	$returnurl = JRoute::_('index.php?option=com_prayercenter&task='.$page.'&Itemid='.$itemid.'&return_msg='.JText::_('PCCAPTCHAMAXATTEMPTS'));
        $this->PCRedirect($returnurl);
        return false;
      }
    }
    if(isset($mod)){
    $security_code = strtolower($session->get($mod.'_security_code'));
    } else {
    $security_code = strtolower($session->get('pc_security_code'));
    }
    if($security_code != $usercode)
      {
        $i = $session->get('pc_max_attempts');
        $i++;
        $session->set('pc_max_attempts',$i);
        return false;
      } else {
        if(isset($mod)){
          $session->set($mod.'_security_code','');
          } else {
          $session->set('pc_security_code','');
          }
          $session->set('pc_max_attempts',0);
       return true;
      }
  }
  function PCSIDvalidate($var)
  {
    if (!preg_match("^[A-Za-z0-9]{1,32}^",$var)) 
      {
      return false;
      } 
    else
    {
    return true;
    } 
  }
  function PCgetItemid() {
   	 $JSite = new JSite();
     $menu		= $JSite->getMenu();
    $component	= JComponentHelper::getComponent('com_prayercenter');
    $items		= $menu->getItems('component_id', $component->id);
    $itemid = $items[0]->id;
    return $itemid;
  }
  function PCcheckEmail( $email ) 
  {
    global $pcConfig;
    $config_domain_list = $pcConfig['config_domain_list'];
    $domArray = preg_split('/[,]/',$config_domain_list, -1, PREG_SPLIT_NO_EMPTY);
  //	global $mxrecords;
  //	if ( $email == '' ) return '';
  //	if ( !getmxrr ( $domaintld , $mxrecords ) || !preg_match ( "(^[-\w\.]+$)" , $username ) )  return false;
  	list( $username , $domaintld ) = split( "@" , $email ); 
  	$domaintld = strtolower( $domaintld );
    if(!empty($domArray)){
    	if( in_array( $domaintld, $domArray )) 
        {
          return false;
        }
  	  else {
          return true;
      }
    } else { 
    return true;
    }
  }
  function PCcheckBlockedEmail( $email ) 
  {
    global $pcConfig;
    $config_emailblock_list = $pcConfig['config_emailblock_list'];
    $ebArray = preg_split('/[,]/',strtolower($config_emailblock_list), -1, PREG_SPLIT_NO_EMPTY);
    if(!empty($ebArray)){
    	if( in_array( $email, $ebArray )) 
        {
          return false;
        }
  	  else {
          return true;
      }
    } else { 
    return true;
    }
  }
  function PCspamcheck($string) {
    global $pcConfig;
    jimport('joomla.environment.browser');
    $browser = JBrowser::getInstance($_SERVER['HTTP_USER_AGENT']);
    $JVersion = new JVersion();
    $spam = 0;
    if($browser->isRobot()) $spam = 1;
    $config_use_spamcheck = $pcConfig['config_use_spamcheck'];
  //  if (preg_match( "/^bcc:|cc:|multipart|\[url|Content-Type:/i", $string, $out)) {
    if (preg_match( "/^bcc:|cc:|multipart|\[url|Content-Type:|MIME-Version:|content-transfer-encoding:|to:/i", $string, $out)) {
        $spam = 1;
    }
    if (preg_match("/^<a|http|https|www\.|ftp:/i", $string, $out)) {
        $spam = 1;
    }
    if (preg_match("/(%0A|%0D|\n+\r+)/i", $string, $out)) {
        $spam = 1;
    }
    if((isset($_SERVER['HTTP_REFERER']) && !stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))) {
        $spam = 1;
    }
    if(!empty($_POST['temail'])) $spam = 1;
//    if($_POST['formtime'] < time()-3600) $spam = 1;
    if($spam > 0 && $config_use_spamcheck > 0) {
      return false;
     } else {
      return true;
     }
  }
  function PCbadword_replace($string) {
    global $pcConfig;
    if($pcConfig['config_use_wordfilter'] == 1){
      $config_bad_words = trim($pcConfig['config_bad_words']);
      $config_replace_word = $pcConfig['config_replace_word'];
      if (!empty($config_bad_words)){
      $arr = preg_split('/[,]/',$config_bad_words, -1, PREG_SPLIT_NO_EMPTY);
        foreach($arr as $array){
          if($array != " "){
            $arrayStr = '#'.$array.'#i';
            $string = preg_replace($arrayStr,$config_replace_word,$string);
          }
        }
      }
    } elseif($pcConfig['config_use_wordfilter'] == 2 && JPluginHelper::isEnabled('content','wordcensor')) {
    		$dispatcher	= JDispatcher::getInstance();
    		JPluginHelper::importPlugin('content','wordcensor');
        $tresults = $dispatcher->trigger('badword_replace', array ( $string,  '', 0));
        $string = $tresults[0];
    } elseif($pcConfig['config_use_wordfilter'] == 3 && JPluginHelper::isEnabled('content','badwordfilter')) {
    		$dispatcher	= JDispatcher::getInstance();
    		JPluginHelper::importPlugin('content','badwordfilter');
        $params = new JObject();
        $content = new stdClass();
        $content->text = $string;
        $tresults = $dispatcher->trigger('onContentPrepare', array ('text',&$content,&$params,0));
        $string = $content->text;
    } elseif($pcConfig['config_use_wordfilter'] == 4 && JPluginHelper::isEnabled('content','JBehave')) {
    		$dispatcher	= JDispatcher::getInstance();
    		JPluginHelper::importPlugin('content','JBehave');
        $content = new stdClass();
        $content->text = $string;
        $tresults = $dispatcher->trigger('onPrepareContent', array (&$content,'',0));
        $string = $content->text;
    }
    return $string;
  }
  function PCautoPurge($config_request_retention, $config_archive_retention) {
    $jcomments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
    if (file_exists($jcomments)) {
      require_once($jcomments);
    }
    $now = time();
    $config_request_retention = (86400*$config_request_retention);
    $config_archive_retention = (86400*$config_archive_retention);
    $db		= JFactory::getDBO();
    $db->setQuery("SELECT * FROM #__prayercenter WHERE archivestate='0'");
    $purgeresult = $db->loadObjectList();
    if(count($purgeresult) > 0){
      foreach($purgeresult as $purgeresults)
      {
       if (($now - strtotime($purgeresults->date)) >= $config_request_retention)
       {
         $db->setQuery("DELETE FROM #__prayercenter WHERE id='".(int)($purgeresults->id)."'");
      		if (!$db->query()) {
  					JError::raiseError( 500, $db->stderr());
      		}	
       }
      if (file_exists($jcomments)) {
        JComments::deleteComments($purgeresults->id, 'com_prayercenter');
      }
      }
    }
    $db->setQuery("SELECT * FROM #__prayercenter WHERE archivestate='1'");
    $archivepurgeresult=$db->loadObjectList();
    if (count($archivepurgeresult) > 0){
      foreach($archivepurgeresult as $archivepurgeresults)
      {
         if (($now - strtotime($archivepurgeresults->date)) >= $config_archive_retention) 
         {
         $db->setQuery("DELETE FROM #__prayercenter WHERE id='".(int)($archivepurgeresults->id)."'");
      		if (!$db->query()) {
  					JError::raiseError( 500, $db->stderr());
      		}	
         }
        if (file_exists($jcomments)) {
          JComments::deleteComments($archivepurgeresults->id, 'com_prayercenter');
        }
      }
    }
  }
  function PCsendPM($newrequesterid,$newrequester,$newrequest,$newemail,$sendpriv,$lastId=null,$sessionid=null,$admin=false)
  {
    global $pcConfig;
    $pcpmsclassname = 'PC'.ucfirst($pcConfig['config_pms_plugin']).'PMSPlugin';
    if (!empty($pcConfig['config_pms_plugin']) && file_exists(JPATH_ROOT.'/administrator/components/com_prayercenter/plugins/pms/plg.pms.'.$pcConfig['config_pms_plugin'].'.php')) {
      require_once(JPATH_ROOT.'/administrator/components/com_prayercenter/helpers/pc_plugin_class.php');
      $PCPluginHelper = new PCPluginHelper();
      $pluginfile = 'plg.pms.'.$pcConfig['config_pms_plugin'].'.php';
      $PCPluginHelper->importPlugin('pms',$pluginfile);
      $PCPMSPlugin = new $pcpmsclassname();
    } else {
      return;
    }
    if($admin){
     $PCPMSPlugin->admin_private_messaging($newrequesterid,$newrequester,$newrequest,$newemail,$lastId,$sessionid,$sendpriv);
    } elseif(!$sendpriv) {
     $PCPMSPlugin->send_private_messaging($newrequester,$newrequest,$newemail,$sendpriv,$lastId,$sessionid);
    }
  }
  function PCcleanText( &$text )
  {
  		$text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
  		$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
  		$text = preg_replace( '/<!--.+?-->/', '', $text );
  		$text = preg_replace( '/&nbsp;/', ' ', $text );
  		$text = preg_replace( '/&amp;/', ' ', $text );
  		$text = preg_replace( '/&quot;/', ' ', $text );
      $text = preg_replace( '/<(\w[^>]*) class=([^ |>]*)([^>]*)/i', "<$1$3", $text );
      $text = preg_replace( '/<(\w[^>]*) style="([^\"]*)"([^>]*)/i', "<$1$3", $text );
      $text = preg_replace( '/\s*style="\s*"/i', '' , $text ); 
      $text = preg_replace( '/<(\w[^>]*) lang=([^ |>]*)([^>]*)/i', "<$1$3", $text ); 
      $text = preg_replace( '/<STYLE\s*>(.*?)<\/STYLE>/i', '' , $text ); 
  		$text = strip_tags( $text, '<u><i><em><strong><strike><ul><ol><li><br />' );
   		return $text;
  }
  function buildPCMenu($mod=false,$modparams=null){
      global $itemid, $pcConfig;
      $itemid = $this->PCgetItemid();
      $user = JFactory::getUser();
      if(!is_null($modparams)){
        $menu_style = $modparams->get('menu_style');
        if($menu_style == 'vert_indent') $menu_style = 0;
        if($menu_style == 'horiz_flat') $menu_style = 1;
      	$menuclass = 'mainlevel'. $modparams->get( 'modulesclass_sfx' );
        $moduleclass_sfx = $modparams->get('moduleclass_sfx');
        $show_submit = $modparams->get('show_submit',1);
        $show_view = $modparams->get('show_view',1);
        $show_subscribe = $modparams->get('show_subscribe',1);
        $show_links = $modparams->get('show_links',1);
        $show_devotion = $modparams->get('show_devotion',1);
        $show_moderator = $modparams->get('show_moderator',1);
        $menu_css = "";
      } elseif($pcConfig['config_show_menu']) {
        $menu_style = 0;
        $menuclass = "";
        $moduleclass_sfx = "";
        if($pcConfig['config_moduleclass_sfx'] == 1) $moduleclass_sfx = 'alt';
        $show_submit = 1;
        $show_view = 1;
        $show_subscribe = $pcConfig['config_show_subscribe'];
        $show_links = $pcConfig['config_show_links'];
        $show_devotion = $pcConfig['config_show_devotion'];
        $show_moderator = 1;
        $menu_css = ' id="pc-menu"';
      }
    if(!$mod) echo '<br />';
      echo $menu_style == 0 ? '<div'.$menu_css.'>' : '<ul class="pc-modmenu" id="'.$menuclass.'">';
    if ($show_view && $this->pc_rights->get('pc.view')){
        echo $menu_style == 0 ? '<div align="left">' : '<li class="pc-modmenu">';
        echo '<a class="'.$menuclass.$moduleclass_sfx.'" title="'.htmlentities(JText::_('PCVIEWLIST')).'" href="'.JRoute::_("index.php?option=com_prayercenter&task=view&Itemid=$itemid").'">
             '.htmlentities(JText::_('PCVIEWLIST')).'</a>';
        echo $menu_style == 0 ? '</div>' : '</li>';
        }
    if ($show_submit && $this->pc_rights->get('pc.post')){
        echo $menu_style == 0 ? '<div align="left">' : '<li class="pc-modmenu">';
        echo '<a class="'.$menuclass.$moduleclass_sfx.'" title="'.htmlentities(JText::_('PCSUBMITREQUEST')).'" href="'.JRoute::_("index.php?option=com_prayercenter&task=newreq&Itemid=$itemid").'">
            '.htmlentities(JText::_('PCSUBMITREQUEST')).'</a>';
        echo $menu_style == 0 ? '</div>' : '</li>';
        }
    if ($show_subscribe && $this->pc_rights->get('pc.subscribe')){
        echo $menu_style == 0 ? '<div align="left">' : '<li class="pc-modmenu">';
        echo '<a class="'.$menuclass.$moduleclass_sfx.'" title="'.htmlentities(JText::_('PCSUBSCRIBE')).'" href="'.JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=$itemid").'">
            '.htmlentities(JText::_('PCSUBSCRIBE')).'</a>';
        echo $menu_style == 0 ? '</div>' : '</li>';
        }
    if ($show_links && $this->pc_rights->get('pc.view_links')){
        echo $menu_style == 0 ? '<div align="left">' : '<li class="pc-modmenu">';
        echo '<a class="'.$menuclass.$moduleclass_sfx.'" title="'.htmlentities(JText::_('PCLINKSLIST')).'" href="'.JRoute::_("index.php?option=com_prayercenter&task=view_links&Itemid=$itemid").'">
            '.htmlentities(JText::_('PCLINKSLIST')).'</a>';
        echo $menu_style == 0 ? '</div>' : '</li>';
        }
    if ($show_devotion && $this->pc_rights->get('pc.view_devotional')){
        echo $menu_style == 0 ? '<div align="left">' : '<li class="pc-modmenu">';
        echo '<a class="'.$menuclass.$moduleclass_sfx.'" title="'.htmlentities(JText::_('PCDEVOTIONALS')).'" href="'.JRoute::_("index.php?option=com_prayercenter&task=view_devotion&Itemid=$itemid").'">
            '.htmlentities(JText::_('PCDEVOTIONALS')).'</a>';
        echo $menu_style == 0 ? '</div>' : '</li>';
        }
    if ($show_moderator && $this->pc_rights->get('pc.moderate') && $pcConfig['config_use_admin_alert'] > 1){
          echo $menu_style == 0 ? '<div align="left">' : '<li class="pc-modmenu">';
          echo '<a class="'.$menuclass.$moduleclass_sfx.'" title="'.htmlentities(JText::_('PCMODERATORS')).'" href="'.JRoute::_("index.php?option=com_prayercenter&task=moderate&Itemid=$itemid").'">
             '.htmlentities(JText::_('PCMODERATORS')).'</a>';
          echo $menu_style == 0 ? '</div>' : '</li>';
          }
     echo $menu_style == 0 ? '</div>' : '</ul>';
  }
  function writePCImage(){
    global $pcConfig;
    if(!$pcConfig['config_show_image']) return;
    jimport('joomla.filesystem.folder');
    $livesite = JURI::base();
    $alt_line = "";
    $border = "1";
    $width = "130";
    $height = "130";
    if($pcConfig['config_use_slideshow']){
      $abpath_folder = JPATH_COMPONENT.'/assets/images/slideshow';
      if (JFolder::exists($abpath_folder)) {
        $timage = JFolder::files($abpath_folder,'png|jpg');
      	if (!$timage) {
      		echo JText::_('PCNOIMAGES');
      	} else {
        	$i = count($timage);
        	$random = mt_rand(0, $i - 1);
        	$timg_name = $timage[$random];
        	$i = $abpath_folder.'/'.$timg_name;
        	$size = getimagesize ($i);
        	if ($width == '') {
        		$width = 100;
        	}
        	if ($height == '') {
        		$coeff = $size[0]/$size[1];
        		$height = (int)($width/$coeff);
        	}
      }
      $image = $livesite .'components/com_prayercenter/assets/images/slideshow/'.$timg_name;
      ?>
      <script type="text/javascript">
      var pcslidespeed=<?php echo $pcConfig['config_slideshow_speed']?>*1000;
      var pcslideimages=new Array()
      var pcslidelinks=new Array()
      var pcnewwindow=1 //open links in new window? 1=yes, 0=no
      </script>
      <?php
      $i = 0;
      foreach ($timage as $pcimag) {
  			if (preg_match('/png$/i', $pcimag) || preg_match('/jpg$/i', $pcimag)) {
      		$the_pcimage = $livesite .'components/com_prayercenter/assets/images/slideshow/'.$pcimag;
      ?>
      <script type="text/javascript">
  			pcslideimages[<?php echo $i; ?>] = "<?php echo $the_pcimage; ?>";
  		</script>
      <?php
    		$i++;
      		}
      }
      ?>
      <script type="text/javascript">
      var pcimageholder=new Array()
      var pcie=document.all
      for (i=0;i<pcslideimages.length;i++){
      pcimageholder[i]=new Image()
      pcimageholder[i].src=pcslideimages[i]
      }
      function gotoshow(){
        if (pcnewwindow)
          window.open(pcslidelinks[pcwhichlink])
        else
          window.location=pcslidelinks[pcwhichlink]
        }
      </script>
      <div class="mosimage" align="center" style="float:right;padding:0">
      <img src="<?php echo $image;?>" name="pcslide" border="<?php echo $border;?>" style="filter:blendTrans(duration=<?php echo $pcConfig['config_slideshow_duration']; ?>)" width="<?php echo $width; ?>" height="<?php echo $height; ?>" title="<?php echo $alt_line; ?>" alt="<?php echo $alt_line; ?>">
      <script type="text/javascript">
      var pcwhichlink=0
      var pcwhichimage=0
      var pcblenddelay=(pcie)? document.images.pcslide.filters[0].duration*1000 : 0
      function pcslideit(){
      if (!document.images) return
      if (pcie) document.images.pcslide.filters[0].apply()
      document.images.pcslide.src=pcimageholder[pcwhichimage].src
      if (pcie) document.images.pcslide.filters[0].play()
      pcwhichlink=pcwhichimage
      pcwhichimage=(pcwhichimage<pcslideimages.length-1)? pcwhichimage+1 : 0
      setTimeout("pcslideit()",pcslidespeed+pcblenddelay)
      }
      pcslideit()
      </script>
      </div>
      <?php
       }
      } else {
       echo '<div class="mosimage" align="center" style="float:right;padding:0">
         <img class="pc-img" alt="" title="" border="0" src="components/com_prayercenter/assets/images/'.$pcConfig['config_imagefile'].'" />
        </div>';
    }
   }
  function writePCHeader($text,$override=false,$subtext=""){
    global $pcConfig;
    if(!$pcConfig['config_show_header_text'] && !$override) return;
    $return = $this->PCkeephtml(htmlentities($text)).'<br /><br />';
    if(!empty($subtext))$return .= $this->PCkeephtml($subtext);
    return $return;
  }
  function writePCFooter(){
    global $pcConfig;
    $user = JFactory::getUser();
    $lang = JFactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
    $user = JFactory::getUser();
    $config_bmrss_service = $pcConfig['config_bmrss_service'];
    if($pcConfig['config_show_credit']){
      echo '<div class="footer" style="clear:both;text-align:center;font-size:x-small;">'.JText::_('PCFOOTER').' <a href="http://www.mlwebtechnologies.com/" title="MLWebTechnologies">MLWebTechnologies</a></div>';
    }
    if($pcConfig['config_show_rss'] && $this->pc_rights->get('pc.view')){
  		!$user->guest ? $key = '&key='.md5($pcConfig['config_rss_authkey']) : $key = "";
      $rss_link = JRoute::_('index.php?option=com_prayercenter&amp;task=rss'.$key);
   		$img =  JHTML::_('image', JURI::base().'media/system/images/livemarks.png', htmlentities(JText::_('PCFEEDS')), 'style="border:0;"');
      echo '<br /><div style="text-align:right;">';
			if($config_bmrss_service == 1){
        echo "<a href=\"http://www.addthis.com/feed.php?username=&amp;h1=".$rss_link."&amp;t1=\" onclick=\"return addthis_open(this, 'feed', '".$rss_link."')\" title=\"".htmlentities(JText::_('PCFEEDS'))." by AddThis"."\" target=\"_blank\"><img src=\"http://s7.addthis.com/static/btn/sm-rss-en.gif\" width=\"83\" height=\"16\" title=\"".htmlentities(JText::_('USRLPCFEEDS'))." by AddThis"."\" style=\"border:0\"/></a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=\"></script>";
      } elseif($config_bmrss_service == 2) {
        echo "<a class=\"a2a_dd\" href=\"http://www.addtoany.com/subscribe?linkname=".htmlentities(JText::_('PCFEEDS'))."&amp;linkurl=".$rss_link."\" title=\"".htmlentities(JText::_('PCFEEDS'))." by AddToAny"."\"><img src=\"http://static.addtoany.com/buttons/subscribe_16_16.gif\" width=\"16\" height=\"16\" border=\"0\" title=\"".htmlentities(JText::_('USRLPCFEEDS'))." by AddToAny"."\"/></a><script type=\"text/javascript\">a2a_linkname=\"".htmlentities(JText::_('USRLPCFEEDS'))."\";a2a_linkurl=\"".$rss_link."\";</script><script type=\"text/javascript\" src=\"http://static.addtoany.com/menu/feed.js\"></script>";
      } else {
        echo "<a href=\"".$rss_link."\" target=\"_blank\" title=\"".htmlentities(JText::_('PCFEEDS'))."\">".$img."</a>";
      }
  		echo '&nbsp;&nbsp;</div><br /><br /><br />';
  	}
  }
  function PCwordWrapIgnoreHTML($string, $length = 45, $wrapString = "\n"){ 
     $wrapped = ''; 
     $word = ''; 
     $html = false; 
     $string = (string) $string; 
     for($i=0;$i<strlen($string);$i+=1) 
     { 
       $char = $string[$i]; 
       if($char === '<') 
       { 
         if(!empty($word)) 
         { 
           $wrapped .= $word; 
           $word = ''; 
         } 
         $html = true; 
         $wrapped .= $char; 
       } 
       elseif($char === '>') 
       { 
         $html = false; 
         $wrapped .= $char; 
       } 
       elseif($html) 
       { 
         $wrapped .= $char; 
       } 
       elseif($char === ' ' || $char === "\t" || $char === "\n") 
       { 
         $wrapped .= $word.$char; 
         $word = ''; 
       } 
       else 
       { 
         $word .= $char; 
         if(strlen($word) > $length) 
         { 
           $wrapped .= $word.$wrapString; 
           $word = ''; 
         } 
       } 
     } 
     if($word !== ''){ 
       $wrapped .= $word; 
     } 
    return $wrapped; 
  } 
  function PCkeephtml($string){
//    $res = htmlentities($string);
    $res = $string;
    $res = str_replace("&lt;","<",$res);
    $res = str_replace("&gt;",">",$res);
    $res = str_replace("&quot;",'"',$res);
    $res = str_replace("&amp;",'&',$res);
    return $res;
  }
  function PCarray_flatten($array) { 
    if (!is_array($array)) { 
      return FALSE; 
    } 
    $result = array(); 
    foreach ($array as $key => $value) { 
      if (is_array($value)) { 
        $result = array_merge($result, $this->PCarray_flatten($value)); 
      } 
      else { 
        $result[$key] = $value; 
      } 
    } 
    return $result; 
  } 
  function PCgetAdminData(){
    $db = JFactory::getDBO();
    $JVersion = new JVersion();
    if( (real)$JVersion->RELEASE == 1.5 ) {
      $db->setQuery("SELECT name,email FROM #__users WHERE usertype='Administrator' OR usertype='Super Administrator'");
      $resultusers = $db->loadObjectList();
    } elseif( (real)$JVersion->RELEASE >= 1.6 ) {
      $access = JFactory::getACL();
      $db->setQuery("SELECT id FROM #__usergroups");
      $groups = $db->loadObjectList();
      foreach($groups as $group){
        if($access->checkGroup($group->id, 'core.manage') || $access->checkGroup($group->id, 'core.admin')){
          $adminusers[] = $access->getUsersByGroup($group->id);
        }
      }
      $result = $this->PCarray_flatten($adminusers);
   		$result = implode(',', $result);
      $db->setQuery("SELECT name,email FROM #__users WHERE id IN (".$result.")");
      $resultusers = $db->loadObjectList();
    }
    return $resultusers;
  }
  function PCgetTimeZoneData($data,$alt=null){
    global $pcConfig;
    $user = JFactory::getUser();
    $userid = $user->get('id');
    $juser = new JUser($userid);
    $usertz = $juser->getParam('timezone');
		$conf = JFactory::getConfig();
    $config_offset = $conf->get('offset');
    $dateTime = array();
    $dateset = new DateTime($data->date.' '.$data->time, new DateTimeZone('UTC'));
    $config_offset_user = $conf->get('offset_user'); 
    if(isset($usertz)) {
      $dateset->setTimeZone(new DateTimeZone($usertz)); 
    } elseif(isset($config_offset_user)) {
      $dateset->setTimeZone(new DateTimeZone($config_offset_user)); 
    } else {
      $dateset->setTimeZone(new DateTimeZone($config_offset)); 
    }
    $dateTime['time'] = $dateset->format(!is_null($alt) ? $alt : $pcConfig['config_time_format']);
    $dateTime['date'] = $dateset->format(!is_null($alt) ? $alt : $pcConfig['config_date_format']);
    $dateTime['tz'] = $dateset->format('T');
    $tzid = $dateset->format('e');
    if($tzid == 'UTC'){
      $dateTime['tzid'] = 'Coordinated Universal Time';
    } elseif($tzid == 'GMT'){
      $dateTime['tzid'] = 'Greenwich Mean Time';
    } else {
      $dateTime['tzid'] = $tzid;
    }
    return $dateTime;
  }
  function PCdateFormatToStrftime($dateFormat) { 
    $strarray = array( 
        'd' => '%d', 'D' => '%a', 'j' => '%e', 'l' => '%A', 'N' => '%u', 'w' => '%w', 'z' => '%j',
        'W' => '%V', 'F' => '%B', 'm' => '%m', 'M' => '%b', 'o' => '%G', 'Y' => '%Y', 'y' => '%y', 
        'a' => '%P', 'A' => '%p', 'g' => '%l', 'h' => '%I', 'H' => '%H', 'i' => '%M', 's' => '%S',
        'O' => '%z', 'T' => '%Z', 'U' => '%s', 'r' => '%c' 
    ); 
    return strtr((string)$dateFormat, $strarray); 
  } 
  function PCnumeric_entities($string){
     $mapping = array();
     foreach (get_html_translation_table(HTML_ENTITIES, ENT_QUOTES) as $char => $entity){
         $mapping[$entity] = '&#' . ord($char) . ';';
     }
     return str_replace(array_keys($mapping), $mapping, $string);
  }
}
?>