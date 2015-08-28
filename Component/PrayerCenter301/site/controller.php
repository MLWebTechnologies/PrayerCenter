<?php
/* *************************************************************************************
Title          PrayerCenter Component for Joomla
Author         Mike Leeper
Enhancements   Douglas Machado & Christina Ishii
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
class PrayerCenterController extends JControllerLegacy
{
	var $pcConfig;
	function __construct() {
		parent::__construct();
		$pcParams = JComponentHelper::getParams('com_prayercenter');
		$pcParamsArray = $pcParams->toArray();
		foreach($pcParamsArray['params'] as $name => $value){
			$pcConfig[(string)$name] = (string)$value;
		}
    	$this->pcConfig = $pcConfig;
  }
	function display($cachable = false, $urlparams = false){
		parent::display();
  }
  function confirm(){
    global $db, $prayercenter, $pcConfig;
    $db		= JFactory::getDBO();
    $id = JFilterOutput::cleanText(JRequest::getVar('id',null,'get','int'));
    $sessionid = JFilterOutput::cleanText(JRequest::getVar('sessionid',null,'get','string'));
    $itemid = $prayercenter->PCgetItemid();
    if(is_numeric($id) && $prayercenter->PCSIDvalidate($sessionid)){
      $db->setQuery("SELECT request,requester,email,displaystate FROM #__prayercenter WHERE id='".$id."' AND sessionid='".$sessionid."' AND publishstate='0'");
      $cresults = $db->loadObjectList();    
      if(count($cresults)>0){
    		$db->setQuery("UPDATE #__prayercenter SET publishstate='1' WHERE id='".$id."' AND sessionid='".$sessionid."'");
    		if (!$db->query()) {
    			die("SQL error" . $db->stderr(true));
    		}	
        $sendpriv = $cresults[0]->displaystate;
        if($sendpriv){
          if(JPluginHelper::isEnabled('system','prayercenteremail')){
            $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_notification',array('0'=>$id));
            $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_prayer_chain',array('0'=>$id));
          }
          if($pcConfig['config_distrib_type'] > 1 && $pcConfig['config_pms_plugin']){
            $prayercenter->PCsendPM($cresults[0]->requester,$cresults[0]->request,$cresults[0]->email,$sendpriv);
          }
        } elseif(!$sendpriv){
          if(JPluginHelper::isEnabled('system','prayercenteremail')){
            $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_notification',array('0'=>$id));
          }
          if($pcConfig['config_distrib_type'] > 1 && $pcConfig['config_pms_plugin']){
            $prayercenter->PCsendPM($cresults[0]->requester,$cresults[0]->request,$cresults[0]->email,$sendpriv);
          }
         }
    	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCREQSUBMIT'),ENT_COMPAT,'UTF-8'));
  		$this->setRedirect(JRoute::_($returnurl, false));
    }
   }
  	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid);
		$this->setRedirect(JRoute::_($returnurl, false));
  }
  function confirm_adm(){
    global $db, $prayercenter, $pcConfig;
    $db		= JFactory::getDBO();
    $id = JFilterOutput::cleanText(JRequest::getVar('id',null,'get','int'));
    $sessionid = JFilterOutput::cleanText(JRequest::getVar('sessionid',null,'get','string'));
    $itemid = $prayercenter->PCgetItemid();
    if(is_numeric($id) && $prayercenter->PCSIDvalidate($sessionid)){
      $db->setQuery("SELECT request,requester,email,displaystate FROM #__prayercenter WHERE id='".$id."' AND sessionid='".$sessionid."' AND publishstate='0'");
      $cresults = $db->loadObjectList();    
      if(count($cresults)>0){
    		$db->setQuery("UPDATE #__prayercenter SET publishstate='1' WHERE id='".$id."' AND sessionid='".$sessionid."'");
    		if (!$db->query()) {
    			die("SQL error" . $db->stderr(true));
    		}	
        $sendpriv = $cresults[0]->displaystate;
        if($sendpriv){
          if(JPluginHelper::isEnabled('system','prayercenteremail')){
            $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_notification',array('0'=>$id));
            $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_prayer_chain',array('0'=>$id));
          }
          if($pcConfig['config_distrib_type'] > 1 && $pcConfig['config_pms_plugin']){
            $prayercenter->PCsendPM($cresults[0]->requester,$cresults[0]->request,$cresults[0]->email,$sendpriv);
          }
        } elseif(!$sendpriv){
          if(JPluginHelper::isEnabled('system','prayercenteremail')){
            $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_notification',array('0'=>$id));
          }
          if($pcConfig['config_distrib_type'] > 1 && $pcConfig['config_pms_plugin']){
            $prayercenter->PCsendPM($cresults[0]->requester,$cresults[0]->request,$cresults[0]->email,$sendpriv);
          }
        }
      	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCREQAPPROVE'),ENT_COMPAT,'UTF-8'));
    		$this->setRedirect(JRoute::_($returnurl, false));
      }
    }
  	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid);
 		$this->setRedirect(JRoute::_($returnurl, false));
  }
  function delreq_adm(){
    global $db, $prayercenter;
    $db		= JFactory::getDBO();
    $id = JFilterOutput::cleanText(JRequest::getVar('id',null,'get','int'));
    $sessionid = JFilterOutput::cleanText(JRequest::getVar('sessionid',null,'get','string'));
    $itemid = $prayercenter->PCgetItemid();
    if(is_numeric($id) && $prayercenter->PCSIDvalidate($sessionid)){
      $db->setQuery("SELECT COUNT(id) FROM #__prayercenter WHERE id='".$id."' AND sessionid='".$sessionid."' AND publishstate='0'");
      $cresults = $db->loadResult();    
      if($cresults>0){
        $db->setQuery("DELETE FROM #__prayercenter WHERE id='".$id."' AND sessionid='".$sessionid."'");
    		if (!$db->query()) {
					JError::raiseError( 500, $db->stderr());
    		}	
      }
    }
  	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid);
 		$this->setRedirect(JRoute::_($returnurl, false));
  }
  function confirm_sub(){
    global $db, $prayercenter;
    $db		= JFactory::getDBO();
    $id = JFilterOutput::cleanText(JRequest::getVar('id',null,'get','int'));
    $sessionid = JFilterOutput::cleanText(JRequest::getVar('sessionid',null,'get','string'));
    $itemid = $prayercenter->PCgetItemid();
    if(is_numeric($id) && $prayercenter->PCSIDvalidate($sessionid)){
      $db->setQuery("SELECT email FROM #__prayercenter_subscribe WHERE id='".$id."' AND sessionid='".$sessionid."' AND approved='0'");
      $subresults = $db->loadObjectList();    
      if(count($subresults)>0){
    		$db->setQuery("UPDATE #__prayercenter_subscribe SET approved='1' WHERE id='".$id."' AND sessionid='".$sessionid."'");
    		if (!$db->query()) {
    			die("SQL error" . $db->stderr(true));
    		}	
        if(JPluginHelper::isEnabled('system','prayercenteremail')){
          $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_subscribe',array('0'=>$subresults[0]->email));
          if (($pcConfig['config_email_subscribe'])&&($pcConfig['config_admin_approve_subscribe'] == 2)) {
            $results = plgSystemPrayerCenterEmail::pcEmailTask('PCadmin_email_subscribe_notification',array('0'=>$subresults[0]->email));
          }
        }
      	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=subscribe&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCENTRYACCEPTED'),ENT_COMPAT,'UTF-8'));
    		$this->setRedirect(JRoute::_($returnurl, false));
      }
    }
  	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=subscribe&Itemid='.$itemid);
 		$this->setRedirect(JRoute::_($returnurl, false));
  }
  function confirm_unsub(){
    global $db, $prayercenter;
    $db		= JFactory::getDBO();
    $id = JFilterOutput::cleanText(JRequest::getVar('id',null,'get','int'));
    $sessionid = JFilterOutput::cleanText(JRequest::getVar('sessionid',null,'get','string'));
    $itemid = $prayercenter->PCgetItemid();
    if(is_numeric($id) && $prayercenter->PCSIDvalidate($sessionid)){
      $db->setQuery("SELECT email FROM #__prayercenter_subscribe WHERE id='".$id."' AND sessionid='".$sessionid."' AND approved='1'");
      $unsubresults = $db->loadObjectList();    
      if(count($unsubresults)>0){
        $db->setQuery("DELETE FROM #__prayercenter_subscribe WHERE id='".$id."' AND sessionid='".$sessionid."'");
    		if (!$db->query()) {
    			die("SQL error" . $db->stderr(true));
    		}	
        if(JPluginHelper::isEnabled('system','prayercenteremail')){
          $results = plgSystemPrayerCenterEmail::pcEmailTask('PCemail_unsubscribe',array('0'=>$unsubresults[0]->email));
        }
      	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=subscribe&Itemid='.$itemid.'&return_msg='.htmlentities(JText::_('PCENTRYREMOVED'),ENT_COMPAT,'UTF-8'));
    		$this->setRedirect(JRoute::_($returnurl, false));
      }
    }
  	$returnurl = JRoute::_('index.php?option=com_prayercenter&task=subscribe&Itemid='.$itemid);
 		$this->setRedirect(JRoute::_($returnurl, false));
  }
  function newreq($cachable = false){
		JRequest::setVar('view', JRequest::getCmd('view', 'newreq') );
		parent::display($cachable);
    }
  function subscribe(){
		JRequest::setVar('view', 'subscribe' );
		parent::display();
    }
  function unsubscribe(){
		JRequest::setVar('view', 'subscribe' );
		parent::display();
    }
  function view(){
    global $pcConfig;
		$view = $this->getView('list', 'html');
    if($pcConfig['config_view_template'] == 1) {
  		$view->setLayout('rounded');
    }elseif($pcConfig['config_view_template'] == 2) {
  		$view->setLayout('basic');
    }
		$view->display();
   }
  function pdf(){
   	global $pc_rights, $prayercenter, $pcConfig;
    if ($pc_rights->get('pc.view') && $pcConfig['config_show_pdf']){
      $lang = Jfactory::getLanguage();
      $lang->load( 'com_prayercenter', JPATH_SITE);  
      $headerarr = array(utf8_encode(JText::_('PCMODREQ')),utf8_encode(JText::_('PCMODREQR')));
  		$listtype = JRequest::getVar( 'listtype', null, 'method', 'int' );
      require_once('components/com_prayercenter/helpers/pc_pdf_class.php');
  		$pdf = new PDF();
      $pdf->listtype = $listtype;
      $pdf->AddPage();
      $pdf->Ln(7);
      $pdf->SetFont('helvetica','',10);
      if($listtype == 0){
    		$id = JRequest::getVar( 'id', null, 'method', 'int' );
        $pdf->Table($headerarr,"SELECT * FROM #__prayercenter WHERE id='$id' AND publishstate='1' AND displaystate='1'");
      } elseif($listtype == 1){
        $pdf->Table($headerarr,"SELECT * FROM #__prayercenter WHERE publishstate='1' AND displaystate='1' AND date=CURDATE() ORDER BY topic,DATE_FORMAT(CONCAT_WS(' ',date,time),'%Y-%m-%d %T') DESC");
      } elseif($listtype == 2){
        $pdf->Table($headerarr,"SELECT topic,request,requester FROM #__prayercenter WHERE publishstate='1' AND displaystate='1' AND WEEKOFYEAR(date)=WEEKOFYEAR(CURDATE()) AND YEAR(date)=YEAR(CURDATE()) ORDER BY topic,DATE_FORMAT(CONCAT_WS(' ',date,time),'%Y-%m-%d %T') DESC");
      }
      $pdf->Output();
      exit(0);
    } else {
      echo '<div class="componentheading">'.utf8_encode(JText::_('PCTITLE')).'</div>';
      echo '<h5><center>'.JText::_('JERROR_ALERTNOAUTHOR').'<br />'.utf8_encode(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST')).'</center></h5>';
      echo '<br /><br /><br /><br />';
      $notAuth = 1;
    }
  }
  function view_links(){
		JRequest::setVar('view', 'links' );
		parent::display();
  }
  function view_devotion(){
		JRequest::setVar('view', 'devotions' );
		parent::display();
  }
  function moderate(){
    global $pc_rights, $pcConfig;
    if ( $pcConfig['config_use_admin_alert'] > 1 && $pc_rights->get('pc.moderate') ){
  		JRequest::setVar('view', 'moderate' );
  		parent::display();
    } else {
      echo '<div class="componentheading">'.htmlentities(JText::_('PCTITLE')).'</div>';
      echo '<h5><center>'.JText::_('JERROR_ALERTNOAUTHOR').'<br />'.JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST').'</center></h5>';
      echo '<br /><br /><br /><br />';
      $notAuth = 1;
    }
  }
  function edit(){
    $eid = JRequest::getVar('id',null,'get','int');
    $eid = JFilterOutput::cleanText($eid);
 		JRequest::setVar('view', 'edit' );
 		parent::display();
   }
  function view_request(){
    $eid = JRequest::getVar('id',null,'request','int');
		JRequest::setVar('view', 'showreq' );
		parent::display();
    }
  function rss(){
  	global $db, $prayercenter, $pc_rights, $pcConfig;
    $user = JFactory::getUser();
    $app = JFactory::getApplication();
    $offset = "";
    if ($pc_rights->get('pc.view') && $pcConfig['config_show_pdf']){
      $lang = Jfactory::getLanguage();
      $lang->load( 'com_prayercenter', JPATH_SITE); 
      $livesite = JURI::base();
      $sitename = $app->getCfg( 'sitename' );
      $itemid = $prayercenter->PCgetItemid();
      $config_rss_num = $pcConfig['config_rss_num'];
      $db		= JFactory::getDBO();
    	while( @ob_end_clean() );
   		require_once('components/com_prayercenter/assets/rss/feedcreator.php');
    	$feed_type = 'RSS2.0';
    	$filename = 'pc_feed.xml';
      $cacheDir = JPATH_BASE.'/cache';
    	$cachefile = $cacheDir.'/'. $filename;
    	$rss 	= new UniversalFeedCreator();
    	$image 	= new FeedImage();
    	if ( $pcConfig['config_enable_rss_cache'] ) {
    		$rss->useCached( $feed_type, $cachefile, $pcConfig['config_rss_update_time'] );
    	}
    	$rss->title = $sitename.' - '.utf8_encode(JText::_('PCTITLE'));
    	$rss->description = utf8_encode(JText::_('PCRSSFEEDMSG')).' '.$sitename;
    	$rss->link = htmlspecialchars( $livesite).'index.php?option=com_prayercenter&amp;Itemid='.$itemid;
    	$rss->syndicationURL = htmlspecialchars( $livesite).'index.php?option=com_prayercenter&amp;Itemid='.$itemid;
    	$rss->cssStyleSheet	= NULL;
    	$feed_image	= $livesite.'components/com_prayercenter/assets/images/prayer.png';
    	if ( $feed_image ) {
    		$image->url 		= $feed_image;
    		$image->link 		= $rss->link;
    		$image->title 		= 'Powered by Joomla! & PrayerCenter';
    		$image->description	= $rss->description;
    		$rss->image 		= $image;
    	}
      $db->setQuery( "SELECT * FROM #__prayercenter "
      . "\n WHERE publishstate = 1 "
      . "\n AND displaystate = 1 "
      . "\n ORDER BY id DESC "
      . "\n LIMIT ".$config_rss_num.""
      );
      $rows = $db->loadObjectList();
   		foreach($rows as $row) {
    		$item = new FeedItem();
    		$item->title = utf8_encode(html_entity_decode($row->requester));
        $item->link = JRoute::_("index.php?option=com_prayercenter&amp;Itemid=".$itemid."&amp;task=view_request&amp;type=rss&amp;id=".$row->id);
        $words = $row->request;
    			if( $pcConfig['config_rss_limit_text'] ) {
    				$words = substr( $words, 0, $pcConfig['config_rss_text_length'] );
    			}
    			$item->description = $words;
          $seconds = date_offset_get(new DateTime);
          $offset =  $seconds / 3600;
          $itemdate = date("r",strtotime($row->date.' '.$row->time.' '.$offset));
    			$item->date	= $itemdate;
    			$rss->addItem( $item );
    		}
    	$rss->saveFeed( $feed_type, $cachefile );
    } else {
      $this->setRedirect( 'index.php?option=com_prayercenter', JText::_('JERROR_ALERTNOAUTHOR') );
    }
  }
  function PCCapValid()
  {
    global $pcConfig;
    $JVersion = new JVersion();
    $captcha = JRequest::getVar( 'cap', null, 'post', 'int' );
    $modtype = JRequest::getVar( 'modtype', null, 'post', 'string' );
    $mod = JRequest::getVar( 'mod', null, 'post', 'string' );
    $returnto = $_SERVER['HTTP_REFERER'];
    preg_match("!index.php\?!",$returnto,$match);
    if(!$match) $returnto = $returnto.'index.php?';
  	if($captcha == 1){
      $session = JFactory::getSession();
      if(isset($mod)){
      $security_code = strtolower($session->get($mod.'_security_code'));
      } else {
      $security_code = strtolower($session->get('pc_security_code'));
      }
      $usercode = strtolower((JRequest::getVar( 'usercode', null, 'post', 'string' )));
      if($security_code != $usercode){
        $i = $session->get('pc_max_attempts');
        $i++;
        $session->set('pc_max_attempts',$i);
        if($pcConfig['config_captcha_maxattempts'] > $i){
          $message = JText::_('PCINVALIDCODE');
        } else {
          $message = JText::_('PCCAPTCHAMAXATTEMPTS');
        }
        exit($message);
      } else {
        exit(true);
      }
    } elseif($captcha == 6){
      require_once(JPATH_ROOT.'/components/com_prayercenter/assets/captcha/recaptchalib.php');
      $privatekey = $pcConfig['config_recap_privkey'];
      $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge"], $_POST["recaptcha_response"]);    
      if (!$resp->is_valid) {     
        $message = htmlentities(JText::_('PCINVALIDCODE'),ENT_COMPAT,'UTF-8');
        exit($message);
      } else {
        exit(true);
      }
    } elseif($captcha == 7 && (real)$JVersion->RELEASE >= 2.5){
      $session =& JFactory::getSession();
      $plugin  = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
      $captcha = JCaptcha::getInstance($plugin, array('namespace' => 'adminForm'));
      $captcha_code = "";
      $resp = $captcha->checkAnswer($captcha_code);
      if($resp == false) {     
        $message = htmlentities(JText::_('PCINVALIDCODE'));
        echo $message;
      } else {
        $session->set('pc_respchk',true);
        echo true;
      }
    }
  }
}
?>