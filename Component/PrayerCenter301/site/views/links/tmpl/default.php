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
  global $prayercenter;
  $prayercenter->PCgetAuth('view_links');
    if($this->config_use_gb){
      JHtml::_('behavior.modal');
      $attribs['rel'] = "{handler: 'iframe', size: {x: 800, y: 450}}";
      $attribs['class'] = 'modal'; 
    }
    $document = JFactory::getDocument();
    $document->addScript('components/com_prayercenter/assets/js/pc.js');
    $JVersion = new JVersion();
 		$img =  JHTML::_('image', JURI::base().'media/system/images/weblink.png', htmlentities(JText::_('PCFEEDS')), 'style="border:0;"');
    echo '<div>';
    if($this->config_show_page_headers) echo '<div class="componentheading"><h2>'.htmlentities($this->title.' - '.JText::_('PCLINKSLIST')).'</h2></div>';
    echo '<div>';
    $prayercenter->buildPCMenu();
    echo '</div><div>';
    $prayercenter->writePCImage().'</div><div>';
    echo $prayercenter->writePCHeader($this->intro).'</div>';
    echo '<fieldset class="pcmod"><legend>'.htmlentities(JText::_('PCLINKSLIST')).'</legend>';
    echo '<div class="modlink">';
    $content = "";
    $cat = "";
    if (count($this->link_array) > 0){
		if ($this->config_two_columns) {
			$col1count = ceil( count( $this->link_array ) / 2 );
			$content .= '<div class="mod-left">';
				$content .= '<dl>';			
				for ($i = 0,$j = 1; $i < $col1count; $i++){
          $links = $this->link_array[$i];
          if($cat != $links->category && $this->config_show_linkcats) $content .= '<span style=""><h3>'.$links->category.'</h3></span><hr>';
          $attribs['title'] = $links->alias;
          $attribs['target'] = '_blank';
          $weblink = JHTML::_('link', JRoute::_($links->url), '<b>'.stripslashes($links->name).'</b>', $attribs);
					$content .= '<br /><dt>'.$img.'&nbsp;&nbsp;'.stripslashes($weblink).'</dt>';
 		    	$content .= ' <dd class="mod"><b><i>'.$links->descrip.'</i></b></dd><br />';
          if($j < $col1count) $content .= '<hr class="modlink">';
          $j++;
          $cat = $links->category;
        }
				$content .= '</dl></div>';
        $content .= '<div class="mod-right"><dl>';
				$cat = "";
        for ($i,$j=($i-1); $i < count( $this->link_array ); $i++){
          $links = $this->link_array[$i];
          if($cat != $links->category && $this->config_show_linkcats) $content .= '<span style=""><h3>'.$links->category.'</h3></span><hr>';
          $attribs['title'] = $links->alias;
          $attribs['target'] = '_blank';
          $weblink = JHTML::_('link', JRoute::_($links->url), '<b>'.stripslashes($links->name).'</b>', $attribs);
					$content .= '<br /><dt>'.$img.'&nbsp;&nbsp;'.stripslashes($weblink).'</dt>';
 		    	$content .= ' <dd class="mod"><b><i>  '.$links->descrip.'</i></b></dd><br />';
          if($j < (count( $this->link_array )-($i-2)) && $j != 0) $content .= '<hr class="modlink">';
          $j++;
          $cat = $links->category;
        }
				$content .= '</dl></div>';
			} else {
          $i = 0;
          $lcount = count( $this->link_array );
    			$content .= '<div><dl>';
          foreach($this->link_array as $links){
            if($cat != $links->category && $this->config_show_linkcats) $content .= '<span style=""><h3>'.$links->category.'</h2></span><hr>';
            $attribs['title'] = $links->alias;
            $attribs['target'] = '_blank';
            $weblink = JHTML::_('link', JRoute::_($links->url), '<b>'.stripslashes($links->name).'</b>', $attribs);
  					$content .= '<br /><dt style="margin-left:20px;">'.$img.'&nbsp;&nbsp;'.stripslashes($weblink).'</dt>';
  		    	$content .= ' <dd class="mod" style="margin-left:25px;"><b><i>'.$links->descrip.'</i></b></dd>';
  			    $content .= '<br /></dt>';
            $i++;
            $cat = $links->category;
            if($i < $lcount) $content .= '<hr class="modlink">';
         }
         $content .= '</dl></div>';
     }
    echo $content;
    echo '</div><br /></fieldset>';
    echo '<br />';
    } else {
     echo '<br />';
  	 echo '<div><strong><div class="content"><br />'.htmlentities(JText::_('PCNOLINKS')).'</div></strong>
            </div></fieldset>';
     }
    echo '</div>';
    $prayercenter->writePCFooter();
?>