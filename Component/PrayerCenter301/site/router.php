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
defined('_JEXEC') or die('Restricted access'); 
/**
* Method to build the Route
*/
function PrayerCenterBuildRoute(&$query)
{
  $segments = array();
	if (isset($query['id']) && strpos($query['id'], ':')) {
		list($query['id'], $query['alias']) = explode(':', $query['id'], 2);
	}
  if (isset($query['task']))
  {
    $segments[] = $query['task'];
    unset($query['task']);
  }
	if (isset($query['id'])){ 
		if (isset($query['alias'])) {
			$query['id'] .= ':'.$query['alias'];
		}
		if(isset($query['view'])) {$segments[]	= $query['view'];}
    $segments[] = $query['id'].'-request';
		unset($query['view']);
		unset($query['id']);
//		unset($query['alias']);
  }
  if(isset($query['pop'])){
    $segments[] = $query['pop'];
    unset($query['pop']);
  }
  if(isset($query['listtype'])){
//    $segments[] = $query['listtype'];
//    unset($query['listtype']);
  }
  if(isset($query['title'])){
//    $segments[] = $query['title'];
//    unset($query['title']);
  }
  if(isset($query['format'])){
//    $segments[] = $query['format'];
//    unset($query['format']);
  }
  if(isset($query['Itemid'])&&isset($query['alias'])){
    unset($query['Itemid']);
  }
  return $segments;
}
/**
* Method to parse the Route
*/
function PrayerCenterParseRoute($segments)
{
	$vars = array();
	$count = count($segments);
	if($count)
	{
		$count--;
		$segment = array_shift($segments);
		$vars['task'] = $segment;
	}
	if($count)
	{
		$count--;
		$segment = array_shift($segments);
//		if (is_numeric($segment)) {
      $seg = explode(":",$segment);
			$vars['id'] = $seg[0];
//		}
	}
	if($count)
	{
		$count--;
		$segment = array_shift($segments) ;
		if(is_numeric($segment)) {
			$vars['pop'] = $segment;
		}
	}
	if($count)
	{
		$count--;
		$segment = array_shift($segments) ;
		if(is_numeric($segment)) {
			$vars['listtype'] = $segment;
		}
	}
	if($count)
	{
		$count--;
		$segment = array_shift($segments) ;
		if(is_numeric($segment)) {
			$vars['title'] = $segment;
		}
	}
	if($count)
	{
		$count--;
		$segment = array_shift($segments) ;
		if(is_numeric($segment)) {
			$vars['format'] = $segment;
		}
	}
//  $vars['Itemid'] = null;
	return $vars;
}
?>