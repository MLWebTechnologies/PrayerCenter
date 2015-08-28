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
global $pcConfig, $prayercenter;
$JVersion = new JVersion();
$pcParams = JComponentHelper::getParams('com_prayercenter');
$pcParamsArray = $pcParams->toArray();
foreach($pcParamsArray['params'] as $name => $value){
  $pcConfig[(string)$name] = (string)$value;
}
$document = JFactory::getDocument();
$document->addScript('components/com_prayercenter/assets/js/pc.js');
$document->addStyleSheet(JURI::base().'components/com_prayercenter/assets/css/prayercenter.css');
$lang = Jfactory::getLanguage();
$lang->load( 'com_prayercenter', JPATH_SITE); 
if(!function_exists('str_split')) {
    function str_split($string,$string_length=1) {
        if(strlen($string)>$string_length || !$string_length) {
            do {
                $c = strlen($string);
                $parts[] = substr($string,0,$string_length);
                $string = substr($string,$string_length);
            } while($string !== false);
        } else {
            $parts = array($string);
        }
        return $parts;
    }
}
?>