<?php
/**
 * @version		$Id: pcimagelist.php 20196 2011-01-09 02:40:25Z ian $
 * @package		PrayerCenter
 * @copyright	Copyright (C) 2006 - 2014 MLWebTechnologies. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('filelist');
class JFormFieldPCImageList extends JFormField
{
	protected $type = 'PCImageList';
	protected static $initialised = false;
	protected function getInput()
	{
    $livesite = JURI::root();
		if (!self::$initialised) {
			$script = array();
			$script[] = '	function showImage(img) {';
			$script[] = '		var site = "'.$livesite.'"';
			$script[] = "		var imgObj = document.images['config_preview'];";
			$script[] = "		imgObj.src = site + 'components/com_prayercenter/assets/images/' + img;";
			$script[] = '	}';
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
			self::$initialised = true;
		}
		$html = array();
		$html[] = '<div class="fltlft"><img name="config_preview" src="'.JURI::root().'components/com_prayercenter/assets/images/'.$this->value.'" style="height:70px;width:70px;" /></div>';
		$directory = (string)$this->element['directory'];
		$exclude = explode(",",$this->element['exclude']);
		$filter = (string)$this->element['filter'];
    $preview_script = "javascript:showImage(this.value);";
		$files = JFolder::files($directory, $filter, false, false, $exclude);
		if (is_array($files))
		{
			foreach ($files as $file)
			{
				$images[] = JHtml::_('select.option', $file, $file);
      }
		}
    $imagelist = JHtml::_(
			'select.genericlist',
			$images,
			"jform[params][config_imagefile]",
			array(
				'list.attr' => 'class="inputbox" size="1" ' . 'onchange="'.$preview_script.'"',
				'list.select' => $this->value
			)
		);
    
    $html[] = '&nbsp;&nbsp;<div valign="middle">'.$imagelist."</div>";
		return implode("\n", $html);
	}
}