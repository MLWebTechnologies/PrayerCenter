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
Credits        FPDF class by www.fpdf.org
****************************************************************************************
No direct access*/
defined( '_JEXEC' ) or die( 'Restricted access' );
require('components/com_prayercenter/assets/fpdf/fpdf.php');
class PDF_MySQL_Table extends FPDF
{
  var $TempTopic = -1;
  var $TempHeader = 1;
  function Table($header,$query)
  {
    global $prayercenter;
    $topicarray = $prayercenter->PCgetTopics();
    $db	=& JFactory::getDBO();
    $db->setQuery($query);
    $res = $db->loadObjectList();
    if(count($res) < 1) {
      $this->Ln(2);
      $this->Cell(0,5,JText::_('No requests found'),0,0,'C');
      return;
    }
    $f = 0;
    foreach($res as $row)
    {
      $fill = ($f % 2) ? true : false;
      if($this->TempTopic != $row->topic){
        $this->Ln(3);
        $this->SetFont('helvetica','',10);
  			$topic = $topicarray[$row->topic+1]['text'];
        $this->Cell(0,5,$topic,0,0,'L');
        $this->Ln(5);
        $this->TempTopic = $row->topic;
        $this->TempHeader = 0;      
        $w = array(135,55);
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('helvetica','',9);
        for($i=0;$i<count($header);$i++){
          $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        }
        $this->Ln();
  		}
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->SetFont('');
      $request = preg_replace('/(\r|\n|\r\n){2,}/', ' ', $row->request);
      $nb1 = $this->NbLines($w[0],$request)*6;
      $this->MultiCell($w[0],6,str_replace('&nbsp;',' ',$request),'TLRB','L',$fill);
      $tempx = $this->GetX();
      $tempy = $this->GetY();
      $this->SetXY($tempx+$w[0], $tempy-$nb1);
      $this->MultiCell($w[1],$nb1,utf8_encode($row->requester),'TLRB','C',$fill);
//      $this->Ln();
      $f++;
    }
    $this->TempHeader = 1;      
  }
  function NbLines($w,$txt)
  {
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
    $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
    $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
      $c=$s[$i];
      if($c=="\n")
      {
        $i++;
        $sep=-1;
        $j=$i;
        $l=0;
        $nl++;
        continue;
      }
      if($c==' ')
      $sep=$i;
      $l+=$cw[$c];
      if($l>$wmax)
      {
        if($sep==-1)
        {
          if($i==$j)
          $i++;
        } else {
          $i=$sep+1;
          $sep=-1;
          $j=$i;
          $l=0;
          $nl++;
        }
      } else {
        $i++;
      }
    }
    return $nl;
  }
}
class PDF extends PDF_MySQL_Table{
  function Header(){
    global $pcConfig;
    jimport('joomla.utilities.date');
    jimport('joomla.filesystem.file');
    $app = &JFactory::getApplication();
    $offset = date('T');
    $dateset = new JDate('now',$offset);
    $date = $dateset->format('F d, Y',true);
    $sitename = $app->getCfg( 'sitename' );
    $img = 'components/com_prayercenter/assets/images/'.$pcConfig['config_imagefile'];
    $ext = substr($pcConfig['config_imagefile'], strrpos($pcConfig['config_imagefile'], '.') + 1);
    if($ext == 'png' && !JFile::exists('components/com_prayercenter/assets/fpdf/images/'.basename($pcConfig['config_imagefile'],'.png').'.jpg')){
      imagejpeg(imagecreatefrompng($img),'components/com_prayercenter/assets/fpdf/images/'.basename($pcConfig['config_imagefile'],'.png').'.jpg');
      $img = 'components/com_prayercenter/assets/fpdf/images/'.basename($pcConfig['config_imagefile'],'.png').'.jpg';
    } elseif($ext == 'png' && JFile::exists('components/com_prayercenter/assets/fpdf/images/'.basename($pcConfig['config_imagefile'],'.png').'.jpg')) {
      $img = 'components/com_prayercenter/assets/fpdf/images/'.basename($pcConfig['config_imagefile'],'.png').'.jpg';
    }
    $this->Image($img,160,8,20);
    $this->SetFont('helvetica','B',12);
    $this->Cell(0,5,$sitename.' - '.JText::_('PCTITLE').' '.JText::_('PCPRAYERREQUESTS'),0,0,'');
    $this->Ln();
    $this->SetFont('helvetica','',7);
    $listtype = $this->listtype;
    if($listtype == 1) {
      $this->Cell(0,5,JText::_('PCPDFDAILY').' '.$date,0,0,'');
    } elseif($listtype == 2){
      $this->Cell(0,5,JText::_('PCPDFWEEKLY').' '.date('F d',strtotime("-7 day")).' - '.date('F d, Y'),0,0,'');
    }
    $this->Ln(10);
  }
  function Footer(){
    jimport('joomla.date.date');
    $dateset = new JDate();
    $date = $dateset->format('F d, Y h:i:s A',false,true);
    $this->SetY(-15);
    $this->SetFont('helvetica','',6);
    $this->Cell(0,5,JText::_('PCPDFGEN').' '.$date,0,0,'C');
    $this->Ln(5);
    $this->SetFont('helvetica','I',6);
    $this->Cell(0,10,JText::_('PCPDFPAGE').' '.$this->PageNo(),0,0,'C');
  }
}
?>