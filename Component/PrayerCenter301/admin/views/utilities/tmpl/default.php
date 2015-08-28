<?php
/**
* PrayerCenter Component for Joomla
* By Mike Leeper
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
  global $db, $prayercenteradmin;
  jimport('joomla.filesystem.file');
  $livesite = JURI::base();
  $db	= JFactory::getDBO();
  $template	= JFactory::getApplication()->getTemplate();
  $imagedir = 'templates/'.$template.'/images/admin/';
  ?>
	<div id="j-main-container" class="span12">
		<div class="well well-small" style="font-size:small;"><div class="module-title nav-header"><?php echo JText::_('Database Utilities');?></div><div class="row-striped">
  	<table class="table table-striped">
          <tr><td>  
            <ul style="list-style-type:none;margin-left:0px;padding-left:0px;border-top:1px solid #eee;margin-top:4px;"><li style="padding:2px 0px;"><?php
           		$optlink = $livesite."index.php?option=com_prayercenter&task=optimizePCTables";
           		$optimage = '<i class="icon-wrench"></i>';
            	$optattribs['rel'] = 'nofollow';
              $optattribs['onclick'] = "javascript:if(confirm('Do you wish to perform an optimization of the PrayerCenter DB tables?')){return true;}else{return false;}";
         		echo '&nbsp;&nbsp;'.JHTML::_('link', JRoute::_($optlink), $optimage.'&nbsp;'.JText::_('Optimize Tables'),$optattribs).'&nbsp;&nbsp;(Used periodically to defragment DB tables)<br /></li><li style="padding:2px 0px;">';
           		$cklink = $livesite."index.php?option=com_prayercenter&task=checkPCTables";
           		$ckimage = '<i class="icon-wrench"></i>';
            	$ckattribs['rel']     = 'nofollow';
              $ckattribs['onclick'] = "javascript:if(confirm('Do you wish to perform a check of the PrayerCenter DB tables?')){return true;}else{return false;}";
         		echo '&nbsp;&nbsp;'.JHTML::_('link', JRoute::_($cklink), $ckimage.'&nbsp;'.JText::_('Launch Health Check'),$ckattribs).'&nbsp;&nbsp;(Checks DB tables for errors)<br /></li><li style="padding:2px 0px;">';
           		$replink = $livesite."index.php?option=com_prayercenter&task=repairPCTables";
           		$repimage = '<i class="icon-wrench"></i>';
            	$repattribs['rel']     = 'nofollow';
              $repattribs['onclick'] = "javascript:if(confirm('Do you wish to perform a repair of the PrayerCenter DB tables? It is recommended that you backup the PrayerCenter tables prior to running this utility.')){return true;}else{return false;}";
         		echo '&nbsp;&nbsp;'.JHTML::_('link', JRoute::_($replink), $repimage.'&nbsp;'.JText::_('Repair Tables'),$repattribs).'&nbsp;&nbsp;(Repairs possibly corrupt DB table entries)<br /></li><li style="padding:2px 0px;">';
           		$baklink = $livesite."index.php?option=com_prayercenter&task=backupPCTables&format=raw";
           		$bakimage = '<i class="icon-wrench"></i>';
            	$bakattribs['rel']     = 'nofollow';
              $bakattribs['onclick'] = "javascript:if(confirm('Do you wish to perform a backup of the PrayerCenter DB tables?')){return true;}else{return false;}";
         		echo '&nbsp;&nbsp;'.JHTML::_('link', JRoute::_($baklink), $bakimage.'&nbsp;'.JText::_('Launch Backup'),$bakattribs).'&nbsp;&nbsp;(Backup DB table entries to text file)<br /></li><li style="padding:2px 0px;">';
            ?>
            <script type="text/javascript">
            function toggleRestore() {
              var restoreId = document.getElementById("restore");
              restoreId.style.display == "block" ? restoreId.style.display = "none" : restoreId.style.display = "block"; 
            }
            </script>
            <?php
         		$reslink = $livesite."index.php?option=com_prayercenter&task=restorePCTables";
         		$resimage = '<i class="icon-wrench"></i>';
          	$resattribs['rel']     = 'nofollow';
            $resattribs['onclick'] = "javascript:if(document.getElementById('uploadedbkfile').value == ''){alert('Select restore file');document.getElementById('uploadedbkfile').focus();return false;}else{if(confirm('Do you wish to perform a restore of the PrayerCenter DB tables?  \\n\\nWarning: Existing data may be overwritten.')){return true;}else{return false;}}";
         		echo '&nbsp;&nbsp;'.JHTML::_('link', 'javascript:void(0);', $resimage.'&nbsp;'.JText::_('Launch Restore'),"onclick=\"javascript:toggleRestore();if(document.getElementById('restore').style.display=='block'){alert('Select restore file');document.getElementById('uploadedbkfile').focus();}\"").'&nbsp;&nbsp;(Restore DB table entries from backup)<br />';
            ?></li></ul><div id="restore" style="display:none;padding-left:15px;padding-top:2px;">
            <form action="index.php?" enctype="multipart/form-data" method="POST" name="restorePCTables">
        		<input type="hidden" name="option" value="com_prayercenter" />
            <input type="hidden" name="task" value="restorePCTables" />
            <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
            <input type="file" name="uploadedbkfile" id="uploadedbkfile" size="40" /><br /> 
            <input type="submit" class="radio" value="Restore" onclick="<?php echo $resattribs['onclick'];?>" /> 
            </div></form></td>
         </tr>
       </table>
     </div></div>
		<div class="well well-small" style="font-size:small;"><div class="module-title nav-header"><?php echo JText::_('Logging Utilities');?></div><div class="row-striped">
  	<table class="table table-striped" >
	    <form name="adminForm">
			<tr>
				<td>
        <?php
    		$logstring = JFile::read( JPATH_COMPONENT.'/logs/pcerrorlog.php' );
  			?>
  			<textarea style="width:100%;" name="pclogfile" id="log_content" cols="100" rows="20" readonly><?php echo $logstring;?></textarea>
				</td>
		 </tr>
    <tr><td>
    <script language="javascript">
    function printlog()
    { 
      var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,"; 
          disp_setting += "scrollbars=yes,width=650, height=300, left=100, top=25"; 
      var content_vlue = document.getElementById("log_content").value.replace(/(\r\n|\n\r|\r|\n)/g,"<br>");; 
      var docprint=window.open("","",disp_setting); 
       docprint.document.open(); 
       docprint.document.write('<html><head><title>PrayerCenter Email Distribution Error Log</title>'); 
       docprint.document.write('</head><body onLoad="self.print()"><center><table border=1><tr><td>');          
       docprint.document.write(content_vlue);          
       docprint.document.write('</td></tr></table><br /><a href="javascript:self.close();">Close Window</a>');          
       docprint.document.write('</center></body></html>'); 
       docprint.document.close(); 
       docprint.focus(); 
    }
    </script>
    <?php
 		$purgeloglink = "index.php?option=com_prayercenter&amp;task=purgeErrorLog";
 		$image = '<i class="icon-trash"></i>';
  	$attribs['title']	= htmlentities(JText::_( 'Purge Log' ),ENT_COMPAT,'UTF-8');
  	$attribs['rel']     = 'nofollow';
 		echo '&nbsp;&nbsp;'.JHTML::_('link', JRoute::_($purgeloglink), $image.'&nbsp;<small>'.htmlentities(JText::_('Purge Log'),ENT_COMPAT,'UTF-8').'</small>',$attribs);
 		$printloglink = "javascript:printlog();";
 		$prtimage = '<i class="icon-print"></i>';
  	$attribs['title']	= htmlentities(JText::_( 'Print Log' ),ENT_COMPAT,'UTF-8');
  	$attribs['rel']     = 'nofollow';
 		echo '&nbsp;&nbsp;|&nbsp;&nbsp;'.JHTML::_('link', JRoute::_($printloglink), $prtimage.'&nbsp;<small>'.htmlentities(JText::_('Print Log'),ENT_COMPAT,'UTF-8').'</small>',$attribs);
    ?>
    </tr></form></table></div></div>
		<div class="well well-small" style="font-size:small;"><div class="module-title nav-header"><?php echo JText::_('Migration Utilities');?></div><div class="row-striped">
  	<table class="table table-striped">
		      <tr><td>
            <ul style="list-style-type:none;margin-left:0px;padding-left:0px;"><li style="padding:2px 0px;"><?php
           		$miglink = $livesite."index.php?option=com_prayercenter&task=showmigwiz";
           		$image = '<i class="icon-wrench"></i>';//config
            	$mattribs['rel']     = 'nofollow';
              $mattribs['onclick'] = "javascript:if(confirm('Do you wish to start the PrayerCenter DB Table Migration Wizard?')){return true;}else{return false;}";
         		echo '&nbsp;&nbsp;'.JHTML::_('link', JRoute::_($miglink), $image.'&nbsp;'.JText::_('Launch Migration Wizard'),$mattribs);
            ?>&nbsp;<?php echo JText::_('  ( Migrate Data From Prayer Request Component Into PrayerCenter )');?>
            </li></ul><br /><br /></td>
         </tr>
   </table>
   </div></div>
  <?php
  echo '<div class="clr"></div><br /><br /><br /><br /><div>';
  $prayercenteradmin->PCfooter();
?></div>