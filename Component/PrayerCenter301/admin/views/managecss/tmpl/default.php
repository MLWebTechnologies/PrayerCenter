<?php
/**
* PrayerCenter Component for Joomla
* By Mike Leeper
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
  global $prayercenteradmin;
  include_once('components/com_prayercenter/helpers/pc_version.php');
  $pcversion = new PCVersion();
  $db	= JFactory::getDBO();
	$version = new JVersion();
  $supportinfo = "";
  $supportinfo .= 'System Information%0D%0A%0D%0A';
  $supportinfo .= "PrayerCenter Version:%20".$pcversion->getLongVersion()."%0D%0A%0D%0A";
  $supportinfo .= 'Database Version:%20'.$db->getVersion().'%0D%0A';
  $supportinfo .= 'PHP Version:%20'.phpversion().'%0D%0A';
  $supportinfo .= 'Web Server:%20'.$prayercenteradmin->pc_get_server_software().'%0D%0A';
  $supportinfo .= 'Joomla! Version:%20'.$version->getLongVersion().'%0D%0A%0D%0A';
  $supportinfo .= 'Relevant PHP Settings%0D%0A';
  $supportinfo .= 'Magic Quotes GPC:%20'.$prayercenteradmin->pc_get_php_setting('magic_quotes_gpc').'%0D%0A';
  $supportinfo .= 'Short Open Tags:%20'.$prayercenteradmin->pc_get_php_setting('short_open_tag').'%0D%0A';
  $supportinfo .= 'Disabled Functions:%20'.(($df=ini_get('disable_functions'))?$df:'none').'%0D%0A';
  $editor = JFactory::getEditor('none');
//	$prayercenteradmin->PCsideBar();
?><script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task)
  {
  		Joomla.submitform( task, document.getElementById('adminForm') );
  }
  </script>
	<div class="span6">
 	<form action="index.php?option=com_prayercenter&task=manage_css" method="post" name="adminForm" id="adminForm">
		<div class="well well-small" style="color:#08c;font-size:small;"><div class="module-title nav-header">PrayerCenter Frontend Cascading Style Sheet</div><div class="row-striped">
			<div class="row-fluid small">
				<div class="span12" style="white-space:nowrap;">
    		<?php
    		$filename = JPATH_ROOT.'/components/com_prayercenter/assets/css/prayercenter.css';
    		$initstring = file_get_contents($filename);
        echo $editor->display( 'config_css',  $initstring , '90%', '250', '70', '15', false ) ;
    		?>
        </div>
      </div>
    </div></div>
    <script language="javascript">
    function printcss()
    { 
      var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,"; 
          disp_setting += "scrollbars=yes,width=650, height=300, left=100, top=25"; 
      var content_vlue = document.getElementById("config_css").value.replace(/(\r\n|\n\r|\r|\n)/g,"<br>"); 
      var docprint=window.open("","",disp_setting); 
       docprint.document.open(); 
       docprint.document.write('<html><head><title>PrayerCenter CSS File</title>'); 
       docprint.document.write('</head><body onLoad="self.print()"><center><table border=1><tr><td>');          
       docprint.document.write(content_vlue);
       docprint.document.write('</td></tr></table><br /><a href="javascript:self.close();">Close Window</a>');          
       docprint.document.write('</center></body></html>'); 
       docprint.document.close(); 
       docprint.focus(); 
    }
    </script>
 		<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_prayercenter" />
  <input type="hidden" name="controller" value="prayercenter" />
  	<?php echo JHTML::_( 'form.token' ); ?>
		</form></div><div class="span4">
    <div class="well well-small"><div class="module-title nav-header">Quick Links</div>	<div class="row-striped">
      <div class="row-fluid"><div class="span12"><a href="http://www.joomlacode.org/gf/project/prayercenter/tracker/"><i class="icon-support"></i> <span><?php echo JText::_('Support BugTracker'); ?></span></a></div></div>
      <div class="row-fluid"><div class="span12"><a href="http://www.mlwebtechnologies.com/index.php?option=com_kunena"><i class="icon-comment"></i> <span><?php echo JText::_('Support Forum'); ?></span></a></div></div>
      <div class="row-fluid"><div class="span12"><a href="mailto:web@mlwebtechnologies.com?subject=PrayerCenter%20Support%20Inquiry&body=<?php echo $supportinfo;?>"><i class="icon-mail"></i> <span><?php echo JText::_('Support Email'); ?></span></a></div></div>
      <div class="row-fluid"><div class="span12"><a href="http://www.mlwebtechnologies.com"><i class="icon-home"></i> <span><?php echo JText::_('Support Website'); ?></span></a></div></div>
      <div class="row-fluid"><div class="span12"><a href="#" onclick="javascript:printcss();"><i class="icon-print"></i> <span><?php echo JText::_('Print CSS'); ?></span></a></div></div>
      <div class="row-fluid"><div class="span12"><a href="http://www.w3schools.com/css/default.asp"><i class="icon-question-sign"></i> <span><?php echo JText::_('CSS Tutorial and Reference'); ?></span></a></div></div>
    </div>
	</div>
</div><div class="clearfix"></div><br /><br />
	<?php
  $prayercenteradmin->PCfooter();
?>