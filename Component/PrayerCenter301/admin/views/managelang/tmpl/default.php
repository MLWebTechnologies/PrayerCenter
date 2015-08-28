<?php
/**
* PrayerCenter Component for Joomla
* By Mike Leeper
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
    global $prayercenteradmin;
    $lang = JFactory::getLanguage();
    $lang->load( 'com_prayercenter', JPATH_SITE); 
  	$version = new JVersion();
//  	$prayercenteradmin->PCsideBar();
    JHtml::_('bootstrap.tooltip');
    JHtml::_('behavior.multiselect');
    JHtml::_('formbehavior.chosen', 'select');
    include_once('components/com_prayercenter/helpers/pc_version.php');
    $pcversion = new PCVersion();
    $livesite = JURI::base();
    $lang_path = JPATH_ROOT.'/language/';
    jimport('joomla.filesystem.folder');
    $langfolderarray = JFolder::folders($lang_path, '.', false, false, array('pdf_fonts'));
    $langarray = array();
    foreach($langfolderarray as $langfolder){
      $langfilesarray = JFolder::files($lang_path.$langfolder,'com_prayercenter.ini',false,true);
      $langarray = array_merge_recursive($langarray,$langfilesarray);
     }
    $lfsubmit = "";
    $lfsubmit .= 'PrayerCenter Language File Submission%0D%0A%0D%0A';
    $lfsubmit .= "PrayerCenter Version:%20".$pcversion->getLongVersion()."%0D%0A%0D%0A";
    $lfsubmit .= 'Joomla! Version:%20'.$version->getLongVersion().'%0D%0A%0D%0A%0D%0A%0D%0A';
    $lfsubmit .= 'Do you wish to have your name and/or email address listed as the author of this language file?%0D%0A%0D%0A';
    $lfsubmit .= 'Would you agree to be contacted when language file changes are made in future version of PrayerCenter?%0D%0A%0D%0A%0D%0A%0D%0A';
    $lfsubmit .= 'Please attach the language file to this message. It will then be made available for others to download.%0D%0A%0D%0A';
    $client	= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
    $params = JComponentHelper::getParams('com_languages');
    $defaultlang = $params->get($client->name, 'en-GB');
    ?>
    <div id="j-main-container" class="span12">
    <div class="well well-small" style="color:#08c;font-size:small;"><div class="module-title nav-header">Installed Language Files</div>
  	<form action="index.php?option=com_prayercenter&task=manage_lang" method="post" name="adminForm" id="adminForm">
		<table class="table table-striped" id="lwlangList">
  	<thead>
    	<tr>
			<th width="5%"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
			<th width="15%" class="title nowrap small hidden-phone"><?php echo JText::_( 'Joomla Default' );?></th>
			<th class="title nowrap small hidden-phone" width="5%"><?php echo JText::_( 'Tag');?></th>
			<th class="title nowrap small hidden-phone" width="15%" class="title"><?php echo JText::_( 'File');?></th>
			<th class="title nowrap small hidden-phone" width="15%" class="title"><?php echo JText::_( 'Name' );?></th>
			<th class="title nowrap small hidden-phone" width="5%"><?php echo JText::_( 'Version' ); ?></th>
			<th class="title nowrap small hidden-phone" width="10%"><?php echo JText::_( 'Date' ); ?></th>
			<th class="title nowrap small hidden-phone" width="15%"><?php echo JText::_( 'Author' ); ?></th>
			<th class="title nowrap small hidden-phone" width="15%"><?php echo JText::_( 'Author Email' ); ?></th>
		</tr></thead><tbody>
    <?php
    for($j=0;$j<count($langarray);$j++)
    {
      $d = "#[\\\/]#";
      $lf = preg_split($d,$langarray[$j],-1,PREG_SPLIT_NO_EMPTY);
      $lffile = count($lf)-1;
      $lffolder = $lffile-1;
			$ldata = JApplicationHelper::parseXMLLangMetaFile($lang_path.$lf[$lffolder].'/'.$lf[$lffolder].'.xml');
			$row = new StdClass();
			$row->id = $j;
			$row->language = substr($lf[$lffolder].'.xml',0,-4);
			if (!is_array($ldata)) {
				continue;
			}
			foreach($ldata as $key => $value) {
				$row->$key = $value;
			}
    $content = file($lang_path.$lf[$lffolder].'/'.$lf[$lffile]);
		if (strpos($content[0],'.ini')) {
			$line = preg_replace('/^.*[.]ini[ ]+/','',$content[0]);
			list( $file['version'], $file['date'], $file['time'], $file['owner'], $file['complete'] ) = explode( ' ', $line . '   ', 6 );
			$file['headertype'] = 1;
		}
		$file['author'] 	= preg_replace('/^.*author[ ]+/i', '', trim($content[1],'# ') );
		$file['author email'] 	= preg_replace('/^.*author email[ ]+/i', '', trim($content[2],'# ') );
		$file['language'] 	= preg_replace('/^.*language[ ]+/i', '', trim($content[5],'# ') );
    ?>
		<tr style="color:#555;">
			<td class="center hidden-phone" width="5">
      	<?php echo JHtml::_('grid.id', $j, $lf[$lffolder].'/'.$lf[$lffile]); ?>
      </td>
      <td class="center hidden-phone small">
        <?php echo $row->name;?>
      </td>
       <td class="small hidden-phone nowrap"><?php echo $lf[$lffolder]; ?>
      			<?php if(file_exists(JPATH_ROOT.'/media/mod_languages/images/'.preg_replace('/(\-\w+)/','',$lf[$lffolder]).'.gif') ) echo '&nbsp;'.JHtml::_('image', 'mod_languages/'.preg_replace('/(\-\w+)/','',$lf[$lffolder]).'.gif', $row->name, array('title'=>$row->name), true);?>
      </td>
			<td class="small hidden-phone nowrap"><span class="editlangtip hasTip" title="<?php echo JText::_( 'Edit Language File' );?>::<?php echo $lf[$lffile]; ?>">
				<a href="#edit" onclick="return listItemTask('cb<?php echo $j; ?>','editlang')">
				<?php echo $lf[$lffile]; ?></a></span></td>
			<td class="small hidden-phone nowrap">
   			<?php
					echo $file['language'];
				?>
      </td>
			<td class="small hidden-phone center"><?php echo $file['version']; ?></td>
			<td class="small hidden-phone nowrap"><?php echo $file['date']; ?></td>
			<td class="small hidden-phone nowrap"><?php echo $file['author']; ?></td>
			<td class="small hidden-phone"><?php echo $file['author email']; ?></td>
    </tr>
    <?php
    }
    ?>    
		<input type="hidden" name="boxchecked" value="0" />
 		<input type="hidden" name="task" value="" />
	  <?php echo JHTML::_( 'form.token' ); ?>
		</tbody></table></form></div>
		<div class="well well-small">
  	<table class="table table-striped">
    <form enctype="multipart/form-data" action="index.php?option=com_prayercenter&task=uploadLangfile" method="POST" name="LangFileUp" id="LangFileUp">
    <tr><td class="small hidden-phone center"><input type="hidden" name="MAX_FILE_SIZE" value="100000" />
    <b>Choose a file to upload:&nbsp;</b>
    <input name="uploadedlangfile" type="file" class="radio small" style="height:22px;" /> 
    <input type="submit" class="radio small" value="Upload File" style="height:15px;padding:0 4px 16px 4px;" /> 
    </td></tr></form></table>
    </div>
    <div class="well well-small"><div class="module-title nav-header">Quick Links</div>	<div class="row-striped">
      <div class="row-fluid"><div class="span12"><a href="http://www.joomlacode.org/gf/project/prayercenter/frs/"><i class="icon-download"></i> <span><?php echo JText::_('Download available language files'); ?></span></a></div></div>
      <div class="row-fluid"><div class="span12"><a href="/administrator/index.php?option=com_languages"><i class="icon-flag"></i> <span><?php echo JText::_('Joomla Language Manager'); ?></span></a></div></div>
      <div class="row-fluid"><div class="span12"><a href="mailto:web@mlwebtechnologies.com?subject=PrayerCenter%20Language%20File%20Submission&body=<?php echo $lfsubmit; ?>"><i class="icon-envelope"></i> <span><?php echo JText::_('Submit a language file'); ?></span></a></div></div>
     </div>
	  </div>
	<?php
  $prayercenteradmin->PCfooter();
?></div>