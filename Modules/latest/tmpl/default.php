<?php
/*****************************************************************************************
 Title          PrayerCenter Latest Prayer Module for Joomla
 Author         Mike Leeper
 Version        3.0.0
 License        This is free software and you may redistribute it under the GPL.
                PrayerCenter Latest Prayer comes with absolutely no warranty. For details, 
                see the license at http://www.gnu.org/licenses/gpl.txt
                YOU ARE NOT REQUIRED TO KEEP COPYRIGHT NOTICES IN
                THE HTML OUTPUT OF THIS SCRIPT. YOU ARE NOT ALLOWED
                TO REMOVE COPYRIGHT NOTICES FROM THE SOURCE CODE.
******************************************************************************************/
defined( '_JEXEC' ) or die( 'Restricted access' );// no direct access
if(file_exists(JPATH_ROOT."/administrator/components/com_prayercenter/config.xml")){
  require_once( JPATH_ROOT."/components/com_prayercenter/helpers/pc_includes.php" );
  require_once( JPATH_ROOT."/components/com_prayercenter/helpers/pc_class.php" );
  global $pcConfig;
  $prayercenterlmod = new prayercenter();
  $pc_rights = $prayercenterlmod->intializePCRights();
  $itemid = $prayercenterlmod->PCgetItemid();
  $lang =& Jfactory::getLanguage();
  $lang->load( 'com_prayercenter', JPATH_SITE); 
  $pclmodhelper = new mod_pc_latestHelper();
  $count = $params->get( 'count' );
	$wordcount = $params->get('word_count');
  $link = JRoute::_('index.php?option=com_prayercenter&task=view&Itemid='.$itemid);
  $request = "";  
  $rows = $pclmodhelper->getPCLModData($count);
  if($pc_rights->get( 'pc.view' )){
    if(count($rows) > 0){
  	?>
    <style>
      div.moduletable ul.pcl {
      	margin-left: 0px;
        list-style-type: none;
        padding-left:0px;
      }
    </style>
  	<div cellpadding="0" cellspacing="0" class="moduletable<?php echo $params->get('moduleclass_sfx'); ?>">
  			<br /><ul class="pcl<?php echo $params->get( 'moduleclass_sfx'); ?>">
          <?php
          for( $i=0; $i<count($rows); $i++ ) {
  					if ($wordcount)
  					{
  						$texts = explode(' ', $rows[$i]->request);
  						$count = count($texts);
  						if ($count > $wordcount)
  						{
  							for ($j = 0; $j < $wordcount; $j++) {
  								$request .= ' '.$texts[$j];
  							}
  							$request .= '...';
  						} else {
                $request = $rows[$i]->request;
              }
  					} else {
              $request = $rows[$i]->request;
            }
            $viewlink = JRoute::_('index.php?option=com_prayercenter&task=view_request&id='.$rows[$i]->id.'&Itemid='.$itemid);
          	?>
          	<li style="padding-bottom:10px">
            <?php if($i > 0 && $i != count($rows)) echo '<br />';?>
              <?php echo '<b>'.htmlentities(JText::_('PCPOSTEDBY')).'</b><br />'.wordwrap($rows[$i]->requester,22,"<br />\n",true).'<br />';?>
              <?php echo '('.date("M j,Y",strtotime($rows[$i]->date)).')<br />'; ?><br />
              <?php echo '<i>"'.$prayercenterlmod->PCkeephtml($request).'"</i><small>&nbsp;&nbsp;&nbsp;<a href="'.$viewlink.'" /><i>'.htmlentities(JText::_('PCREADMORE')).'</i></a></small>'; ?>
          	</li>
          	<?php
            echo '<hr style="padding:0px;margin:2px;">';
            $request = "";
          }
        ?>
        </ul><br />
          <small><a class="readon" style="margin-top: 4px;margin-right: 2px;" href="<?php echo $link; ?>"><?php echo JText::_('PCVIEWLIST'); ?></a></small>
      	</div>
    	<?php
      } else {
      	echo '<div><center><b>';
      	echo wordwrap(JText::_('PCNOREQUEST'),20,"<br />\n",true);
      	echo '</b></center></div>';
      }
    } else {
      echo '<div>&nbsp;</div>';
    }
} else { 
  if(!defined('PCCOMNOTINSTALL')) define('PCCOMNOTINSTALL','PrayerCenter Component Not Installed');
  echo '<div><center><font color="red"><b>'.htmlentities(JText::_('PCCOMNOTINSTALL')).'</b></font></center></div>';
}
?>