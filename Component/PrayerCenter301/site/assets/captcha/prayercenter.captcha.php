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
function create_image($source) 
{ 
  global $mainframe;
     //Check for required GD functions
    if (!function_exists('imagecreate') || !function_exists("imagecreatefrompng")) {
        return false;
       }
    //Generate a random string using md5 
    $md5_hash = md5(rand(0,999)); 
    //Trim down the 32 character long md5 string 
    $numchars = 5;
    $security_code = substr($md5_hash, 15, $numchars); 
    //Set the session to store the security code
    define('_JEXEC',1);
    define('JPATH_BASE',realpath(dirname(__FILE__).'/../../../..'));
    define('DS',DIRECTORY_SEPARATOR);
    require_once(JPATH_BASE.DS.'includes'.DS.'defines.php');
    require_once(JPATH_BASE.DS.'includes'.DS.'framework.php');
    $mainframe = JFactory::getApplication('site');
    $mainframe->initialise();
    $session = JFactory::getSession();
    if($source == 'pccomp'){
    $session->set('pc_security_code',$security_code);
    }elseif($source == 'pcmsub'){
    $session->set('pcmsub_security_code',$security_code);
    }elseif($source == 'pcmsr'){
    $session->set('pcmsr_security_code',$security_code);
    }
    //Set the image variables
    $width = 120; 
    $height = 35;  
    $fontfile = dirname(__FILE__).DS."fonts".DS."VeraMoBd.ttf";
    $spacing = (int)($width / $numchars-3);
    $numlines = 0; //Set to 0 to disable foreground lines drawn on image text
    //Create the image resource 
    $filename = dirname(__FILE__).DS."images".DS."stainglass.png";
    $image = imagecreatefrompng($filename);
    //Allocate string color 
    $white = imagecolorallocate($image, 255, 255, 255); 
    $black = imagecolorallocate($image, 0, 0, 0); 
    $grey = imagecolorallocate($image, 204, 204, 204);
    $red = imagecolorallocate($image, 255, 0, 0); 
    $green = imagecolorallocate($image, 0, 255, 0); 
    $blue = imagecolorallocate($image, 0, 0, 255); 
    $yellow = imagecolorallocate($image, 255, 255, 0); 
    $cyan = imagecolorallocate($image, 0, 255, 255); 
    $magenta = imagecolorallocate($image, 255, 0, 255); 
    $random = imagecolorallocate($image, rand(0,204), rand(0,204), rand(0,204));
    $randomarray = array($black,$red,$green,$blue,$cyan,$magenta);
    for ($i = 0; $i < strlen($security_code); $i++) {
    //Allocate string color 
    $random = imagecolorallocate($image, rand(0,204), rand(0,204), rand(0,204));
    $angle = rand(-30, 30);
    $fontsize = rand(14,18);
       // Set even / odd character
       if(($i % 2) == 1){
        $scode = strtolower($security_code[$i]);
       } else {
        $scode = strtoupper($security_code[$i]);
       }
       // Get dimensions of character in selected font and text size
       $dimensions = imageftbbox($fontsize, $angle, $fontfile, $scode, array());
       // Calculate character starting coordinates
       $xposition = $spacing / 4 + $i * $spacing;
       $charheight = $dimensions[2] - $dimensions[5];
       $yposition = $height / 2 + $charheight / 4; 
       // Write text to image
       imagefttext($image, $fontsize, $angle, $xposition, $yposition, $random, $fontfile, $scode, array());
    }
      // Draw foreground lines over image text (recommend with plain backgrounds)
    if($numlines > 0){
      for ($i = 0; $i < $numlines; $i++) {
      $randnum = array_rand($randomarray);
       //  Allocate line color
       $linecolor = imagecolorallocate($image, rand(0, 250), rand(0, 250), rand(0, 250));
      // Draw lines
       imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $randomarray[$randnum]);
      }
    }
    //Tell the browser what kind of file is coming 
    header("Content-Type: image/png"); 
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    //Output the newly created image in jpeg format 
    $imageout = imagepng($image); 
    //Free up resources
    imagedestroy($image); 
  exit(0);//return true;
} 
?>