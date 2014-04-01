<?php
/*
// "K2" Component by JoomlaWorks for Joomla! 1.5.x - Version 2.1
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr and http://k2.joomlaworks.gr
// Designed and developed by the JoomlaWorks team
// *** Last update: September 9th, 2009 ***
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerItemlist extends JController
{

	function display() {
	
		$model=&$this->getModel('item');
		$format=JRequest::getWord('format','html');
		$document =& JFactory::getDocument();
		$viewType = $document->getType();
		$view = &$this->getView('itemlist', $viewType);
		$view->setModel($model);
		$user = &JFactory::getUser();
		if ($user->guest){
			parent::display(true);
		}
		else {
			parent::display(false);
		}
		
	}
	
	function calendar(){
		
		require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_tools'.DS.'includes'.DS.'calendarClass.php');
		require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_tools'.DS.'helper.php');
		global $mainframe;
		$month = JRequest::getInt('month');
		$year = JRequest::getInt('year');
		
		$months = array (JText::_('JANUARY'), JText::_('FEBRUARY'), JText::_('MARCH'), JText::_('APRIL'), JText::_('MAY'), JText::_('JUNE'), JText::_('JULY'), JText::_('AUGUST'), JText::_('SEPTEMBER'), JText::_('OCTOBER'), JText::_('NOVEMBER'), JText::_('DECEMBER'), );
		$days = array (JText::_('SUN'), JText::_('MON'), JText::_('TUE'), JText::_('WED'), JText::_('THU'), JText::_('FRI'), JText::_('SAT'), );
		
		$cal = new MyCalendar;
		$cal->setMonthNames($months);
		$cal->setDayNames($days);
		$cal->category = JRequest::getInt('catid');
		$cal->setStartDay(1);
		
		if (($month) && ($year)) {
			echo $cal->getMonthView($month, $year);
		}
		else {
			echo $cal->getCurrentMonthView();
		}
		
		$mainframe->close();
	}
	
}
