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
defined( '_JEXEC' ) or die( 'Restricted Access' );
/**
* Provides access to the #__prayercenter table
*/
class TablePrayercenter extends JTable {
	var $id=null;
	var $checked_out=null;
	var $checked_out_time=null;
	function __construct( &$db ) {
		parent::__construct( '#__prayercenter', 'id', $db );
	}
	function check() {
		if( empty( $this->created ) ) {
			$this->created = date('Y-m-d H:i:s');
		}
		return true;
	}
	function isCheckedOut( $uid=0 )
	{
			$prayercenter = & $this->getTable();
			if ($uid) {
				return ($prayercenter->checked_out && $prayercenter->checked_out != $uid);
			} else {
				return $prayercenter->checked_out;
			}
	}
	function checkin()
	{
		if ($this->_id)
		{
			$prayercenter = & $this->getTable();
			if(! $prayercenter->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
	function checkout($uid = null)
	{
		if ($this->_id)
		{
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			$prayercenter = & $this->getTable();
			if(!$prayercenter->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
}
?>