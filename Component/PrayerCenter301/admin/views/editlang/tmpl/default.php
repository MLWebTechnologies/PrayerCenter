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
	JRequest::setVar( 'hidemainmenu', 1 );
  JHTML::_('behavior.tooltip');
  $edit = $this->edit;
  if(empty($edit)) $edit = 0;
	if(!$this->file){
    $lffile = 'en-GB.com_prayercenter.ini';
    $lffolder = 'en-GB';
   } else {
    $d = "#[\\\/]#";
    $lf = preg_split($d,$this->file,-1,PREG_SPLIT_NO_EMPTY);
    $lffile = $lf[count($lf)-1];
    $lffolder = $lf[count($lf)-2];
  }
  ?>
	<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task)
  {
      var edit = <?php echo $edit;?>;
  		var form = document.adminForm;
  		if (task == 'prayercenter.canceleditlang') {
    		Joomla.submitform( task, document.getElementById('lang-form') );
  			return;
  		}
      if (edit > 0) {
    		Joomla.submitform( task, document.getElementById('lang-form') );
        return;
        }
  		// do field validation
      var q = new RegExp('[a-z]+\-[A-Z]');
  		if (form.config_langfolder.value == "" || q.test(form.config_langfolder.value) != true || form.config_langfolder.value == 'en-GB'){
  			alert( "Please enter a valid language code. \n(Example: en-GB)" );
  		} else {
    		Joomla.submitform( task, document.getElementById('lang-form') );
  		}
  	}
  </script>
	<div class="span10 form-horizontal">
	<form action="index.php?option=com_prayercenter" method="post" name="adminForm" id="lang-form">
		<div class="tab-content">
			<div class="tab-pane active" id="details">
				<div class="control-group">
					<div class="control-label">
          <?php 
          if(!$this->file){
            echo JText::_( '<b>File Name:</b><br />(Example<br />en-GB.com_prayercenter.ini)<br />(See Help for details)' );
          } else {
            echo JText::_( '<b>File Name:</b>' );
          }
          ?>
          </div>
					<div class="controls">
          <?php 
          if(!$this->file){
     			  ?><input class="text_area" type="text" name="config_langfile" id="config_langfile" size="32" maxlength="250" value="" /><?php
          } else {
     			  ?><input class="text_area" type="text" name="config_langfile" id="config_langfile" size="32" maxlength="250" value="<?php echo $lffile;?>" readonly="readonly" /><?php
          }
          ?>
          </div>
				</div>
				<div class="control-group">
					<div class="control-label">
          <?php 
          if(!$this->file){
            echo JText::_( '<b>Language Code:</b><br />(Example en-GB)<br />(See Help for details)' );
          } else {
            echo JText::_( '<b>Language Code:</b>' );
          }
          ?>
          </div>
					<div class="controls">
          <?php
          if(!$this->file){
          ?>
      			<input class="text_area" type="text" name="config_langfolder" id="config_langfolder" size="6" maxlength="250" value="" />
          <?php
          } else {
          ?>
      			<input class="text_area" type="text" name="config_langfolder" id="config_langfolder" size="6" maxlength="250" value="<?php echo $lffolder;?>" readonly="readonly" />
    			<?php
          }
          ?>
          </div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('<b>File Content</b>'); ?></div>
          <?php
          $filename = JPATH_ROOT.'/language/'.$lffolder.'/'.$lffile;
      		$initstring = file_get_contents($filename);
          ?>
					<div class="controls"><?php echo $this->editor->display( 'config_lang',  $initstring , '90%', '250', '70', '15', false ); ?></div>
				</div>
			</div>
    </div>
  <div class="clr"></div>
  <input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
	 </form>
	<?php
	echo '<br /><br />';
  $prayercenteradmin->PCfooter();
?></div>