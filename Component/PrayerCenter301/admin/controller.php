<?php
/**
* PrayerCenter Component for Joomla
* By Mike Leeper
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
/**
 * PrayerCenter Component Controller
 *
 */
class PrayerCenterController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = false)
    {
		$this->checkMig();
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'prayercenter'));
    parent::display();
    }
  function checkMig( $option='com_prayercenter' ){
    jimport('joomla.date.date');
    $dateset = new JDate();
    $now = $dateset->format('Y-m-d H:i:s');
    $db	= JFactory::getDBO();
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'requester'");
    $ckreqestertype = $db->loadObjectList();
    if($ckreqestertype[0]->Type != 'varchar(50)'){
      $db->setQuery( "ALTER TABLE #__prayercenter MODIFY requester varchar(50)");
  		if (!$db->query()) {
  			return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'sendto'");
    $cksendtotype = $db->loadObjectList();
    if($cksendtotype[0]->Type != 'datetime'){
      $db->setQuery( "ALTER TABLE #__prayercenter MODIFY sendto datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
  		if (!$db->query()) {
  			return JError::raiseWarning( 500, $db->stderr() );
  		}
  		$sql = "UPDATE #__prayercenter SET sendto='".$now."' WHERE publishstate=0";
  		$db->setQuery($sql);
  		if(!$db->query()) {
  			return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'checked_out_time'");
    $pctable_nm2 =  $db->loadObjectList();
    if(count($pctable_nm2)<1){
      $db->setQuery( "ALTER TABLE #__prayercenter ADD COLUMN checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER praise");
  		if (!$db->query()) {
			 return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'checked_out'");
    $pctable_nm3 =  $db->loadObjectList();
    if(count($pctable_nm3)<1){
      $db->setQuery( "ALTER TABLE #__prayercenter ADD COLUMN checked_out int(11) NOT NULL DEFAULT 0 AFTER checked_out_time");
  		if (!$db->query()) {
			 return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'sessionid'");
    $pctable_nm4 =  $db->loadObjectList();
    if(count($pctable_nm4)<1){
      $db->setQuery( "ALTER TABLE #__prayercenter ADD COLUMN sessionid varchar(50) NOT NULL AFTER checked_out");
  		if (!$db->query()) {
			 return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'title'");
    $pctable_nm5 =  $db->loadObjectList();
    if(count($pctable_nm5)<1){
      $db->setQuery( "ALTER TABLE #__prayercenter ADD COLUMN title varchar(100) NOT NULL AFTER sessionid");
  		if (!$db->query()) {
			 return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'topic'");
    $pctable_nm6 =  $db->loadObjectList();
    if(count($pctable_nm6)<1){
      $db->setQuery( "ALTER TABLE #__prayercenter ADD COLUMN topic int(11) NOT NULL DEFAULT 0 AFTER title");
  		if (!$db->query()) {
			 return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'hits'");
    $pctable_nm7 =  $db->loadObjectList();
    if(count($pctable_nm7)<1){
      $db->setQuery( "ALTER TABLE #__prayercenter ADD COLUMN hits int(11) NOT NULL DEFAULT 0 AFTER topic");
  		if (!$db->query()) {
			 return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter_subscribe LIKE 'sessionid'");
    $pctable_nm8 =  $db->loadObjectList();
    if(count($pctable_nm8)<1){
      $db->setQuery( "ALTER TABLE #__prayercenter_subscribe ADD COLUMN sessionid varchar(50) NOT NULL AFTER approved");
  		if (!$db->query()) {
			 return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'requesterid'");
    $pctable_nm9 =  $db->loadObjectList();
    if(count($pctable_nm9)<1){
      $db->setQuery( "ALTER TABLE #__prayercenter ADD COLUMN requesterid int(11) NOT NULL DEFAULT 0 AFTER id");
  		if (!$db->query()) {
			 return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
  }
  function optimizePCTables( $option='com_prayercenter' ){
    global $db;
    $db	= JFactory::getDBO();
    $dbcmds = array($db->name.'_data_seek',$db->name.'_num_rows',$db->name.'_fetch_assoc');
    $sql = "OPTIMIZE TABLE #__prayercenter, #__prayercenter_subscribe, #__prayercenter_links, #__prayercenter_devotions";
    $db->setQuery($sql);
		if (!$db->query()) {
		 return JError::raiseWarning( 500, $db->stderr() );
		}
    $rs_status = $db->query();
    $dbcmds[0]($rs_status, $dbcmds[1]($rs_status)-1);
    $row_status = $dbcmds[2]($rs_status);
		$this->setMessage("PrayerCenter database tables have been optimized.  (".ucfirst($row_status['Msg_type']).": ".$row_status['Msg_text'].")", 'message');
		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
    }
  function checkPCTables( $option='com_prayercenter' ){
    global $db;
    $db	= JFactory::getDBO();
    $dbcmds = array($db->name.'_data_seek',$db->name.'_num_rows',$db->name.'_fetch_assoc');
    $sql = "CHECK TABLE #__prayercenter, #__prayercenter_subscribe, #__prayercenter_links, #__prayercenter_devotions MEDIUM";
    $db->setQuery($sql);
		if (!$db->query()) {
		 return JError::raiseWarning( 500, $db->stderr() );
		}
    $rs_status = $db->query();
    $dbcmds[0]($rs_status, $dbcmds[1]($rs_status)-1);
    $row_status = $dbcmds[2]($rs_status);
		$this->setMessage("PrayerCenter database tables have been checked.  (".ucfirst($row_status['Msg_type']).": ".$row_status['Msg_text'].")", 'message');
		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
    }
  function repairPCTables( $option='com_prayercenter' ){
    global $db;
    $db	= JFactory::getDBO();
    $dbcmds = array($db->name.'_data_seek',$db->name.'_num_rows',$db->name.'_fetch_assoc');
    $sql = "REPAIR TABLE #__prayercenter, #__prayercenter_subscribe, #__prayercenter_links, #__prayercenter_devotions";
    $db->setQuery($sql);
		if (!$db->query()) {
		 return JError::raiseWarning( 500, $db->stderr() );
		}
    $rs_status = $db->query();
    $dbcmds[0]($rs_status, $dbcmds[1]($rs_status)-1);
    $row_status = $dbcmds[2]($rs_status);
		$this->setMessage("PrayerCenter database tables have been repaired.  (".ucfirst($row_status['Msg_type']).": ".$row_status['Msg_text'].")", 'message');
		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
    }
  function backupPCTables( $option='com_prayercenter', $locks=true, $compress=false, $drop_tables=true, $download=true ){
    global $db;
    $db	= JFactory::getDBO();
    $dbcmds = array($db->name.'_data_seek',$db->name.'_num_rows',$db->name.'_fetch_assoc',$db->name.'_fetch_row');
    $app = JFactory::getApplication('site');
    $dbprefix = $app->getCfg( 'dbprefix' );
    $fpath = 'components'.DS.'com_prayercenter'.DS;
    $filename = ($compress ? 'prayercenter.sql.gz' : 'prayercenter.sql');
    $fname = $fpath.$filename;
    $value = "";
    $tablestr = 'prayercenter,prayercenter_subscribe,prayercenter_links,prayercenter_devotions';
    $tables = preg_split('/[,]/',$tablestr, -1, PREG_SPLIT_NO_EMPTY);
    $null_values = array( '0000-00-00', '00:00:00', '0000-00-00 00:00:00');
		$compress ? $fp = gzopen($fname, 'w9') : $fp = fopen($fname, 'w');   
		$sql = "LOCK TABLES #__prayercenter WRITE, #__prayercenter_subscribe WRITE, #__prayercenter_links WRITE, #__prayercenter_devotions WRITE";
    $db->setQuery($sql);
		if (!$db->query()) {
		 return JError::raiseWarning( 500, $db->stderr() );
		}
		$value .= '# '."\n";
		$value .= '# PrayerCenter Database Table Dump'."\n";
		$value .= '# Host: ' . $app->getCfg( 'sitename' ) . "\n";
		$value .= '# Generated: ' . date('M j, Y') . ' at ' . date('H:i:s') . "\n";
		$value .= '# MySQL version: ' . $db->getVersion() . "\n";
		$value .= '# PHP version: ' . phpversion() . "\n";
		$value .= '# ' . "\n";
		$value .= '# Database: `' . $app->getCfg( 'db' ) . '`' . "\n";
		$value .= '# Tables: `' . str_replace("p"," P",$tablestr) . '`' . "\n";
		$value .= '# ' . "\n\n\n";
    foreach($tables as $table){
  		if ($drop_tables) {
  			$value .= 'DROP TABLE IF EXISTS `'.$dbprefix.$table.'`;' . "\n";
  		}
      $sql = "SHOW CREATE TABLE #__".$table;
      $db->setQuery($sql);
  		if (!($result = $db->query())) {
  		 return JError::raiseWarning( 500, $db->stderr() );
  		}
  		$row = $dbcmds[2]($result);
  		$value .= $row['Create Table'].';';
      $value .= "\n\n";
      $sql = "SELECT * FROM #__".$table;
      $db->setQuery($sql);
  		if (!($result = $db->query())) {
  		 return JError::raiseWarning( 500, $db->stderr() );
  		}
  	  $num_rows = $dbcmds[1]($result);
    	if ($num_rows > 0) {
    		if ($locks) {
    			$value .= 'LOCK TABLES #__'.$table.' WRITE;'."\n\n";
    		}
     		$value .= 'INSERT INTO #__'.$table;
    		$row = $dbcmds[2]($result);
    		$value .= ' (`' . implode('`,`', array_keys($row)) . '`)';
    		$value .= ' VALUES ';
    		$fields = count($row);
    		$dbcmds[0]($result, 0);
    		$value .= "\n";
    		if ($fp) {
    			$compress ? gzwrite($fp, $value) : fwrite ($fp, $value);
    		}
     		$j=0;
    		$size = 0;
    		while ($row = $dbcmds[3]($result))
    		{
    			if ($fp)
    			{
    				$i = 0;
    				$compress ? $size += gzwrite($fp, '(') : $size += fwrite ($fp, '(');
    				for($x =0; $x < $fields; $x++)
    				{
    					if (!isset($row[$x]) || in_array($row[$x], $null_values)) {
    						$row[$x] = 'NULL';
    					} else {
    						$row[$x] = '\'' . str_replace("\n","\\n",addslashes($row[$x])) . '\'';
    					}
    					if ($i > 0)
    					{
    						$compress ? $size += gzwrite($fp, ',') : $size += fwrite ($fp, ",");
    					}
    					$compress ? $size += gzwrite($fp, $row[$x]) : $size += fwrite ($fp,  $row[$x]);
    					$i++;
    				}
    				$compress ? $size += gzwrite($fp, ')') : $size += fwrite ($fp, ')');
    				if ($j+1 < $num_rows && $size < 900000 )
    				{
    					$compress ? $size += gzwrite($fp, ",\n") : $size += fwrite ($fp, ",\n");
    				}	else {
    					$size = 0;
    					$compress ? gzwrite($fp, ';' . "\n\n\n") : fwrite ($fp, ';' . "\n\n\n");
    					if ($j+1 < $num_rows)
    					{
    						$compress ? gzwrite($fp, $insert) : fwrite ($fp, $insert);
    					} elseif($locks) {
    						$compress ? gzwrite($fp, 'UNLOCK TABLES;' . "\n") : fwrite ($fp, 'UNLOCK TABLES;' . "\n");
    					}
    				}
           $j++;
          }
         }
        $value = "";
      }
    }
		$sql = "UNLOCK TABLES";
    $db->setQuery($sql);
		if (!$db->query()) {
		 return JError::raiseWarning( 500, $db->stderr() );
		}
		$compress ? gzclose($fp) : fclose($fp);
		$fp = fopen($fname, 'rb');
		if ($fp && $download) {
        if(preg_match("/MSIE/",$_SERVER['HTTP_USER_AGENT'])){
          header("Content-type: application/octet-stream;");
      		header('Content-disposition: attachment; filename='.$filename.';');
      		header('Pragma: no-cache;');
      		header('Expires: 0;');
//          header("Location:index.php?option=com_prayercenter&task=utilities");
        } else { 
          header("Refresh:0; URL=index.php?option=com_prayercenter&task=utilities");
          header("Content-type: application/octet-stream;");
      		header('Content-disposition: attachment; filename='.$filename.';');
      		header('Pragma: no-cache;');
      		header('Expires: 0;');
        } 
  		while ($value = fread($fp,8192))
  		{
  			echo $value;
  			unset ($value);
  		}
  		$compress ? gzclose($fp) : fclose($fp);
  		@unlink ($fname);
    }
  }
  function restorePCTables( $option='com_prayercenter' ){
    global $db;
    $db	= JFactory::getDBO();
		jimport('joomla.installer.helper');
    if((!empty($_FILES['uploadedbkfile'])) && ($_FILES['uploadedbkfile']['error'] == 0)) {
      $filename = basename($_FILES['uploadedbkfile']['name']);
      $ext = substr($filename, strrpos($filename, '.') + 1);
      if($ext == "sql") {
          $newname = JPATH_ADMINISTRATOR.'/components/com_prayercenter/'.$filename;
          if (!file_exists($newname)) {
            if ((move_uploaded_file($_FILES['uploadedbkfile']['tmp_name'],$newname))) {
          		$buffer = file_get_contents($newname);
          		$queries = JInstallerHelper::splitSql($buffer);
          		foreach ($queries as $query)
          		{
          			$query = trim($query);
          			if ($query != '' && $query{0} != '#') {
          				$db->setQuery($query);
          				if (!$db->query()) {
                    return JError::raiseWarning( 500, $db->stderr() );
          				}
          			}
          		}
          		@unlink ($newname);
            	$this->setMessage("PrayerCenter database tables have been restored from path provided.", 'message');
          		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
              } else {
          		$this->setMessage("Error: A problem occurred during file upload!", 'error');
          		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
            }
          } else {
        		$this->setMessage("Error: File ".$_FILES['uploadedbkfile']['name']." already exists", 'error');
        		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
          }
      } else {
    		$this->setMessage("Error: Only .sql files are accepted for upload", 'error');
    		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
      }
    } else {
  		$this->setMessage("Error: No file uploaded", 'error');
  		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
    }
  }
  function manage_req( $option='com_prayercenter' )
  {
  	JRequest::setVar('view', 'managereq' );
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'managereq'));
  	parent::display();
  }
  function manage_dev( $option='com_prayercenter' )
  {
  	JRequest::setVar('view', 'managedevotions' );
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'managedevotions'));
  	parent::display();
  }
  function manage_link( $option='com_prayercenter' )
  {
  	JRequest::setVar('view', 'managelink' );
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'managelink'));
  	parent::display();
  }
  function manage_sub( $option='com_prayercenter' )
  {
  	JRequest::setVar('view', 'managesub' );
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'managesub'));
  	parent::display();
  }
  function manage_css( $option='com_prayercenter' )
  {
  	JRequest::setVar('view', 'managecss' );
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'managecss'));
  	parent::display();
  }
  function manage_lang( $option='com_prayercenter' )
  {
  	JRequest::setVar('view', 'managelang' );
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'managelang'));
  	parent::display();
  }
  function manage_files( $option='com_prayercenter' )
  {
  	JRequest::setVar('view', 'managefiles' );
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'managefiles'));
  	parent::display();
  }
  function edit() {
  	JRequest::setVar('view', 'editreq' );
  	parent::display();
  }
  function addlink() {
		JRequest::setVar( 'hidemainmenu', 1 );
  	JRequest::setVar('view', 'editlink' );
		JRequest::setVar( 'edit', false );
  	parent::display();
  }
  function editlink() {
		JRequest::setVar( 'hidemainmenu', 1 );
  	JRequest::setVar('view', 'editlink' );
		JRequest::setVar( 'edit', true );
  	parent::display();
  }
  function addlang() {
		JRequest::setVar( 'hidemainmenu', 1 );
  	JRequest::setVar('view', 'editlang' );
		JRequest::setVar( 'edit', false );
  	parent::display();
  }
  function editlang() {
		JRequest::setVar( 'hidemainmenu', 1 );
  	JRequest::setVar('view', 'editlang' );
		JRequest::setVar( 'edit', true );
  	parent::display();
  }
  function adddevotion() {
		JRequest::setVar( 'hidemainmenu', 1 );
  	JRequest::setVar('view', 'editdev' );
		JRequest::setVar( 'edit', false );
  	parent::display();
  }
  function editdevotion() {
		JRequest::setVar( 'hidemainmenu', 1 );
  	JRequest::setVar('view', 'editdev' );
		JRequest::setVar( 'edit', true );
  	parent::display();
  }
  function view_req() {
    JRequest::setVar('view', 'showreq');
    parent::display();
    }
  function support() {
    JRequest::setVar('view', 'support');
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'prayercenter'));
    parent::display();
    }
  function utilities() {
    JRequest::setVar('view', 'utilities');
		PrayerCenterHelper::addSubmenu(JRequest::getCmd('view', 'prayercenter'));
    parent::display();
    }
  function showmigwiz( $option='com_prayercenter' ){
    global $db;
    $okToContinue = true;
  	$filePath = JPATH_COMPONENT;
  	$checkfileName = 'prayerrequest_copied_checkfile';
  	if (is_file($filePath.DS.$checkfileName) ) 
  		{
       $okToContinue = false;
      }
    $db	= JFactory::getDBO();
    $db->setQuery("#__prayerrequests");
    $table_nm =  $db->_sql;
    $tableArray = $db->getTableList();
    if(in_array($table_nm,$tableArray) && $okToContinue){
      $pr_upgrade = true;
    } else {
      $pr_upgrade = false;
    }
    if ($pr_upgrade && $okToContinue) {
    ?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
      var form = document.adminForm;
      if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
      }
        submitform( pressbutton );
      }
      </script>
      <table class="adminform" cellpadding="20" cellspacing="25">
      	<tr>
     		<td valign="top">
		    <table class="admintable" width="100%" border="1">
        <tr><td><br /><center>
     		<form action="index.php?option=com_prayercenter" method="post" name="adminUpgradefromprForm">
        <font color="red"><b>The Prayer Requests component installation has been detected!</b></font><br /><br />
        <font color="blue"><blockquote>(Use this option to copy all prayer request items from that component into PrayerCenter.)</blockquote><br /> 
        </font>
        <ul><li>    <label for="upgradefrompr"><b>Copy Requests From Prayer Requests Component Into PrayerCenter</b></label><br /><br /><input type="submit" class="radio" name="upgradefrompr" class=radio value="<?php echo 'Submit';?>" /></li></ul><br />
        <font color="green"><blockquote><center><b>**This option may continue to be shown here until the Prayer Requests**<br />**component and db table have been removed.**</b></font></center></blockquote>
      </center></td></tr></table>
      </td></tr></table>
  		<input type="hidden" name="option" value="com_prayercenter" />
  		<input type="hidden" name="controller" value="prayercenter" />
   		<input type="hidden" name="task" value="doPRUpgrade" />
  		</form>
      <?php
      } else {
    		$this->setMessage("No migrations are needed at this time.", 'message');
    		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
      }
    }
  function purgeErrorLog( $option='com_prayercenter' ){
    $user = JFactory::getUser();
    @unlink(JPATH_ROOT.'/administrator/components/com_prayercenter/logs/pcerrorlog.php');
  	jimport('joomla.error.log');
    jimport('joomla.utilities.date');
    $dateset = new JDate(gmdate('Y-m-d H:i:s'));
		$options['format'] = "{DATE} {TIME} {MESSAGE}";
		$log = JLog::getInstance('pcerrorlog.php', $options, JPATH_ROOT.'/administrator/components/com_prayercenter/logs');
		$pcerrorlog = array();
		$pcerrorlog['message'] = JText::_('Log file purged by ').$user->get('name');
		$pcerrorlog['time'] = $dateset->toFormat("%H:%M:%S(GMT)");
		$log->addEntry($pcerrorlog);
		$this->setMessage("Log file purged.", 'message');
		$this->setRedirect(JRoute::_("index.php?option=".$option."&task=utilities", false));
  }
	function pchelp(){
    include_once('components/com_prayercenter/helpers/pc_version.php');
    $pcversion = new PCVersion();
		$helpurl	= 'http://www.mlwebtechnologies.com';
		$fullhelpurl = $helpurl . '/index.php?option=com_content&amp;tmpl=component&amp;task=findkey&amp;pop=1&amp;keyref=';
		$helpsearch = JRequest::getString('pchelpsearch');
		$helpsearch = str_replace(array('=', '<', '"'), '', $helpsearch);
		$page		= JRequest::getCmd('page', 'pcnews');
		$toc		= $this->getHelpPCToc( $helpsearch, $helpurl );
		?>
		<form action="index.php?option=com_prayercenter&amp;tmpl=component" method="post" name="pchelpForm">
		<fieldset>
			<div style="float: right">
				<button type="button" onclick="window.parent.SqueezeBox.close();">
					<?php echo JText::_( 'Close' );?></button>&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
      <div class="configuration" >
				<?php echo JText::_('PrayerCenter Help') ?>
			</div>
		</fieldset>
		<table class="adminform" border="1">
		<tr>
			<td colspan="2">
				<table width="100%">
					<tr>
						<td>
							<strong><?php echo JText::_( 'Search' ); ?>:</strong>
							<input type="text" name="pchelpsearch" value="" class="inputbox" />
							<input type="submit" value="<?php echo JText::_( 'Go' ); ?>" class="button" />
							<input type="button" value="<?php echo JText::_( 'Reset' ); ?>" class="button" onclick="f=document.pchelpForm;f.pchelpsearch.value='';f.submit()" />
						</td>
						<td class="helpMenu">
							<?php
  					if ($helpurl) {
							?>
							<?php echo JHTML::_('link', $helpurl.'/index.php?option=com_kunena', JText::_( 'Support Forum' ), array('target' => '_blank')) ?>
							<?php } ?>
							&nbsp;|&nbsp;
							<?php echo JHTML::_('link', 'http://www.gnu.org/licenses/gpl-2.0.html', JText::_( 'License' ), array('target' => 'helpFrame')) ?>
							&nbsp;|&nbsp;
							<?php echo JHTML::_('link', $fullhelpurl.'change-logs', JText::_( 'Changelog' ), array('target' => 'helpFrame')) ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
		<div id="treecellhelp">
			<fieldset title="<?php echo JText::_( 'Alphabetical Index' ); ?>">
				<legend>
					<?php echo JText::_( 'Alphabetical Index' ); ?>
				</legend>
				<div class="helpIndex">
					<ul class="subext">
						<?php
  					if(is_array($toc)){
            	foreach ($toc as $tocitem) {
  							if ($helpurl) {
  								echo '<li>';
  								echo JHTML::_('link', $fullhelpurl.$tocitem['keyref'], $tocitem['title'], array('target' => 'helpFrame'));
  								echo '</li>';
  	            } 
    					}
            } else {
              echo $toc;
            }
						?>
					</ul>
				</div>
			</fieldset>
		</div>
		<div id="datacellhelp">
			<fieldset title="<?php echo JText::_( 'View' ); ?>">
				<legend>
					<?php echo JText::_( 'View' ); ?>
				</legend>
				<?php
				if ($helpurl) {
					?>
					<iframe name="helpFrame" src="<?php echo $fullhelpurl.$page;?>" class="helpFrame" frameborder="0" width="100% height="100%"></iframe>
					<?php
				}
				?>
			</fieldset>
		</div>
		<input type="hidden" name="task" value="pchelp" />
		</form>
		<?php
	}
  function getHelpPCTOC( $helpsearch, $helpurl )
  {
  	$fullhelpurl = 'http://www.mlwebtechnologies.com/index.php?option=com_content&amp;tmpl=component&amp;task=findkey&amp;pop=1&amp;keyref=';
    $docliststr = file_get_contents($fullhelpurl.'pcdocslist');
    preg_match_all( '#<p>(.*?)</p>#', $docliststr, $doclist );
  	$toc = array();
    foreach ($doclist[1] as $key => $line) {
      $line = strip_tags($line);
  		$buffer = file_get_contents( $fullhelpurl.$line );
  		if (preg_match( '#<title>(.*?)</title>#', $buffer, $m )) {
  			$title = trim( $m[1] );
  			if ($title) {
  				if ($helpsearch) {
  					if (JString::strpos( strip_tags( $buffer ), $helpsearch ) !== false) {
  						$toc[$key] = $title;
  						$toc[$key]['keyref'] = $line;
  					}
  				} else {
  					$toc[$key]['title'] = $title;
 						$toc[$key]['keyref'] = $line;
  				}
  			}
  		}
  	}
  	if(count($toc) < 1) {
  	  return 'Keyword not found';
  	} else {
      asort( $toc );
  	  return $toc;
  	}
  }
	public function checkin()
	{
		JSession::checkToken() or jexit(JText::_('JInvalid_Token'));
    $lang = Jfactory::getLanguage();
    $lang->load( 'com_checkin', JPATH_ADMINISTRATOR); 
		$ids = $this->input->get('cid', array(), 'array');
    $ids = $ids[0];
		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));
		} else {
			$model = $this->getModel('ManageReq','PrayerCenterModel');
			$this->setMessage(JText::plural('COM_CHECKIN_N_ITEMS_CHECKED_IN_1', $model->checkin($ids)));
		}
		$this->setRedirect('index.php?option=com_prayercenter&task=manage_req');
	}
}
?>