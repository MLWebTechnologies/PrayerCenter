<?php
/**
 * JComments plugin for PrayerCenter
 * @Author: Mike Leeper (MLWebTechnologies.com) 
 *
 * @version 2.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2009 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/
class jc_com_prayercenter extends JCommentsPlugin
{
	function getTitles($ids)
	{
		$db = & JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT id, title FROM #__prayercenter WHERE id IN (' . implode(',', $ids) . ')' );
		return $db->loadObjectList('id');
	}
	function getObjectTitle($id)
	{
		$db = & JCommentsFactory::getDBO();
		$db->setQuery( "SELECT title, id FROM #__prayercenter WHERE id = $id" );
		return $db->loadResult();
	}
	function getObjectLink($id)
	{
		$_Itemid = JCommentsPlugin::getItemid( 'com_prayercenter' );
		$link = JoomlaTuneRoute::_("index.php?option=com_prayercenter&amp;task=view_request&amp;id=" . $id . "&amp;Itemid=" . $_Itemid);
		return $link;
	}
	function getObjectOwner($id)
	{
		$db = & JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT requester FROM #__prayercenter WHERE id = ' . $id );
		$userid = $db->loadResult();
		return intval($userid);
	}
}
?>