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
	<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task)
  {
		Joomla.submitform( task, document.getElementById('pcdev-form') );
	}
  </script>
	<div class="span10 form-horizontal">
	<form action="index.php?option=com_prayercenter" method="post" name="adminForm" id="pcdev-form">
		<div class="tab-content">
			<div class="tab-pane active" id="details">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'name' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'published' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('published'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'catid' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'feed' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('feed'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel( 'target' ); ?></div>
					<div class="controls"><?php echo $this->form->getInput('target'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('ordering'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
				</div>
			</div>
    </div>
<div class="clr"></div>
	<input type="hidden" name="option" value="com_prayercenter" />
	<input type="hidden" name="controller" value="prayercenter" />
	<input type="hidden" name="cid[]" value="<?php echo $this->form->getValue('id'); ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form><br /><br /><br /><br />
	<?php $prayercenteradmin->PCFooter(); ?>