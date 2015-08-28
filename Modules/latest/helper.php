<?php
/* *****************************************************************************************
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
defined( '_JEXEC' ) or die( 'Restricted access' );
class mod_pc_latestHelper{
  function getPCLModData($count){
    $db =& JFactory::getDBO();
  	$query = "SELECT a.id, a.requester, a.request, TIMESTAMP(CONCAT( a.date,' ', a.time)) AS date "
  	. "\n FROM #__prayercenter AS a"
  	. "\n WHERE a.publishstate='1' AND a.displaystate='1' AND a.archivestate='0'"
  	. "\n ORDER BY date DESC"
  	. "\n LIMIT $count"
  	;
  	$db->setQuery( $query );
  	$rows = $db->loadObjectList();
    return $rows;    
  }
}
?>