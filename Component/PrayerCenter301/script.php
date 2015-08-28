<?php
/**
* @version $Id: script.php,v 3.x
* @package PrayerCenter
*/
defined ( '_JEXEC' ) or die ( 'Restricted Access' );
class com_prayercenterInstallerScript
{
  private $release = '3.0.1';
  private $params;
  function install($parent)
  {
    $this->params = $this->getParams();
//    $this->setParams($params);
//    $parent->getParent()->setRedirectURL('index.php?option=com_prayercenter');
  }
  function uninstall($parent)
  {
    $this->uninstallPlugin($parent);
  }
  function update($parent)
  {
//    echo '<p>'.JText::_('COM_PRAYERCENTER_UPDATE_TEXT').'</p>';
  }
  function preflight($type,$parent)
  {
    $JVersion = new JVersion();
    if(version_compare($JVersion->getShortVersion(), '3.0', 'lt')){
      JError::raiseWarning(null, 'Cannot install com_prayercenter in a Joomla release prior to 3.0');
      return false;
    }
    if($type == 'update'){
      include_once('components/com_prayercenter/helpers/pc_version.php');
      $pcversion = & PCVersion::getInstance();
      $oldrelease = $pcversion->getShortVersion();
      $rel = $oldrelease.' to '.$this->release;
      if(version_compare($this->release, $oldrelease, 'le')){
        JError::raiseWarning(null, 'Incorrect version sequence.  Cannot upgrade '.$rel);
        return false;
      } else {
        $rel = $this->release;
      }
    }
//    echo '<p>'.JText::_('COM_PRAYERCENTER_PREFLIGHT_'.$type.'_TEXT').'</p>';
  }
  function getParams(){
    $xml = JFactory::getXML(JPATH_ROOT.'/administrator/components/com_prayercenter/config.xml');
    $ini = array();
    $fieldsets = $xml->fields->fieldset;
    $fieldscount = count($fieldsets);
    for($i=0;$i<$fieldscount;$i++){
    	if( ! count($fieldsets[$i]->children())) {
    		return null;
    	}
    	foreach ($fieldsets[$i] as $field)
    	{
				if (($name = $field->attributes()->name) === null) {
					continue;
				}
				if (($value = $field->attributes()->default) === null) {
					continue;
				}
    		if ($name != '@spacer') {
      		$ini[(string) $name] = (string) $value;
    		}
      }
    }
    return $ini;
  }
  function setParams($param_array){
    if(count($param_array) > 0){
      $db = JFactory::getDbo();
      foreach($param_array as $name => $value){
        $params['params'][(string)$name] = (string)$value;
      }
      $paramString = json_encode($params);
      $db->setQuery('UPDATE #__extensions SET params='.$db->quote($paramString).' WHERE element="com_prayercenter"');
      $db->query();
    }
  }
  function setRules($param_array){
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('a.rules');
      $query->from('#__assets AS a');
      $query->group('a.id, a.rules, a.lft');
      $query->where('(a.name = ' . $db->quote('com_prayercenter')  . ')');
      $db->setQuery($query);
      $result = $db->loadColumn();
      if($result[0] == '{}'){
        $rules = json_encode(array('prayercenter.view'=>array(1=>1),'prayercenter.post'=>array(1=>1),'prayercenter.publish'=>array(7=>1,8=>1),'prayercenter.subscribe'=>array(1=>1),'prayercenter.devotional'=>array(1=>1),'prayercenter.links'=>array(1=>1),'core.admin'=>array(),'core.manage'=>array(),'core.create'=>array(),'core.delete'=>array(),'core.edit'=>array(),'core.edit.state'=>array(),'core.edit.own'=>array()));
        $db->setQuery("UPDATE #__assets SET rules=".$db->quote($rules)." WHERE name='com_prayercenter'");
        $db->query();
      }
  }
  function addPCCategory(){
    // Create categories for our component
    $basePath = JPATH_ADMINISTRATOR . '/components/com_categories';
    require_once $basePath . '/models/category.php';
    $config = array( 'table_path' => $basePath . '/tables');
    $catmodel = new CategoriesModelCategory( $config);
    $catData = array( 'id' => 0, 'parent_id' => 0, 'level' => 1, 'path' => 'uncategorized', 'extension' => 'com_prayercenter'
    , 'title' => 'Uncategorized', 'alias' => 'uncategorized', 'description' => '<p>This is the default PrayerCenter category</p>', 'published' => 1, 'language' => '*');
    $status = $catmodel->save( $catData);
    if(!$status) 
     {
//      JError::raiseWarning(500, JText::_('Unable to create default category!'));
     }
    $id1 = $catmodel->getItem()->id;
    $db = JFactory::getDbo();
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter_links LIKE 'catid'");
    $lwtable_nm1 =  $db->loadObjectList();
    if(count($lwtable_nm1) >0){
      $db->setQuery( "UPDATE #__prayercenter_links SET catid=".(int)$id1);
  		if (!$db->query()) {
  			return JError::raiseWarning( 500, $db->stderr() );
      }
		}
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter_devotions LIKE 'catid'");
    $lwtable_nm2 =  $db->loadObjectList();
    if(count($lwtable_nm2) >0){
      $db->setQuery( "UPDATE #__prayercenter_devotions SET catid=".(int)$id1);
  		if (!$db->query()) {
  			return JError::raiseWarning( 500, $db->stderr() );
      }
		}
  }
	function activatePlugin() {
		$db = JFactory::getDBO();
		$sql = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND folder='system' AND element='prayercenteremail'";
		$db->setQuery($sql);
		if(!$db->query()) {
			JError::raiseWarning(66508, JText::_('Could not publish PrayerCenterEmail plugin'));
		}
	}
	function installPlugin() {
		$folder = JPATH_ADMINISTRATOR.'/components/com_prayercenter/plugins/email/';
    $installedfolder = JPATH_ROOT.'/plugins/system/prayercenteremail/';
    $filearray = JFolder::files($folder);
		$status = true;
    $installed = JFolder::exists($installedfolder); 
		if(count($filearray)>0) {
			$installer = new JInstaller();
			if($installed) {
        if(!$installer->update($folder)) {
				  $status = false;
			 }
      } else {
        if(!$installer->install($folder)) {
  				$status = false;
        }
      }
		}
	 return $status;
	}
  function uninstallPlugin($parent)
  {
    $db = JFactory::getDBO();
    $query = "SELECT extension_id FROM #__extensions WHERE type='plugin' AND element='prayercenteremail' AND folder='system'";
    $db->setQuery($query);
    $id = $db->loadResult();
    $installer = new JInstaller;
    $installer->uninstall('plugin', $id);
  }
  function updateSendTo(){
    jimport('joomla.date.date');
    $dateset = new JDate();
    $now = $dateset->format('Y-m-d H:i:s');
		$db = JFactory::getDBO();
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'sendto'");
    $cksendtotype = $db->loadObjectList();
    if(!empty($cksendtotype) &&($cksendtotype[0]->Field == 'sendto' && $cksendtotype[0]->Type != 'datetime')){
//    if($cksendtotype[0]->Field == 'sendto' && $cksendtotype[0]->Type != 'datetime'){
      $db->setQuery( "ALTER TABLE #__prayercenter MODIFY sendto datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
  		if (!$db->query()) {
  			return JError::raiseWarning( 500, $db->stderr() );
  		}
  		$sql = "UPDATE #__prayercenter SET sendto='".$now."' WHERE publishstate=0";
  		$db->setQuery($sql);
  		if(!$db->query()) {
  			JError::raiseWarning(66508, JText::_('Could not modify SendTo status in PrayerCenter database'));
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'praise'");
    $cksendtotype2 = $db->loadObjectList();
    if(count($cksendtotype2)>0){
      $db->setQuery( "ALTER TABLE #__prayercenter DROP praise");
  		if (!$db->query()) {
  			return JError::raiseWarning( 500, $db->stderr() );
  		}
    }
    $db->setQuery("SHOW COLUMNS FROM #__prayercenter LIKE 'adminsendto'");
    $cksendtotype3 = $db->loadObjectList();
    if(count($cksendtotype3)<1){
      $db->setQuery("ALTER TABLE #__prayercenter ADD adminsendto datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
  		if (!$db->query()) {
  			return JError::raiseWarning( 500, $db->stderr() );
  		}
  		$sql = "UPDATE #__prayercenter SET adminsendto='".$now."' WHERE publishstate=0";
  		$db->setQuery($sql);
  		if(!$db->query()) {
  			JError::raiseWarning(66508, JText::_('Could not modify AdminSendTo status in PrayerCenter database'));
  		}
    }
  }
  function postflight($type,$parent)
  {
//    echo '<p>'.JText::_('COM_PRAYERCENTER_POSTFLIGHT_'.$type.'_TEXT').'</p>';
		$installPlugin = $this->installPlugin();
 		if(!$installPlugin) {
 			JError::raiseWarning(66508, JText::_('Could not install PrayerCenterEmail plugin. Uninstall any previous versions and install manually.'));
 		} else {
      $this->activatePlugin();
    }
    if($type == 'update'){
      $pcParams = JComponentHelper::getParams('com_prayercenter');
      $pcParamsArray = $pcParams->toArray();
      foreach($pcParamsArray['params'] as $name => $value){
        $pcConfig[(string)$name] = (string)$value;
      }
      $this->setRules($pcConfig);
      $this->updateSendTo();
      $parent->getParent()->setRedirectURL('index.php?option=com_installer&view=update');
    } elseif($type == 'install'){
      $this->setParams($this->params);
      $this->setRules();
      $this->addPCCategory();
      $this->updateSendTo();
      $parent->getParent()->setRedirectURL('index.php?option=com_prayercenter');
    }
  }
}
?>