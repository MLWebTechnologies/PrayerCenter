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
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');
class PrayerCenterControllerPrayerCenter extends PrayerCenterController
{
  function newreqsubmit()
  {
    global $pcConfig, $prayercenter;
 		JRequest::checkToken() or jexit( 'Invalid Token' );
    $app = JFactory::getApplication();
    $pc_rights = $prayercenter->pc_rights;
    $mod = JRequest::getVar( 'mod', null, 'get', 'string' );
    $modtype = JRequest::getVar( 'modtype', null, 'get', 'string' );
    $returntoarray = preg_split('/\&return/',$_SERVER['HTTP_REFERER'],-1,PREG_SPLIT_NO_EMPTY);
    $returnto = $returntoarray[0];
    jimport('joomla.date.date');
    jimport('joomla.mail.helper');
    jimport('joomla.filter.output');
		$user = JFactory::getUser();
    $db		= JFactory::getDBO();
    $itemid = $prayercenter->PCgetItemid();
    $dateset = new JDate();
    $time = $dateset->format('H:i:s');
    $date = $dateset->format('Y-m-d');
    $session = JFactory::getSession();
    $sessionid = $session->get('session.token');
    $newtitle = JRequest::getVar('newtitle',null,'post','string',JREQUEST_ALLOWHTML);
    $newrequest = JRequest::getVar('newrequest',null,'post','string',JREQUEST_ALLOWHTML);
    $newrequester = JRequest::getVar('newrequester',null,'post','string');
    $newrequesterid = JRequest::getVar('requesterid',null,'post','int');
    $newemail = JRequest::getVar('newemail',null,'post','string');
    $newtopic = JRequest::getVar('newtopic',null,'post','int');
    if(!empty($newemail) && JMailHelper::isEmailAddress($newemail)){
        if( !$prayercenter->PCcheckEmail($newemail) ) {
            if(isset($_GET['modtype'])){
          		$this->setMessage(htmlentities(JText::_('PCINVALIDDOMAIN')), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=newreq&Itemid=".$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDDOMAIN')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          }
        if( !$prayercenter->PCcheckBlockedEmail($newemail) ) {
            if(isset($_GET['modtype'])){
          		$this->setMessage(JText::_('PCINVALIDEMAIL'), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=newreq&Itemid=".$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDEMAIL')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          }
        }
      if(!empty($newrequest)){
        if(!$pcConfig['config_captcha_bypass_4member'] || $pcConfig['config_captcha_bypass_4member'] && $user->guest){
          $this->pcCaptchaValidate($returnto,$itemid,$modtype,'newreq');
        }
        if($prayercenter->PCspamcheck($newrequest) && $prayercenter->PCspamcheck($newrequester)){
        $newtitle = $prayercenter->PCcleanText($newtitle);
        $newrequest = $prayercenter->PCcleanText($newrequest);
        $newrequest = addslashes($newrequest);
        $newrequester = JFilterOutput::cleanText($newrequester);
        $newemail = JMailHelper::cleanAddress($newemail);
        if(!JMailHelper::isEmailAddress($newemail) && $pcConfig['config_use_admin_alert'] == 1){
          if(isset($_GET['modtype'])){
        		$this->setMessage(htmlentities(JText::_('PCINVALIDEMAIL')), 'message');
        		$this->setRedirect(JRoute::_($returnto, false));
          } else {
          	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=newreq&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDEMAIL')));
        		$this->setRedirect(JRoute::_($returnurl, false));
          }
        }
        $sendpriv = JRequest::getVar('sendpriv',null,'post','boolean');
        if ($newrequester=='') {$newrequester= htmlentities(JText::_('PCANONUSER'));}
        if ($pcConfig['config_use_wordfilter'] > 0) {
          $newtitle = $prayercenter->PCbadword_replace($newtitle);
          $newrequest = $prayercenter->PCbadword_replace($newrequest);
          $newemail = $prayercenter->PCbadword_replace($newemail);
          $newrequester = $prayercenter->PCbadword_replace($newrequester);
        }
        if ($pcConfig['config_use_admin_alert'] == 0 && $pc_rights->get('pc.publish')) {
          $sql="INSERT INTO #__prayercenter (id,requesterid,requester,date,time,request,publishstate,archivestate,displaystate,sendto,email,adminsendto,sessionid,title,topic) VALUES ('',".(int)$newrequesterid.",".$db->quote($db->escape($newrequester),false).",".$db->quote($db->escape($date),false).",".$db->quote($db->escape($time),false).",".$db->quote($db->escape($newrequest),false).",'1','0',".(int)$sendpriv.",'0000-00-00 00:00:00',".$db->quote($db->escape($newemail),false).",'0000-00-00 00:00:00',".$db->quote($db->escape($sessionid),false).",".$db->quote($db->escape($newtitle),false).",".(int)$newtopic.")";
          		$db->setQuery($sql);
        		if (!$db->query()) {
    					JError::raiseError( 500, $db->stderr());
        		}	
      	  $lastId = $db->insertid();
        } elseif($pcConfig['config_use_admin_alert'] > 0){
          $sql="INSERT INTO #__prayercenter (id,requesterid,requester,date,time,request,publishstate,archivestate,displaystate,sendto,email,adminsendto,sessionid,title,topic) VALUES ('',".(int)$newrequesterid.",".$db->quote($db->escape($newrequester),false).",".$db->quote($db->escape($date),false).",".$db->quote($db->escape($time),false).",".$db->quote($db->escape($newrequest),false).",'0','0',".(int)$sendpriv.",'0000-00-00 00:00:00',".$db->quote($db->escape($newemail),false).",'0000-00-00 00:00:00',".$db->quote($db->escape($sessionid),false).",".$db->quote($db->escape($newtitle),false).",".(int)$newtopic.")";
      		$db->setQuery($sql);
        		if (!$db->query()) {
    					JError::raiseError( 500, $db->stderr());
        		}	
        	$lastId = $db->insertid();
        }		
        // Notify Site Admin(s) and/or moderators on event of new request
        if($pcConfig['config_use_admin_alert'] > 1 && !$pc_rights->get('pc.publish')){
          if($pcConfig['config_admin_distrib_type'] > 1 && $pcConfig['config_pms_plugin']){
            $prayercenter->PCsendPM($newrequesterid,$newrequester,$newrequest,$newemail,$sendpriv,$lastId,$sessionid,true);
          }
        } elseif($pcConfig['config_use_admin_alert'] < 2) {
          if($pcConfig['config_use_admin_alert'] == 1 && !empty($newemail)){
            if(JPluginHelper::isEnabled('system','prayercenteremail')){
              $results = plgSystemPrayerCenterEmail::pcEmailTask('PCconfirm_notification',array('0'=>$lastId));
            }
            if(isset($_GET['modtype'])){
          		$this->setMessage(htmlentities(JText::_('PCREQSUBMITCONFIRM')), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCREQSUBMITCONFIRM')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          }
					if($pcConfig['config_use_admin_alert'] == 0){
            if($sendpriv){
              if($pcConfig['config_distrib_type'] > 1 && $pcConfig['config_pms_plugin']){
                $prayercenter->PCsendPM($newrequester,$newrequest,$newemail,$sendpriv);
              }
            } elseif(!$sendpriv){
              if($pcConfig['config_distrib_type'] > 1 && $pcConfig['config_pms_plugin']){
                $prayercenter->PCsendPM($newrequesterid,$newrequester,$newrequest,$newemail,$sendpriv,$lastId,$sessionid);
              }
            }
          }
         }
          if(isset($_GET['modtype'])){
        		$this->setMessage(htmlentities(JText::_('PCREQSUBMIT')), 'message');
        		$this->setRedirect(JRoute::_($returnto, false));
          } else {
          	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCREQSUBMIT')),false);
        		$this->setRedirect($returnurl);
          }
        } else {
          if(stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])){
            if(isset($_GET['modtype'])){
          		$this->setMessage(JText::_('PCSPAMMSG'), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=newreq&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCSPAMMSG')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          } else {
            header( 'HTTP/1.0 403 Forbidden' );
            sleep(rand(2, 5)); // delay spammers a bit
      			JError::raiseError(403, _NOT_AUTH );
          }
        }
     } else {
        if(isset($_GET['modtype'])){
      		$this->setMessage(htmlentities(JText::_('PCFORMNC')), 'message');
      		$this->setRedirect(JRoute::_($returnto, false));
        } else {
        	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCFORMNC')));
      		$this->setRedirect(JRoute::_($returnurl, false));
        }
      }
    }
    function subscribesubmit()
    {
      global $pcConfig, $prayercenter;
  		JRequest::checkToken() or jexit( 'Invalid Token' );
      $app = JFactory::getApplication();
      $pc_rights = $prayercenter->pc_rights;
      $mod = JRequest::getVar( 'mod', null, 'get', 'string' );
      $modtype = JRequest::getVar( 'modtype', null, 'get', 'string' );
      $returntoarray = preg_split('/\&return/',$_SERVER['HTTP_REFERER'],-1,PREG_SPLIT_NO_EMPTY);
      $returnto = $returntoarray[0];
      jimport('joomla.date.date');
      jimport('joomla.mail.helper');
      jimport('joomla.filter.output');
      $itemid = $prayercenter->PCgetItemid();
  		$user = JFactory::getUser();
      if(!$pcConfig['config_captcha_bypass_4member'] || $pcConfig['config_captcha_bypass_4member'] && $user->guest){
        $this->pcCaptchaValidate($returnto,$itemid,$modtype,'subscribe');
      }
      $session = JFactory::getSession();
      $sessionid = $session->get('session.token');
      $newsubscribe = JRequest::getVar('newsubscribe',null,'post','string');
      if(!empty($newsubscribe) && JMailHelper::isEmailAddress($newsubscribe)){
        if( !$prayercenter->PCcheckEmail($newsubscribe) ) {
            if(isset($_GET['modtype'])){
          		$this->setMessage(htmlentities(JText::_('PCINVALIDDOMAIN')), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=subscribe&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDDOMAIN')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
        }
        if( !$prayercenter->PCcheckBlockedEmail($newsubscribe) ) {
            if(isset($_GET['modtype'])){
          		$this->setMessage(htmlentities(JText::_('PCINVALIDEMAIL')), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDEMAIL')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          }
      }
      if ($pcConfig['config_use_wordfilter'] > 0) {
        $newrequest = $prayercenter->PCbadword_replace($newrequest);
        $newsubscribe = $prayercenter->PCbadword_replace($newsubscribe);
      }
      $newsubscribe = JMailHelper::cleanAddress($newsubscribe);
      if (JMailHelper::isEmailAddress( $newsubscribe ))
      {
        $dateset = new JDate();
        $date = $dateset->format('Y-m-d');
        $db	= JFactory::getDBO();
        $db->setQuery("SELECT email FROM #__prayercenter_subscribe");
        $readq = $db->loadObjectList();
        $duplicate = '0';
        foreach ($readq as $dup){
          if ($newsubscribe == $dup->email){
            $duplicate = '1';
          }
        }
        if ($duplicate != '1'){
          if ($pcConfig['config_admin_approve_subscribe'] == 0){
          $sql="INSERT INTO #__prayercenter_subscribe (id,email,date,approved,sessionid) VALUES ('',".$db->quote($db->escape($newsubscribe),false).",".$db->quote($db->escape($date),false).",'1',".$db->quote($db->escape($sessionid),false).")";
      		$db->setQuery($sql);
      		if (!$db->query()) {
  					JError::raiseError( 500, $db->stderr());
            }
      	  $lastId = $db->insertid();
          } elseif ($pcConfig['config_admin_approve_subscribe'] > 0) {
          $sql="INSERT INTO #__prayercenter_subscribe (id,email,date,approved,sessionid) VALUES ('',".$db->quote($db->escape($newsubscribe),false).",".$db->quote($db->escape($date),false).",'0',".$db->quote($db->escape($sessionid),false).")";
      		$db->setQuery($sql);
      		if (!$db->query()) {
  					JError::raiseError( 500, $db->stderr());
                 }
      	  $lastId = $db->insertid();
          }
          if($pcConfig['config_admin_approve_subscribe'] == 2){
            if(JPluginHelper::isEnabled('system','prayercenteremail')){
              $results = plgSystemPrayerCenterEmail::pcEmailTask('PCconfirm_sub_notification',array('0'=>$newsubscribe,'1'=>$lastId,'2'=>$sessionid));
            }
            if(isset($_GET['modtype'])){
          		$this->setMessage(htmlentities(JText::_('PCREQSUBMITCONFIRM')), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=subscribe&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCREQSUBMITCONFIRM')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          }
    			if ($pcConfig['config_admin_approve_subscribe'] == 1){
            if(JPluginHelper::isEnabled('system','prayercenteremail')){
              $results = plgSystemPrayerCenterEmail::pcEmailTask('PCconfirm_sub_notification',array('0'=>$newsubscribe,'1'=>$lastId,'2'=>$sessionid));
            }				
    				if(isset($_GET['modtype'])){
           		$this->setMessage(htmlentities(JText::_('PCENTRYACCEPTED')), 'message');
           		$this->setRedirect(JRoute::_($returnto, false));
            } else {
             	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid."&return_msg=".htmlentities(JText::_('PCREQSUBMITAUTH')));
              $this->setRedirect(JRoute::_($returnurl, false));
            }				
          }	
          if($pc_rights->get('pc.subscribe') && $pcConfig['config_admin_approve_subscribe'] == 0){
            if(JPluginHelper::isEnabled('system','prayercenteremail')){
              $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_subscribe',array('0'=>$newsubscribe));
    				 	if ($pcConfig['config_email_subscribe']) {
    					  $results = plgSystemPrayerCenterEmail::pcEmailTask('PCadmin_email_subscribe_notification',array('0'=>$newsubscribe));			 	
    				 	}				  
      		  }
            if(isset($_GET['modtype'])){
          		$this->setMessage(htmlentities(JText::_('PCENTRYACCEPTED')), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid."&return_msg=".htmlentities(JText::_('PCENTRYACCEPTED')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          }
        } else {
          if(isset($_GET['modtype'])){
        		$this->setMessage(htmlentities(JText::_('PCDUPLICATEDENTRY')), 'message');
        		$this->setRedirect(JRoute::_($returnto, false));
          } else {
          	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid."&return_msg=".htmlentities(JText::_('PCDUPLICATEDENTRY')));
        		$this->setRedirect(JRoute::_($returnurl, false));
          }
        }
      } else { 
        if(isset($_GET['modtype'])){
      		$this->setMessage(htmlentities(JText::_('PCINVALIDEMAIL')), 'message');
      		$this->setRedirect(JRoute::_($returnto, false));
        } else {
        	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid."&return_msg=".htmlentities(JText::_('PCINVALIDEMAIL')));
      		$this->setRedirect(JRoute::_($returnurl, false));
        }
      }
    }
    function unsubscribesubmit()
    {
      global $db, $pcConfig, $prayercenter;
  		JRequest::checkToken() or jexit( 'Invalid Token' );
      $mod = JRequest::getVar( 'mod', null, 'get', 'string' );
      $modtype = JRequest::getVar( 'modtype', null, 'get', 'string' );
      $returntoarray = preg_split('/\&return/',$_SERVER['HTTP_REFERER'],-1,PREG_SPLIT_NO_EMPTY);
      $returnto = $returntoarray[0];
      jimport('joomla.date.date');
      jimport('joomla.mail.helper');
      jimport('joomla.filter.output');
      $itemid = $prayercenter->PCgetItemid();
  		$user = JFactory::getUser();
      if(!$pcConfig['config_captcha_bypass_4member'] || $pcConfig['config_captcha_bypass_4member'] && $user->guest){
        $this->pcCaptchaValidate($returnto,$itemid,$modtype,'subscribe');
      }
      $newsubscribe = JRequest::getVar('newsubscribe',null,'post','string');
      if(!empty($newsubscribe) && JMailHelper::isEmailAddress($newsubscribe)){
        if( !$prayercenter->PCcheckEmail($newsubscribe) ) {
            if(isset($_GET['modtype'])){
          		$this->setMessage(htmlentities(JText::_('PCINVALIDDOMAIN')), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=subscribe&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDDOMAIN')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          }
      if( !$prayercenter->PCcheckBlockedEmail($newsubscribe) ) {
          if(isset($_GET['modtype'])){
        		$this->setMessage(htmlentities(JText::_('PCINVALIDEMAIL')), 'message');
        		$this->setRedirect(JRoute::_($returnto, false));
          } else {
          	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDEMAIL')));
        		$this->setRedirect(JRoute::_($returnurl, false));
          }
        }
      }
      $newsubscribe = JMailHelper::cleanAddress($newsubscribe);
      if (JMailHelper::isEmailAddress( $newsubscribe )){
        $db	= JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__prayercenter_subscribe WHERE email=".$db->quote($db->escape($newsubscribe),false)."");
        $readq = $db->loadObjectList();
        if($pcConfig['config_admin_approve_subscribe'] == 2){
          if(JPluginHelper::isEnabled('system','prayercenteremail')){
            $results = plgSystemPrayerCenterEmail::pcEmailTask('PCconfirm_unsub_notification',array('0'=>$newsubscribe,'1'=>$readq[0]->id,'2'=>$readq[0]->sessionid));
          }
          if(isset($_GET['modtype'])){
        		$this->setMessage(htmlentities(JText::_('PCREQSUBMITCONFIRM')), 'message');
        		$this->setRedirect(JRoute::_($returnto, false));
          } else {
          	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=subscribe&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCREQSUBMITCONFIRM')));
        		$this->setRedirect(JRoute::_($returnurl, false));
          }
        } else {
          if ($newsubscribe == $readq[0]->email){
            $db->setQuery("DELETE FROM #__prayercenter_subscribe WHERE email=".$db->quote($db->escape($newsubscribe),false)."");
        		if (!$db->query()) {
    					JError::raiseError( 500, $db->stderr());
        		}	
            if(JPluginHelper::isEnabled('system','prayercenteremail')){
              $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_unsubscribe',array('0'=>$newsubscribe));
            }
            if(isset($_GET['modtype'])){
          		$this->setMessage(htmlentities(JText::_('PCENTRYREMOVED')), 'message');
          		$this->setRedirect(JRoute::_($returnto, false));
            } else {
            	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid."&return_msg=".htmlentities(JText::_('PCENTRYREMOVED')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          } else {
              if(isset($_GET['modtype'])){
            		$this->setMessage(htmlentities(JText::_('PCNOTSUBSCRIBED')), 'message');
            		$this->setRedirect(JRoute::_($returnto, false));
              } else {
              	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid."&return_msg=".htmlentities(JText::_('PCNOTSUBSCRIBED')));
            		$this->setRedirect(JRoute::_($returnurl, false));
              }
          }
        }
      } else {
        if(isset($_GET['modtype'])){
      		$this->setMessage(htmlentities(JText::_('PCINVALIDEMAIL')), 'message');
      		$this->setRedirect(JRoute::_($returnto, false));
        } else {
        	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=subscribe&Itemid=".$itemid."&return_msg=".htmlentities(JText::_('PCINVALIDEMAIL')));
      		$this->setRedirect(JRoute::_($returnurl, false));
        }
      }
    }
    function editrequest()
    {   
      global $db, $pcConfig, $prayercenter;
      $itemid = $prayercenter->PCgetItemid();
      $db		= JFactory::getDBO();
      $app = JFactory::getApplication();
      jimport('joomla.date.date');
      $dateset = new JDate();
      $time = $dateset->format('H:i:s');
      $date = $dateset->format('Y-m-d');
  		$id = JRequest::getVar('id',null,'post','int');
      $request = JRequest::getVar('newrequest',null,'post','string',JREQUEST_ALLOWHTML);
     	$db->setQuery("UPDATE #__prayercenter SET request=".$db->quote($db->escape($request),false).", date=".$db->quote($db->escape($date),false).", time=".$db->quote($db->escape($time),false)." WHERE id=".(int)$id."");
  		if (!$db->query()) {
				JError::raiseError( 500, $db->stderr());
  		}	
     	$db->setQuery("SELECT * FROM #__prayercenter WHERE id=".(int)($id)."");
      $readresult = $db->loadObjectList();
  		$model = $this->getModel('prayercenter');
  		$model->checkin();
     	$returnurl = JRoute::_("index.php?option=com_prayercenter&task=".$_POST['last']."&Itemid=".$itemid);
  		$this->setRedirect(JRoute::_($returnurl, false));
    }
    function closeedit()
    {
      global $db, $prayercenter;
      $itemid = $prayercenter->PCgetItemid();
      $last = JRequest::getVar('last',null,'post','string');
   		$id = JRequest::getVar('id',null,'post','int');
  		$model = $this->getModel('prayercenter');
  		$model->checkin();
    	$returnurl = JRoute::_('index.php?option=com_prayercenter&task='.$last.'&Itemid='.(int)$itemid);
  		$this->setRedirect(JRoute::_($returnurl, false));
    }
    function delrequest()
    {
      global $db, $pcConfig, $prayercenter;
      if($pcConfig['config_comments'] == 1) {
        $jcomments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
        if (file_exists($jcomments)) {
          require_once($jcomments);
        }
      } elseif($pcConfig['config_comments'] == 2) {
        $jsc = JPATH_SITE.'/components/com_jsitecomments/helpers/jsc_class.php';
        if (file_exists($jsc)) {
          require_once($jsc);
        }
      }
      $itemid = $prayercenter->PCgetItemid();
      $db		= JFactory::getDBO();
    	$cid = (JRequest::getVar('delete',array(0),'post','array'));
    	while(list($key, $val) = each($cid))
    		{
          	$delreq = "DELETE FROM #__prayercenter WHERE id=".(int)$key."";
            $db->setQuery($delreq);
        		if (!$db->query()) {
    					JError::raiseError( 500, $db->stderr());
        		}	
            if($pcConfig['config_comments'] > 0) {
              if (file_exists($jcomments)) {
                  JComments::deleteComments($id, 'com_prayercenter');
              } elseif (file_exists($jsc)) {
                  jsitecomments::JSCdelComment('com_prayercenter', $id);
              }
            }
        }
    	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=moderate&Itemid='.$itemid);
  		$this->setRedirect(JRoute::_($returnurl, false));
    }
    function editdelrequest()
    {
      global $db, $pcConfig, $prayercenter;
      if($pcConfig['config_comments'] == 1) {
        $jcomments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
        if (file_exists($jcomments)) {
          require_once($jcomments);
        }
      } elseif($pcConfig['config_comments'] == 2) {
        $jsc = JPATH_SITE.'/components/com_jsitecomments/helpers/jsc_class.php';
        if (file_exists($jsc)) {
          require_once($jsc);
        }
      }
      $itemid = $prayercenter->PCgetItemid();
      $db		= JFactory::getDBO();
    	$id = JRequest::getVar('id',null,'post','int');
     	$delreq = "DELETE FROM #__prayercenter WHERE id=".(int)$id."";
      $db->setQuery($delreq);
   		if (!$db->query()) {
				JError::raiseError( 500, $db->stderr());
   		}	
      if($pcConfig['config_comments'] > 0) {
        if (file_exists($jcomments)) {
            JComments::deleteComments($id, 'com_prayercenter');
        } elseif (file_exists($jsc)) {
            jsitecomments::JSCdelComment('com_prayercenter', $id);
        }
      }
    	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid);
  		$this->setRedirect(JRoute::_($returnurl, false));
    }
    function pubrequest()
    {
      global $db, $pcConfig, $prayercenter;
      jimport('joomla.plugin.helper');
      $itemid = $prayercenter->PCgetItemid();
      $db		= JFactory::getDBO();
    	$cid = JRequest::getVar('delete','','post','array');
      $idarray = array_keys($cid);
    	while(list($key, $val) = each($cid))
    		{
      	$pubreq = "UPDATE #__prayercenter SET publishstate='1' WHERE id=".(int)$key."";
            $db->setQuery($pubreq);
        		if (!$db->query()) {
    					JError::raiseError( 500, $db->stderr());
        		}	
    		$model = $this->getModel('prayercenter');
    		$model->checkin();
        $query = $db->setQuery("SELECT * FROM #__prayercenter WHERE id=".(int)$key."");
        $result = $db->loadObjectList();
        $newrequester = $result[0]->requester;
        $newrequest = stripslashes($result[0]->request);
        $newemail = $result[0]->email;
        $sendpriv = $result[0]->displaystate;
        $sessionid = $result[0]->sessionid;
        if($sendpriv){
          if($pcConfig['config_distrib_type'] > 1 && !empty($pcConfig['config_pms_plugin'])){
            $prayercenter->PCsendPM($newrequesterid,$newrequester,$newrequest,$newemail,$sendpriv);
          }
        } elseif(!$sendpriv){
          if($pcConfig['config_distrib_type'] > 1 && !empty($pcConfig['config_pms_plugin'])){
            $prayercenter->PCsendPM($newrequesterid,$newrequester,$newrequest,$newemail,$sendpriv,(int)$key,$sessionid);
            }
        }
      }
    	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=moderate&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCREQSUBMIT')));
  		$this->setRedirect(JRoute::_($returnurl, false));
    }
    function unpubrequest()
    {
      global $db, $prayercenter;
   		JRequest::checkToken() or jexit( 'Invalid Token' );
      $db	= JFactory::getDBO();
      $itemid = $prayercenter->PCgetItemid();
    	$id = JRequest::getVar('id',null,'post','int');
    	$unpubreq = "UPDATE #__prayercenter SET publishstate='0' WHERE id=".(int)$id."";
      $db->setQuery($unpubreq);
  		if (!$db->query()) {
				JError::raiseError( 500, $db->stderr());
  		}	
  		$model = $this->getModel('prayercenter');
  		$model->checkin();
    	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=moderate&Itemid='.$itemid);
  		$this->setRedirect(JRoute::_($returnurl, false));
    }
    function pcCaptchaValidate($returnto,$itemid,$modtype,$task){
      global $pcConfig, $prayercenter;
      $JVersion = new JVersion();
      if ($pcConfig['config_captcha'] == '1') {
        $scode = JRequest::getVar('security_code',null,'post');
        if (!$prayercenter->PCCaptchaValidate($scode,'newreq')){
          if(isset($_GET['modtype'])){
        		$this->setRedirect(JRoute::_($returnto.'&'.$modtype.'='.htmlentities(JText::_('PCINVALIDCODE')), false));
          } else {
          	$returnurl = JRoute::_('index.php?option=com_prayercenter&task='.$task.'&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDCODE')));
        		$this->setRedirect(JRoute::_($returnurl, false));
          }
        } 
      } elseif($pcConfig['config_captcha'] == '3' && JPluginHelper::isEnabled('system','crosscheck')){
        $results = plgSystemCrossCheck::checkCrossChk(JRequest::getVar('user_code',null,'method'));
        if($results !== true){
          if(isset($_GET['modtype'])){
        		$this->setRedirect(JRoute::_($returnto.'&'.$modtype.'='.$results, false));
          } else {
          	$returnurl = JRoute::_('index.php?option=com_prayercenter&task='.$task.'&Itemid='.$itemid.'&return_msg='.$results.'-'.$test.'-'.$test2);
        		$this->setRedirect(JRoute::_($returnurl, false));
          }
        }
      } elseif($pcConfig['config_captcha'] == '6' && $pcConfig['config_recap_pubkey'] != "" && $pcConfig['config_recap_privkey'] != ""){
          require_once(JPATH_ROOT.'/components/com_prayercenter/assets/captcha/recaptchalib.php');
          $privatekey = $pcConfig['config_recap_privkey'];
          $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);    
          if (!$resp->is_valid) {     
            if(isset($_GET['modtype'])){
          		$this->setRedirect(JRoute::_($returnto.'&'.$modtype.'='.htmlentities(JText::_('PCINVALIDCODE')), false));
            } else {
            	$returnurl = JRoute::_('index.php?option=com_prayercenter&task='.$task.'&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDCODE')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          }
       } elseif($pcConfig['config_captcha'] == '7' && (real)$JVersion->RELEASE >= 2.5){
          $session = JFactory::getSession();
          $respchk = $session->has('pc_respchk');
          $plugin  = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
          $captcha = JCaptcha::getInstance($plugin, array('namespace' => 'adminForm'));
          $captcha_code = "";
          $resp = $captcha->checkAnswer($captcha_code);
          if($resp == false && !$respchk) {     
            if(isset($_GET['modtype'])){
          		$this->setRedirect(JRoute::_($returnto.'&'.$modtype.'='.htmlentities(JText::_('PCINVALIDCODE')), false));
            } else {
            	$returnurl = JRoute::_('index.php?option=com_prayercenter&task='.$task.'&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCINVALIDCODE')));
          		$this->setRedirect(JRoute::_($returnurl, false));
            }
          } elseif($respchk) {
            $session->clear('pc_respchk');
          }
        }
      return true;
    }
}
?>