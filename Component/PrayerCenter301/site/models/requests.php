<?php
/* *************************************************************************************
Title          PrayerCenter Component for Joomla
Author         Mike Leeper
Enhancements   Douglas Machado 
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
class PrayerCenterModelRequests extends JModelLegacy
{
	var $_data = null;
	var $_codata = null;
	var $_total = null;
	var $_hits = null;
	function __construct()
	{
		parent::__construct();
	}
	function getData($sort,$searchword,$searchrequester,$searchrequesterid)
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery($sort,$searchword,$searchrequester,$searchrequesterid);
			$this->_data = $this->_getList($query);
		}
		return $this->_data;
	}
	function getNewData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQueryNewReq();
			$this->_data = $this->_getList($query);
		}
		return $this->_data;
	}
	function getTotalData()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQueryTotalReq();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}
	function getTotal($sort,$searchword,$searchrequester,$searchrequesterid)
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery($sort,$searchword,$searchrequester,$searchrequesterid);
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}
	function getNewTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQueryNewReq();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}
	function _buildQuery($sort,$searchword,$searchrequester,$searchrequesterid)
	{
    if($searchword && $searchword != JText::_('PCSEARCH...')){
      $where = " AND (request REGEXP '".$searchword."' OR requester REGEXP '".$searchword."')";
    } elseif($searchrequester){
      $where = " AND requester REGEXP '".$searchrequester."'";
      if($searchrequesterid) $where .= " AND requesterid REGEXP '".$searchrequesterid."'";
    } else {
      $where = "";
    }
    $query = "SELECT *,request AS text FROM #__prayercenter WHERE archivestate='0' AND publishstate='1' AND displaystate='1'".$where;
    if ($sort=="99"){
      $query .= " ORDER BY DATE_FORMAT(CONCAT_WS(' ',date,time),'%Y-%m-%d %T') DESC";
    } else {
      $query .= " AND topic='".$sort."' ORDER BY DATE_FORMAT(CONCAT_WS(' ',date,time),'%Y-%m-%d %T') DESC";
    }
		return $query;
	}
  function _buildQueryTotalReq()
  {
    $query = "SELECT id FROM #__prayercenter WHERE archivestate='0' AND publishstate='1' AND displaystate='1'";
		return $query;
  }
  function _buildQueryNewReq()
  {
  $query = "SELECT * FROM #__prayercenter WHERE archivestate='0' AND publishstate='0' ORDER BY id DESC";
	return $query;
  }
	function getEditData($eid)
	{
		if (empty($this->_data))
		{
			$query = $this->_buildEditQuery($eid);
			$this->_data = $this->_getList($query);
		}
		return $this->_data;
	}
	function _buildEditQuery($eid)
	{
    $query = "SELECT *,request AS text FROM #__prayercenter WHERE id='".(int)$eid."'";
		return $query;
	}
	function getCOData($cou)
	{
		if (empty($this->_codata))
		{
			$query = $this->_buildCOQuery($cou);
			$this->_codata = $this->_getList($query);
		}
		return $this->_codata;
	}
	function _buildCOQuery($cou)
	{
    $query = "SELECT name FROM #__users WHERE id='".(int)$cou."'";
		return $query;
	}
}
?>