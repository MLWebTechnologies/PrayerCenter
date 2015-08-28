<?php
/**
 * @version		$Id: pcpmslist.php 20196 2013-03-24 02:40:25Z ian $
 * @package		PrayerCenter
 * @copyright	Copyright (C) 2006 - 2014 MLWebTechnologies. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('filelist');
class JFormFieldPCPMSList extends JFormField
{
	protected $type = 'PCPMSList';
	protected static $initialised = false;
	protected function getInput()
	{
    jimport( 'joomla.application.component.helper');
    $comParams = &JComponentHelper::getParams('com_prayercenter');
    $pcParamsArray = $comParams->toArray();
    $pmsrefarray = array(
          1 => array ('val' => 'joomla', 'desc' => ''.JText::_(' - (Built-in Joomla Messaging Component. Requires Joomla 3.0 or above)').''),
          2 => array ('val' => 'privmsg', 'desc' => ''.JText::_(' - (Requires PrivMSG 3.0.0 or above)').''),
          3 => array ('val' => 'uddeim', 'desc' => ''.JText::_(' - (Requires uddeIM 2.9 or above)').'')
          );
		$SelFiles = JFolder::files( JPATH_ROOT.'/administrator/components/com_prayercenter/plugins/pms/' );
		$files 	= array(  JHTML::_( 'select.option', 0, '- Select -' ) );
		foreach ( $SelFiles as $file ) {
			if ( eregi( "php", $file ) ) {
			  preg_match('/^plg\.pms\.(.*)\.php$/',$file,$match);
				if($match){
          $keyarr = $this->pc_array_search_recursive($match[1], $pmsrefarray);
          $key = $keyarr[0];
          $pmsfile = $pmsrefarray[$key]['desc'];
          $files[] = JHTML::_( 'select.option', $match[1], ucfirst($match[1].$pmsfile) );
        }
			}
		}
		$files = JHTML::_( 'select.genericlist', $files, "jform[params][config_pms_plugin]", 'class="inputbox" size="1" '. '', 'value', 'text', $pcParamsArray['params']['config_pms_plugin'] );
		return $files;
  }
  function pc_array_search_recursive( $needle, $haystack )
  {
     $path = NULL;
     $keys = array_keys($haystack);
     while (!$path && (list($toss,$k)=each($keys))) {
        $v = $haystack[$k];
        if (is_scalar($v)) {
           if (strtolower($v)===strtolower($needle)) {
              $path = array($k);
           }
        } elseif (is_array($v)) {
           if ($path=$this->pc_array_search_recursive( $needle, $v )) {
              array_unshift($path,$k);
           }
        }
     }
     return $path;
  }
}