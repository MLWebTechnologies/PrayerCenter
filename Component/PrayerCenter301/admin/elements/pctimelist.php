<?php
/**
 * @Component - PrayerCenter
 * @copyright Copyright (C) MLWebTechnologies
 * @license GNU/GPL
 */
// no direct access
defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
class JFormFieldPCTimelist extends JFormFieldList
{
/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'PCTimelist';
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
protected function getOptions()
	{$db = JFactory::getDBO();
$doc = & JFactory::getDocument();
		$js = "
		window.addEvent('domready', function(){
			var filter1 = $('jform_params_sendfreq0');
			if (!filter1) return;
			filter1.addEvent('click', function(){
				$('jform_params_sendtime').setProperty('disabled', 'disabled');
				$('jform_params_sendday').setProperty('disabled', 'disabled');
                $('jform_params_senddate').setProperty('disabled', 'disabled');
			})
			$('jform_params_sendfreq1').addEvent('click', function(){
				$('jform_params_sendtime').removeProperty('disabled');
                $('jform_params_sendday').setProperty('disabled', 'disabled');
                $('jform_params_senddate').setProperty('disabled', 'disabled');
			})
            $('jform_params_sendfreq2').addEvent('click', function(){
                $('jform_params_sendtime').removeProperty('disabled');
                $('jform_params_sendday').removeProperty('disabled');
                $('jform_params_senddate').setProperty('disabled', 'disabled');
            })
            $('jform_params_sendfreq3').addEvent('click', function(){
                $('jform_params_sendtime').removeProperty('disabled');
                $('jform_params_sendday').setProperty('disabled', 'disabled');
                $('jform_params_senddate').removeProperty('disabled');
            })
			if ($('jform_params_sendfreq0').checked) {
				$('jform_params_sendtime').setProperty('disabled', 'disabled');
                $('jform_params_sendday').setProperty('disabled', 'disabled');
                $('jform_params_senddate').setProperty('disabled', 'disabled');
			}
			if ($('jform_params_sendfreq1').checked) {
                $('jform_params_sendtime').removeProperty('disabled');
                $('jform_params_sendday').setProperty('disabled', 'disabled');
                $('jform_params_senddate').setProperty('disabled', 'disabled');
            }
            if ($('jform_params_sendfreq2').checked) {
                $('jform_params_sendtime').removeProperty('disabled');
                $('jform_params_sendday').removeProperty('disabled');
                $('jform_params_senddate').setProperty('disabled', 'disabled');
            }
            if ($('jform_params_sendfreq3').checked) {
                $('jform_params_sendtime').removeProperty('disabled');
                $('jform_params_sendday').setProperty('disabled', 'disabled');
                $('jform_params_senddate').removeProperty('disabled');
            }
		});
		";
		$doc->addScriptDeclaration($js);
//build array of times.
$times = array();
$time = strtotime("00:00:00");
$times["00:00:00"] = date("g:i a",$time);
for($i = 1;$i < 48;$i++) {
    $time = strtotime("+ 30 minutes",$time);
    $key = date("H:i:s",$time);
    $times[$key] = date("g:i a",$time);   
}
                $options = array();
                foreach($times as $time) 
                {
                        $options[] = JHtml::_('select.option', $time, $time);
                }
                $options = array_merge(parent::getOptions() , $options);
                return $options;
}
}