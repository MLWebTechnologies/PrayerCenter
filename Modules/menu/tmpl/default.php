<?php
/*****************************************************************************************
 Title          Mod_pc_menu PrayerCenter Menu module for Joomla
 Author         Douglas Machado & Mike Leeper
 Version        3.0.0
 URL            http://www.mlwebtechnologies.com
 Email          web@mlwebtechnologies.com
 License        This is free software and you may redistribute it under the GPL.
                Mod_pc_menu comes with absolutely no warranty. For details, 
                see the license at http://www.gnu.org/licenses/gpl.txt
                YOU ARE NOT REQUIRED TO KEEP COPYRIGHT NOTICES IN
                THE HTML OUTPUT OF THIS SCRIPT. YOU ARE NOT ALLOWED
                TO REMOVE COPYRIGHT NOTICES FROM THE SOURCE CODE.
                Requires the PrayerCenter component v2.5.2 or higher
*****************************************************************************************/
defined( '_JEXEC' ) or die( 'Restricted access' );// no direct access
if(file_exists(JPATH_ROOT."/administrator/components/com_prayercenter/config.xml")){
  require_once( JPATH_ROOT."/components/com_prayercenter/helpers/pc_includes.php" );
  require_once( JPATH_ROOT."/components/com_prayercenter/helpers/pc_class.php" );
  $prayercentermod = new prayercenter();
  $pc_rights = $prayercentermod->intializePCRights();
  $prayercentermod->buildPCMenu(true,$params);
} else { 
  if(!defined('PCCOMNOTINSTALL')) define('PCCOMNOTINSTALL','PrayerCenter Component Not Installed');
  echo '<div><center><font color="red"><b>'.htmlentities(JText::_('PCCOMNOTINSTALL')).'</b></font></center></div>';
}
?>