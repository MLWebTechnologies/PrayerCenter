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
  $plugin_path = JPATH_COMPONENT.'/plugins/pms/';
  jimport('joomla.filesystem.folder');
  $filesarray = JFolder::files($plugin_path, '.', false, false, array('.svn', 'CVS','.DS_Store','__MACOSX','index.html'));
  $image_path = JPATH_ROOT.'/components/com_prayercenter/assets/images/';
  $imagesarray = JFolder::files($image_path);
  $slideshow_path = JPATH_ROOT.'/components/com_prayercenter/assets/images/slideshow/';
  $slideshowarray = JFolder::files($slideshow_path, '.', false, false, array('.svn', 'CVS','.DS_Store','__MACOSX','index.html'));
  $lang_path = JPATH_ROOT.'/language/';
  $langfolderarray =JFolder::folders($lang_path, '.', false, false, array('pdf_fonts'));
  $langarray = array();
  foreach($langfolderarray as $langfolder){
    $langfilesarray = JFolder::files($lang_path.$langfolder,'com_prayercenter.ini',false,true);
    $langarray = array_merge_recursive($langarray,$langfilesarray);
   }
  $template	= JFactory::getApplication()->getTemplate();
  $imagedir = 'templates/'.$template.'/images/admin';
  ?>
	<div id="j-main-container" class="span12">
		<div class="well well-small" style="color:#08c;font-size:small;"><div class="module-title nav-header">Plugins</div><div class="row-striped">
  	<table class="table table-striped">
    <form enctype="multipart/form-data" action="index.php?option=com_prayercenter&task=showUploadFile" method="POST" name="FileUp">
		<thead><tr>
			<th class="small hidden-phone" width="200" class="key"><?php echo 'File' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Type' ?></th>
			<th class="small hidden-phone" width="200" class="key"><?php echo 'Date' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Size' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Permission' ?></th>
			<th class="small hidden-phone center" width="100"><?php echo 'Delete?' ?></th>
		</tr></thead>
    <?php
    for($j=0;$j<count($filesarray);$j++)
    {
    ?>
		<tbody><tr style="color:#555;">
       <td width="200" class="small hidden-phone"><?php echo $filesarray[$j]; ?></td>
       <td width="100" class="small hidden-phone"><?php echo $prayercenteradmin->findext($filesarray[$j]); ?></td>
       <td width="200" class="small hidden-phone nowrap"><?php echo date("d F Y h:i:s A",filemtime($plugin_path.$filesarray[$j])); ?></td>
       <td width="100" class="small hidden-phone"><?php echo round(filesize($plugin_path.$filesarray[$j])/1024,2); ?> kb</td>
       <td width="100" class="small hidden-phone"><?php echo substr(sprintf('%o', fileperms($plugin_path.$filesarray[$j])), -4); ?> (<?php $prayercenteradmin->fullfileperms(fileperms($plugin_path.$filesarray[$j]));?>)</td>
       <td class="small hidden-phone center"><a href="javascript:if(confirm('Delete <?php echo $filesarray[$j];?>?')) window.location.href='index.php?option=com_prayercenter&task=deletefile&controller=prayercenter&file=<?php echo $filesarray[$j];?>'; else void(0);">
			<img src="<?php echo $imagedir;?>/publish_r.png" width="12" height="12" border="0" alt="<?php echo 'Delete';?>" /></a>
      </td>
    </tr></tbody>
    <?php
    }
    ?>    
    <tr><td class="small hidden-phone center" colspan="6">
    <input type="hidden" name="MAX_FILE_SIZE" value="100000" /><b>
    Choose a file to upload:&nbsp;</b><input name="uploadedfile" type="file" class="radio small" style="height:22px;" /> 
    <input type="submit" class="radio small" value="Upload File" style="height:15px;padding:0 4px 16px 4px;" /> 
    <input type="hidden" name="task" value="uploadfile" />
		<input type="hidden" name="option" value="com_prayercenter" />
		<input type="hidden" name="controller" value="prayercenter" />
    </td></tr></form></table>
  </div></div>
	<div class="well well-small" style="color:#08c;font-size:small;"><div class="module-title nav-header">Language</div><div class="row-striped">
  	<table class="table table-striped">
    <form enctype="multipart/form-data" action="index.php?option=com_prayercenter&task=showUploadFile" method="POST" name="LangFileUp">
		<thead><tr>
			<th class="small hidden-phone" width="200"><?php echo 'File' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Folder' ?></th>
			<th class="small hidden-phone" width="200"><?php echo 'Date' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Size' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Permission' ?></th>
			<th class="small hidden-phone center" width="100"><?php echo 'Delete?' ?></th>
		</tr></thead>
    <?php
    for($j=0;$j<count($langarray);$j++)
    {
      $d = "#[\\\/]#";
      $lf = preg_split($d,$langarray[$j],-1,PREG_SPLIT_NO_EMPTY);
      $lffile = count($lf)-1;
      $lffolder = $lffile-1;
    ?>
		<tbody><tr style="color:#555;">
       <td width="200" class="small hidden-phone"><?php echo $lf[$lffile]; ?></td>
       <td width="100" class="small hidden-phone"><?php echo $lf[$lffolder]; ?></td>
       <td width="200" class="small hidden-phone"><?php echo date("d F Y h:i:s A",filemtime($lang_path.$lf[$lffolder].'/'.$lf[$lffile])); ?></td>
       <td width="100" class="small hidden-phone"><?php echo ceil(filesize($lang_path.$lf[$lffolder].'/'.$lf[$lffile])/1024); ?> kb</td>
       <td width="100" class="small hidden-phone"><?php echo substr(sprintf('%o', fileperms($lang_path.$lf[$lffolder].'/'.$lf[$lffile])), -4); ?> (<?php $prayercenteradmin->fullfileperms(fileperms($lang_path.$lf[$lffolder].'/'.$lf[$lffile]));?>)</td>
       <td class="small hidden-phone center"><a href="javascript:if(confirm('Delete <?php echo $langarray[$j];?>?')) window.location.href='index.php?option=com_prayercenter&task=deleteLangfile&controller=prayercenter&file=<?php echo $langarray[$j];?>'; else void(0);">
			<img src="<?php echo $imagedir;?>/publish_r.png" width="12" height="12" border="0" alt="<?php echo 'Delete';?>" /></a>
    </td>
    </tr></tbody>
    <?php
    }
    ?>    
    <tr><td colspan="6" class="small hidden-phone center">
    <input type="hidden" name="MAX_FILE_SIZE" value="100000" /><b>
    Choose a file to upload:&nbsp;</b><input name="uploadedlangfile" type="file" class="radio small" style="height:22px;" /> 
    <input type="submit" class="radio small" value="Upload File" style="height:15px;padding:0 4px 16px 4px;" /> 
    <input type="hidden" name="task" value="uploadLangfile" />
		<input type="hidden" name="option" value="com_prayercenter" />
		<input type="hidden" name="controller" value="prayercenter" />
    </td></tr></form></table>
  </div></div>
	<div class="well well-small" style="color:#08c;font-size:small;"><div class="module-title nav-header">Images</div><div class="row-striped">
  	<table class="table table-striped">
    <form enctype="multipart/form-data" action="index.php?option=com_prayercenter&task=showUploadFile" method="POST" name="ImageUp">
		<thead><tr>
			<th class="small hidden-phone" width="200"><?php echo 'Image' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'File Type' ?></th>
			<th class="small hidden-phone" width="200"><?php echo 'Date' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Size' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Permission' ?></th>
			<th class="small hidden-phone center" width="100"><?php echo 'Delete?' ?></th>
		</tr></thead>
    <?php
    for($j=0;$j<count($imagesarray);$j++)
    {
      $imagetype = $prayercenteradmin->findimageext($imagesarray[$j]);
      if($imagetype == "JPG" | $imagetype == "PNG" | $imagetype == "GIF")
      {
      ?>
  		<tbody><tr style="color:#555;">
         <td width="200" class="small hidden-phone"><?php echo $imagesarray[$j]; ?></td>
         <td width="100" class="small hidden-phone"><?php echo $imagetype; ?></td>
         <td width="200" class="small hidden-phone"><?php echo date("d F Y h:i:s A",filemtime($image_path.$imagesarray[$j])); ?></td>
         <td width="100" class="small hidden-phone"><?php echo round(filesize($image_path.$imagesarray[$j])/1024,2); ?> kb</td>
         <td width="100" class="small hidden-phone"><?php echo substr(sprintf('%o', fileperms($image_path.$imagesarray[$j])), -4); ?> (<?php $prayercenteradmin->fullfileperms(fileperms($image_path.$imagesarray[$j]));?>)</td>
         <td class="small hidden-phone center"><a href="javascript:if(confirm('Delete <?php echo $imagesarray[$j];?>?')) window.location.href='index.php?option=com_prayercenter&task=deleteimage&controller=prayercenter&image=<?php echo $imagesarray[$j];?>'; else void(0);">
  			<img src="<?php echo $imagedir;?>/publish_r.png" width="12" height="12" border="0" alt="<?php echo 'Delete';?>" /></a>
      </td>
      </tr></tbody>
      <?php
      }
    }
    ?>    
    <tr><td class="small hidden-phone center" colspan="6">
    <input type="hidden" name="MAX_FILE_SIZE" value="100000" /><b>
    Choose a image to upload:&nbsp;</b><input name="uploadedimage" type="file" class="radio small" style="height:22px;" /> 
    <input type="submit" class="radio small" value="Upload File" style="height:15px;padding:0 4px 16px 4px;" /> 
    <input type="hidden" name="task" value="uploadimage" />
		<input type="hidden" name="option" value="com_prayercenter" />
		<input type="hidden" name="controller" value="prayercenter" />
    </td></tr></form></table>
  </div></div>
	<div class="well well-small" style="color:#08c;font-size:small;"><div class="module-title nav-header">Slideshow Images</div><div class="row-striped">
  	<table class="table table-striped">
    <form enctype="multipart/form-data" action="index.php?option=com_prayercenter&task=showUploadFile" method="POST" name="SSImageUp">
		<thead><tr>
			<th class="small hidden-phone" width="200"><?php echo 'Image' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'File Type' ?></th>
			<th class="small hidden-phone" width="200"><?php echo 'Date' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Size' ?></th>
			<th class="small hidden-phone" width="100"><?php echo 'Permission' ?></th>
			<th class="small hidden-phone center" width="100"><?php echo 'Delete?' ?></th>
		</tr></thead>
    <?php
    for($j=0;$j<count($slideshowarray);$j++)
    {
      $imagetype = $prayercenteradmin->findimageext($slideshowarray[$j]);
      if($imagetype == "JPG" | $imagetype == "PNG" | $imagetype == "GIF")
      {
      ?>
  		<tbody><tr style="color:#555;">
         <td width="200" class="small hidden-phone"><?php echo $slideshowarray[$j]; ?></td>
         <td width="100" class="small hidden-phone"><?php echo $imagetype; ?></td>
         <td width="200" class="small hidden-phone"><?php echo date("d F Y h:i:s A",filemtime($slideshow_path.$slideshowarray[$j])); ?></td>
         <td width="100" class="small hidden-phone"><?php echo round(filesize($slideshow_path.$slideshowarray[$j])/1024,2); ?> kb</td>
         <td width="100" class="small hidden-phone"><?php echo substr(sprintf('%o', fileperms($slideshow_path.$slideshowarray[$j])), -4); ?> (<?php $prayercenteradmin->fullfileperms(fileperms($slideshow_path.$slideshowarray[$j]));?>)</td>
         <td class="small hidden-phone center"><a href="javascript:if(confirm('Delete <?php echo $slideshowarray[$j];?>?')) window.location.href='index.php?option=com_prayercenter&task=deletessimage&controller=prayercenter&image=<?php echo $slideshowarray[$j];?>'; else void(0);">
  			<img src="<?php echo $imagedir;?>/publish_r.png" width="12" height="12" border="0" alt="<?php echo 'Delete';?>" /></a>
      </td>
      </tr></tbody>
      <?php
      }
    }
    ?>    
    <tr><td colspan="6" class="small hidden-phone center">
    <input type="hidden" name="MAX_FILE_SIZE" value="100000" /><b>
    Choose a image to upload:&nbsp;</b><input name="uploadedssimage" type="file" class="radio small" style="height:22px;" /> 
    <input type="submit" class="radio small" value="Upload File" style="height:15px;padding:0 4px 16px 4px;" /> 
    <input type="hidden" name="task" value="uploadssimage" />
		<input type="hidden" name="option" value="com_prayercenter" />
		<input type="hidden" name="controller" value="prayercenter" />
    </td></tr></form></table></div></div>
	<?php
  $prayercenteradmin->PCfooter();
?></div>