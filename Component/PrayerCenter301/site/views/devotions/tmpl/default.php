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
$JVersion = new JVersion();
  $prayercenter->PCgetAuth('view_devotional');
  $k = 0;
  if($this->config_use_gb){
    JHtml::_('behavior.modal');
    $attribs['rel'] = "{handler: 'iframe', size: {x: 800, y: 450}}";
    $attribs['class'] = 'modal'; 
  }
    echo '<div>';
    if($this->config_show_page_headers) echo '<div class="componentheading"><h2>'.htmlentities($this->title.' - '.JText::_('PCDEVOTIONALS')).'</h2></div>';
    echo '<div>';
    $prayercenter->buildPCMenu();
    echo '</div><div>';
    $prayercenter->writePCImage().'</div><div>';
    echo $prayercenter->writePCHeader($this->intro).'</div>';
    echo '<fieldset class="pcmod"><legend>'.htmlentities(JText::_('PCDEVOTIONALS')).'</legend>';
    echo '<div class="moddevotion">';
    if(count($this->feed_array)>0){
      foreach ($this->feed_array as $feedfile) {
        $k++;
				if (!is_null( $feedfile->feed ))
				{
      		$options = array();
      		$options['rssUrl'] = $feedfile->feed;
      		if($this->config_enable_cache) {
      			$options['cache_time']  = $this->config_update_time;
      		} else {
      			$options['cache_time'] = null;
      		}
     		  $rssDoc = JFactory::getFeedParser($options['rssUrl'], $options['cache_time']);
          //Clearing feed_parser cache may be a temporary fix for non-visible feeds (cache corruption?)
          $cache = JFactory::getCache('feed_parser');
          $cache->clean();
				}
    		if ($rssDoc != false){
    		$feed = new stdclass();
    			$feed->title = $rssDoc->get_title();
    			$feed->link = $rssDoc->get_link();
    			$feed->description = $rssDoc->get_description();
    			$feed->image->url = $rssDoc->get_image_url();
    			$feed->image->title = $rssDoc->get_image_title();
    			$items = $rssDoc->get_items();
    			$feed->items = array_slice($items, 0, $this->config_item_limit);
    		} else {
    			$feed->title = JText::_('ERROR LOADING FEED DATA');
    			$feed->link = null;
    			$feed->description = $feedfile->feed;
    			$feed->image->url = null;
    			$feed->image->title = null;
    			$items = null;
    			$feed->items = null;
    		}
      	$iUrl 	= isset($feed->image->url)   ? $feed->image->url   : null;
      	$iTitle = isset($feed->image->title) ? $feed->image->title : null;
        if($k > 1) echo '<hr>';
				?>
				<div><dl style="padding:10px 0px;"><dt><span class="devtitle">
				<?php
				if ( $iUrl && $this->config_feed_image ) {
					?>
					<img src="<?php echo $iUrl; ?>" title="<?php echo $iTitle; ?>" class="devimg" />
					<?php
				}
          ?>
					<font size="4">&nbsp;&nbsp;
          <?php
          $attribs['target'] = '_blank';
          $feedlink = JHTML::_('link', JRoute::_($feed->link), $feed->title, $attribs);
					?>
          <?php echo $feedlink;?>
          </font></span>
				<?php
				if ( $this->config_feed_descr ) {
					?>&nbsp;&nbsp;<span class="devtitledescrip">-&nbsp;<?php echo $feed->description; ?></span>
					<?php
				}
				$actualItems 	= count($feed->items);
				?></dt></dl><br />
				<dl class="mod">
						<?php
						for ( $j = 0; $j < $actualItems; $j++ ) {
							$currItem =& $feed->items[$j];
							?>
							<dt>
								<?php							
								if ($currItem->get_link()) {
									?>
									<font size="3">
                  <?php
                  $attribs['target'] = '_blank';
                  $currItemlink = JHTML::_('link', JRoute::_($currItem->get_link()), $currItem->get_title(), $attribs);
        					?>
                  <?php echo $currItemlink;?>
                  </font>
									<?php
								} 								
								if ( $this->config_item_descr ) {
        					$text = html_entity_decode($currItem->get_description());
				        	$text = str_replace('&apos;', "'", $text);
									$num 	= $this->config_word_count;
									if ( $num > -1 ) {
										$texts = explode( ' ', $text );
										$count = count( $texts );
										if ( $count > $num ) {
											$text = '';
											for( $i=0; $i < $num; $i++ ) {
												$text .= ' '. $texts[$i];
											}
											$text .= '...';
										}
									}
									echo '<br /><br /><dd>'.$text;
								}
								?>
							</dd></dt>
            <?php
            if($j < $actualItems - 1) echo '<br />';
						}
						?>
					</dl></div>
				<?php
				if($k < count($this->feed_array)) echo '<div>&nbsp;</div>';
      unset($rssDoc);
			}
    echo '<div colspan="2">&nbsp;</div>';
    echo '</div></fieldset>';
    echo '</div>';
    }
    else {
  	 echo '<br /><div><strong><div class="content"><br />'.htmlentities(JText::_('PCNODEVOTIONS')).'</div></strong>
            </div><tfoot><tr><td colspan="2">&nbsp;</div><br /></fieldset></div><br />';
     }
    $prayercenter->writePCFooter();
?>