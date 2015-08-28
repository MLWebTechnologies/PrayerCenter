<?php
/* *************************************************************************************
Title          PrayerCenter Component for Joomla
Author         Mike Leeper
License        This program is free software: you can redistribute it and/or modify
               it under the terms of the GNU General Public License as published by
               the Free Software Foundation, either version 3 of the License, or
               (at your option) any later version.
               This program is distributed in the hope that it will be useful,
               but WITHOUT ANY WARRANTY; without even the implied warranty of
               MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
               GNU General Public License for more details.
               You should have received a copy of the GNU General Public License
               along with this program.  If not, see <http://www.gnu.org/licenses/>.
Copyright      2006-2014 - Mike Leeper (MLWebTechnologies) 
****************************************************************************************
No direct access*/
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
//require_once JPATH_COMPONENT.'/helpers/route.php';
require_once( JPATH_COMPONENT.'/controller.php' );
require_once( JPATH_COMPONENT.'/helpers/pc_includes.php' );
require_once( JPATH_COMPONENT."/helpers/pc_class.php" );
global $pcConfig;
$prayercenter = new prayercenter();
$pc_rights = $prayercenter->intializePCRights();
if (!empty($pcConfig['config_pms_plugin']) && file_exists(JPATH_ROOT.'/administrator/components/com_prayercenter/plugins/pms/plg.pms.'.$pcConfig['config_pms_plugin'].'.php')) {
  require_once(JPATH_ROOT.'/administrator/components/com_prayercenter/helpers/pc_plugin_class.php');
  $PCPluginHelper = new PCPluginHelper();
  $pluginfile = 'plg.pms.'.$pcConfig['config_pms_plugin'].'.php';
  $PCPluginHelper->importPlugin('pms',$pluginfile);
}
$user = JFactory::getUser();
if($pcConfig['config_allow_purge']){
 if($user->get('usertype') == 'Administrator' || $user->get('usertype') == 'Super Administrator')
  {
   $prayercenter->PCautoPurge($pcConfig['config_request_retention'], $pcConfig['config_archive_retention']);
  }
 }
if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}
$classname	= 'PrayerCenterController'.ucfirst($controller);
$controller = new $classname( );
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
?>