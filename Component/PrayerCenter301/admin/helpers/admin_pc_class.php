<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
class prayercenteradmin 
{
	function PCsideBar2()
	{
		$lang		=& JFactory::getLanguage();
    $user =& JFactory::getUser();
    $version = new JVersion();
    include_once('components/com_prayercenter/helpers/pc_version.php');
    $pcversion = new PCVersion();
    $task = JRequest::getVar('task');
		if($user->authorise('core.admin')){?>
      <div class="row-fluid">
      	<div class="span2">
      		<div class="sidebar-nav">
      			<ul class="nav nav-list">
      				<li class="nav-header"><?php echo JText::_('SUBMENU'); ?></li>
      				<li class="<?php echo $task == '' ? 'active' : ''?>"><a href="<?php echo JURI::base(); ?>index.php?option=com_prayercenter"><?php echo JText::_('Dashboard'); ?></a></li>
      				<li class="<?php echo $task == 'manage_plans' ? 'active' : ''?>"><a href="<?php echo JURI::base(); ?>index.php?option=com_prayercenter&task=manage_plans"><?php echo JText::_('Manage Plans'); ?></a></li>
      				<li class="<?php echo $task == 'manage_books' ? 'active' : ''?>"><a href="<?php echo JURI::base(); ?>index.php?option=com_prayercenter&task=manage_books"><?php echo JText::_('Manage Bible Books'); ?></a></li>
      				<li class="<?php echo $task == 'manage_sub' ? 'active' : ''?>"><a href="<?php echo JURI::base(); ?>index.php?option=com_prayercenter&task=manage_sub"><?php echo JText::_('Manage Subscribers'); ?></a></li>
      				<li class="<?php echo $task == 'manage_css' ? 'active' : ''?>"><a href="<?php echo JURI::base(); ?>index.php?option=com_prayercenter&task=manage_css"><?php echo JText::_('Manage CSS'); ?></a></li>
      				<li class="<?php echo $task == 'manage_link' ? 'active' : ''?>"><a href="<?php echo JURI::base(); ?>index.php?option=com_prayercenter&task=manage_link"><?php echo JText::_('Manage Links'); ?></a></li>
      				<li class="<?php echo $task == 'manage_lang' ? 'active' : ''?>"><a href="<?php echo JURI::base(); ?>index.php?option=com_prayercenter&task=manage_lang"><?php echo JText::_('Manage Languages'); ?></a></li>
      			</ul>
      		</div>
      	<?php if($task != 'manage_lang') {
            echo '</div>'; 
          }
		}
	}
  function fullfileperms($perms)
  {
    if (($perms & 0xC000) == 0xC000) {
      // Socket
      $info = 's';
    } elseif (($perms & 0xA000) == 0xA000) {
      // Symbolic Link
      $info = 'l';
    } elseif (($perms & 0x8000) == 0x8000) {
      // Regular
      $info = '-';
    } elseif (($perms & 0x6000) == 0x6000) {
      // Block special
      $info = 'b';
    } elseif (($perms & 0x4000) == 0x4000) {
      // Directory
      $info = 'd';
    } elseif (($perms & 0x2000) == 0x2000) {
      // Character special
      $info = 'c';
    } elseif (($perms & 0x1000) == 0x1000) {
      // FIFO pipe
      $info = 'p';
    } else {
      // Unknown
      $info = 'u';
    }
    // Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));
    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));
    // World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));
    echo $info;
  }
  function findext($filename)
  {
    $filename = strtoupper($filename);
    $ext = preg_split("[\.]",$filename,-1,PREG_SPLIT_NO_EMPTY);
    $ext = $ext[1];
    return $ext;
  }
  function findimageext($filename)
  {
    $filename = strtoupper($filename);
    $ext = preg_split("[\.]",$filename,-1,PREG_SPLIT_NO_EMPTY);
    $n = count($ext)-1;
    $ext = $ext[$n];
    return $ext;
  }
  function FileSel( $name, &$active, $javascript=NULL, $directory=NULL ) {
    $pmsrefarray = array(
          1 => array ('val' => 'jim', 'desc' => ''.JText::_(' - (Requires JIM 1.0.1 or above)').''),
          2 => array ('val' => 'joomla', 'desc' => ''.JText::_(' - (Built-in Joomla Messaging Component. Requires Joomla 1.6 or above)').''),
          3 => array ('val' => 'messaging', 'desc' => ''.JText::_(' - (Requires Messaging 1.5 or above)').''),
          4 => array ('val' => 'missus', 'desc' => ''.JText::_(' - (Requires Missus 1.0 or above)').''),
          5 => array ('val' => 'mypms2', 'desc' => ''.JText::_(' - (Requires MyPMS II 2.0)').''),
          6 => array ('val' => 'primezilla', 'desc' => ''.JText::_(' - (Requires Primezilla 1.0.5 or above)').''),
          7 => array ('val' => 'privmsg', 'desc' => ''.JText::_(' - (Requires PrivMSG 2.1.0 or above)').''),
          8 => array ('val' => 'uddeim', 'desc' => ''.JText::_(' - (Requires uddeIM 1.8 or above)').'')
          );
		$SelFiles = JFolder::files( JPATH_COMPONENT.DS.$directory );
		$files 	= array(  JHTML::_( 'select.option', 0, '- Select -' ) );
		foreach ( $SelFiles as $file ) {
			if ( eregi( "php", $file ) ) {
			  preg_match('/^plg\.pms\.(.*)\.php$/',$file,$match);
				if($match){
          $keyarr = $this->pc_array_search_recursive($match[1], $pmsrefarray);
          $key = $keyarr[0];
          $pmsfile = $pmsrefarray[$key]['desc'];
          $files[] = JHTML::_( 'select.option', $match[1], ucfirst($match[1].$pmsfile) );
        }
			}
		}
		$files = JHTML::_( 'select.genericlist', $files, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );
		return $files;
  }
	function pc_get_php_setting($val, $colour=0, $yn=1) {
		$r =  (ini_get($val) == '1' ? 1 : 0);
		if ($colour) {
			if ($yn) {
				$r = $r ? '<span style="color: green;">ON</span>' : '<span style="color: red;">OFF</span>';
			} else {
				$r = $r ? '<span style="color: red;">ON</span>' : '<span style="color: green;">OFF</span>';
			}
			return $r;
		} else {
			return $r ? 'ON' : 'OFF';
		}
	}
	function pc_get_server_software() {
		if (isset($_SERVER['SERVER_SOFTWARE'])) {
			return $_SERVER['SERVER_SOFTWARE'];
		} else if (($sf = phpversion() <= '4.2.1' ? getenv('SERVER_SOFTWARE') : $_SERVER['SERVER_SOFTWARE'])) {
			return $sf;
		} else {
			return 'n/a';
		}
	}
	function PCfooter() {
    include_once('components/com_prayercenter/helpers/pc_version.php');
    $pcversion = new PCVersion();
	  ?>
		<div align="center" class="small">
		  <i>"Lift up holy hands in prayer,and praise the Lord."</i>&nbsp;<a href="http://www.biblegateway.com/passage/?search=Psalm%20134:2;&version=51;" target="_blank"><b>Psalm 134:2</b></a>
		  <br />
      <a href="<?php echo $pcversion->getUrl()?>" target="_blank">PrayerCenter Component - <?php echo $pcversion->getShortCopyright();?></a>
		</div>
	  <?php
	}
  function PCAsendPM($newrequester,$newrequest,$newemail,$sendpriv,$lastid=null,$sessionid=null)
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
    $PCPMSPlugin->send_private_messaging($newrequester,$newrequest,$newemail,$sendpriv,$lastid,$sessionid);
  }
  function PCgetTopics(){
    $topicArray = array (
         1 => array ('val' => '0', 'text' => ''.JText::_('PCSELECTTOPIC0').''),         
         2 => array ('val' => '1', 'text' => ''.JText::_('PCSELECTTOPIC1').''),         
         3 => array ('val' => '2', 'text' => ''.JText::_('PCSELECTTOPIC2').''),         
         4 => array ('val' => '3', 'text' => ''.JText::_('PCSELECTTOPIC3').''),         
         5 => array ('val' => '4', 'text' => ''.JText::_('PCSELECTTOPIC4').''),         
         6 => array ('val' => '5', 'text' => ''.JText::_('PCSELECTTOPIC5').''),         
         7 => array ('val' => '6', 'text' => ''.JText::_('PCSELECTTOPIC6').''),         
         8 => array ('val' => '7', 'text' => ''.JText::_('PCSELECTTOPIC7').''),         
         9 => array ('val' => '8', 'text' => ''.JText::_('PCSELECTTOPIC8').''),         
         10 => array ('val' => '9', 'text' => ''.JText::_('PCSELECTTOPIC9').''),         
         11 => array ('val' => '10', 'text' => ''.JText::_('PCSELECTTOPIC10').''),         
         12 => array ('val' => '11', 'text' => ''.JText::_('PCSELECTTOPIC11').''),         
         13 => array ('val' => '12', 'text' => ''.JText::_('PCSELECTTOPIC12').''),         
         14 => array ('val' => '13', 'text' => ''.JText::_('PCSELECTTOPIC13').''),         
         15 => array ('val' => '14', 'text' => ''.JText::_('PCSELECTTOPIC14').''),         
         16 => array ('val' => '15', 'text' => ''.JText::_('PCSELECTTOPIC15').''),         
         17 => array ('val' => '16', 'text' => ''.JText::_('PCSELECTTOPIC16').''),         
         18 => array ('val' => '17', 'text' => ''.JText::_('PCSELECTTOPIC17').''),         
         19 => array ('val' => '18', 'text' => ''.JText::_('PCSELECTTOPIC18').''),         
         20 => array ('val' => '19', 'text' => ''.JText::_('PCSELECTTOPIC19').''),         
         21 => array ('val' => '20', 'text' => ''.JText::_('PCSELECTTOPIC20').''), 
         22 => array ('val' => '21', 'text' => ''.JText::_('PCSELECTTOPIC21').''),       
         23 => array ('val' => '22', 'text' => ''.JText::_('PCSELECTTOPIC22').'')       
          );
    return $topicArray;
  }
	function PCquickiconButton( $link, $image, $text, $attrib="" )
	{
		global $mainframe;
		$lang		=& JFactory::getLanguage();
    $template	= JFactory::getApplication()->getTemplate();
		?>
			<div class="icon-wrapper"><div class="icon">
    <?php
			$image = JHTML::_('image',  $image, '/templates/'. $template .'/images/header/', NULL, NULL, strip_tags($text) ); 
			$image .=	'<span>'.$text.'</span>';
  		echo JHTML::_('link', JRoute::_($link), $image, $attrib);
    ?>
			</div>
		</div>
		<?php
	}
  function PCparseXml($xmlfile){
      $data = "";
			if (file_exists($xmlfile)){
			$data = JApplicationHelper::parseXMLInstallFile($xmlfile);
			}
    return $data;
	}
	 function PCChangeLog() {
	 	$output = '';
		$options = array();
		$options['rssUrl']		= 'http://www.mlwebtechnologies.com/index.php?option=com_content&view=category&id=53&format=feed';
		$options['cache_time']	= 86400;
		$rssDoc =& JFactory::getXMLparser('RSS', $options);
		if ( $rssDoc == false ) {
			$output = JText::_('Error: Feed not retrieved');
		} else {	
			$title 	= $rssDoc->get_title();
			$link	= $rssDoc->get_link();
			$output = '<table class="adminlist">';
			$items = array_slice($rssDoc->get_items(), 0, 3);
			$numItems = count($items);
      if($numItems == 0) {
      	$output .= '<tr><th>' .JText::_('PrayerCenter change log not available at this time'). '</th></tr>';
      } else {
       	$output .= '<tr><td><textarea cols="70" rows="40">';
       	$k = 0;
        for( $j = 0; $j < $numItems; $j++ ) {
          $item = $items[$j];
  				if($item->get_description()) {
           	$output .= ltrim($this->PCp2nl($item->get_description()),"pcnews\npcconfig\npclang\n");
					}
					$k = 1 - $k;
      }
    	$output .= '</textarea></td></tr>';
    }
		$output .= '</table>';
	 }	 	
	 	return $output;
	}
	function PClimitText($text, $wordcount)
	{
		if(!$wordcount) {
			return $text;
		}
		$texts = explode( ' ', $text );
		$count = count( $texts );
		if ( $count > $wordcount )
		{
			$text = '';
			for( $i=0; $i < $wordcount; $i++ ) {
				$text .= ' '. $texts[$i];
			}
			$text .= '...';
		}
		return $text;
	}
  function pc_array_search_recursive( $needle, $haystack )
  {
     $path = NULL;
     $keys = array_keys($haystack);
     while (!$path && (list($toss,$k)=each($keys))) {
        $v = $haystack[$k];
        if (is_scalar($v)) {
           if (strtolower($v)===strtolower($needle)) {
              $path = array($k);
           }
        } elseif (is_array($v)) {
           if ($path=$this->pc_array_search_recursive( $needle, $v )) {
              array_unshift($path,$k);
           }
        }
     }
     return $path;
  }
  function PCkeephtml($string){
    $res = htmlentities($string,ENT_COMPAT,'UTF-8');
    $res = str_replace("&lt;","<",$res);
    $res = str_replace("&gt;",">",$res);
    $res = str_replace("&quot;",'"',$res);
    $res = str_replace("&amp;",'&',$res);
    return $res;
  }
  function PCp2nl ($str) {
    return preg_replace(array("/<p[^>]*>/iU","/<\/p[^>]*>/iU","/<br[^>]*>/iU"),array("\n","","\n"),$str);
  }
  function PCRedirect($str,$msg=null) {
		$app = JFactory::getApplication();
		$app->redirect($str,$msg);
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
}
?>