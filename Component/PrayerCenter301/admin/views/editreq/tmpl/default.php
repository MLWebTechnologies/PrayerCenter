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
  $lang = Jfactory::getLanguage();
  $lang->load( 'com_prayercenter', JPATH_SITE);
  JHTML::_('behavior.tooltip');
  ?>
  <style type="text/css">
  .icon-32-print 	{ background-image: url('templates/bluestork/images/toolbar/icon-32-print.png'); }
  </style>
  <?php
	$print_link = "index.php?option=com_prayercenter&amp;task=view_req&amp;id=".$this->form->getInput('id')."&amp;tmpl=component";
	$sb = JToolBar::getInstance('toolbar');
	$sb->appendButton( 'Popup', 'print', 'Print', $print_link, 600, 380 );
  ?>
	<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task)
  {
		Joomla.submitform( task, document.getElementById('req-form') );
	}
  </script>
	<div class="span10 form-horizontal">
	<form action="index.php?option=com_prayercenter" method="post" name="adminForm" id="req-form">
		<div class="tab-content">
			<div class="tab-pane active" id="details">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'requester' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('requester'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'email' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'date' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('date'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'time' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('time'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'topic' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('topic'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('request'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('request'); ?></div>
				</div>
			</div>
    </div>
  <div class="clr"></div>            
	<input type="hidden" name="jform[id]" id="jform_id" value="<?php echo $this->form->getValue('id'); ?>" />
	<input type="hidden" name="option" value="com_prayercenter" />
 	<input type="hidden" name="edit" value="<?php echo $this->edit; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="prayercenter" />
	<?php echo JHTML::_( 'form.token' ); ?>
  </form>
	<?php
	echo '<br /><br />';
  $prayercenteradmin->PCfooter();
?></div>