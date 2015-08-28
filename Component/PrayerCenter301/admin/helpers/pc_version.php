<?php
/**
* PrayerCenter Component
* 
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class PCVersion {
	/** @var string Product */
	var $PRODUCT 	= 'PrayerCenter';
	/** @var int Main Release Level */
	var $RELEASE 	= '3';
	/** @var int Sub Release Level */
	var $DEV_LEVEL 	= '0';
	/** @var string Patch Level */
	var $PATCH_LEVEL = '1';
	/** @var string Development Status */
	var $DEV_STATUS = '';
	/** @var string Copyright Text */
	var $COPYRIGHT 	= 'MLWebTechnologies &copy; 2006-';
	/** @var string Copyright Text */
	var $COPYRIGHTBY 	= 'MLWebTechnologies';
	/** @var string LINK */
	var $LINK 		= 'http://www.mlwebtechnologies.com';
	function &getInstance() {
		static $instance;
		if ($instance == null) {
			$instance = new PCVersion();
		}
		return $instance;
	}
	/**
	 * access instance properties
	 * @var    string		property name
	 * @return mixed		property content
	 */
	function get($property) {
		if(isset($this->$property)) {
			return $this->$property;
		}
		return null;
	}
	/**
	 * Returns a reference to a global PCVersion object, only creating it
	 * if it doesn't already exist.
	 *
	 * @static
	 * @access public
	 * @return object
	 */
	/**
	 * @return string URL
	 */
	function getUrl() {
		return $this->LINK;
	}
	/**
	 * @return string short Copyright
	 */
	function getShortCopyright() {
		return $this->COPYRIGHT.date('Y');
	}
	/**
	 * @return string long Copyright
	 */
	function getLongCopyright() {
		$copyright = $this->COPYRIGHT.date('Y');
		return $copyright . ' ' . $this->COPYRIGHTBY;
	}
	/**
	 * @return string Long format version
	 */
	function getLongVersion() {
		return ' v.'. $this->getShortVersion();
	}
	/**
	 * @return string Short version format
	 */
	function getShortVersion() {
		return $this->RELEASE . '.' . $this->DEV_LEVEL . '.' . $this->PATCH_LEVEL . ' ' . $this->DEV_STATUS;
	}
}
?>