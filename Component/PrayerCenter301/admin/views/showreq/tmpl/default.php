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
    $newtopicarray = $prayercenteradmin->PCgetTopics();
    $etopic = $newtopicarray[$this->erow[0]->topic+1]['text'];
  ?>
  <div class="col width-70">
  	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'USRLPRAYERREQUESTER' ); ?>
				</label>
			</td>
      <td><?php echo $this->erow[0]->requester;?></td>
      </tr>
    <?php if(empty($this->erow[0]->email)) $this->erow[0]->email = 'None';?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'USRLPRAYERREQUESTEREMAIL' ); ?>
				</label>
			</td>
			<td><?php echo $this->erow[0]->email;?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'USRLDATE' ); ?>
				</label>
			</td>
			<td><?php echo $this->erow[0]->date;?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'USRLTIME' ); ?>
				</label>
			</td>
			<td><?php echo date("h:i:s A",strtotime($this->erow[0]->time));?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'PCREQTOPIC' ); ?>
				</label>
			</td>
			<td><?php echo $etopic;?></td>
		</tr>
    <?php if(empty($this->erow[0]->title)) $this->erow[0]->title = 'None';?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'PCREQTITLE' ); ?>
				</label>
			</td>
			<td><?php echo JText::_($this->erow[0]->title);?></td>
		</tr>
	</table>
	</fieldset>
  </div>
  <div style="float:left;width:100%;">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'USRLPRAYERREQUEST' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100%">
      <div style="padding:4px;min-height:60px;"><?php echo rtrim(stripslashes(JText::_($this->erow[0]->request)));?></div>
      </td>
		</tr>
		</table>
	</fieldset>
  </div>
  <div class="clr"></div>
	<div style="float: left"><br />&nbsp;&nbsp;&nbsp;
		<button type="button" onclick="javascript:void window.print();return false;">
			<?php echo JText::_( 'USRLPRINT' );?></button>&nbsp;&nbsp;<?php
  if( (real)$this->JVersion->RELEASE == 1.5 ) {
		?><button type="button" onclick="window.parent.document.getElementById('sbox-window').close();"><?php
  } elseif( (real)$this->JVersion->RELEASE >= 1.6 ){
		?><button type="button" onclick="window.parent.SqueezeBox.close();">
			<?php } echo JText::_( 'USRLCANCEL' );?></button>
	</div>