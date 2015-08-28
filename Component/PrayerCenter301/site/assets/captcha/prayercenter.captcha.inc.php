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
defined( '_JEXEC' );
include("prayercenter.captcha.php");
$task=$_GET['action'];
switch($task){
  case 'pccomp':
  create_image('pccomp');
  break;
  case 'pcmsub':
  create_image('pcmsub');
  break;
  case 'pcmsr':
  create_image('pcmsr');
  break;
  default:
  break;
}
?>