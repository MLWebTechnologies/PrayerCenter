<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
global $pcConfig,$prayercenteradmin;
$lang = JFactory::getLanguage();
$lang->load( 'com_prayercenter', JPATH_SITE); 
require( JPATH_ADMINISTRATOR.'/components/com_prayercenter/helpers/admin_pc_class.php' );
$prayercenteradmin = new prayercenteradmin();
$pcParams = JComponentHelper::getParams('com_prayercenter');
$pcParamsArray = $pcParams->toArray();
foreach($pcParamsArray['params'] as $name => $value){
  $pcConfig[(string)$name] = (string)$value;
}
?>