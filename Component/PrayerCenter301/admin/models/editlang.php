<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_prayercenter
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
class PrayerCenterModelEditLang extends JModelAdmin
{
	protected $text_prefix = 'COM_PRAYERCENTER';
	protected function populateState()
	{
	}
	public function getTable($type = '', $prefix = '', $config = array())
	{
		return;// JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();
		$form = $this->loadForm('com_prayercenter.editlang', 'lang', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	protected function loadFormData()
	{
    $id = $this->getState('id');
    $edit = $this->getState('edit');
		if(!$this->getState('files')){
      $lffile = 'en-GB.com_prayercenter.ini';
      $lffolder = 'en-GB';
     } else {
    $d = "#[\\\/]#";
    $lf = preg_split($d,$this->getState('files'),-1,PREG_SPLIT_NO_EMPTY);
    $lffile = $lf[count($lf)-1];
    $lffolder = $lf[count($lf)-2];
    }
    if($edit){
			$data->config_langfile = $lffile;
			$data->config_langfolder = $lffolder;
      $filename = JPATH_ROOT.'/language/'.$lffolder.'/'.$lffile;
      $initstring = file_get_contents($filename);
			$data->config_lang = $initstring;
    } else {
			$data->config_langfile = "";
			$data->config_langfolder = "";
			$data->config_lang = "";
    }
		return $data;
	}
	protected function prepareTable($table)
	{
	}
	protected function getReorderConditions($table)
	{
	}
}