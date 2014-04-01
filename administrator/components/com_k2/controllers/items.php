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

class K2ControllerItems extends JController
{

	function display() {
		JRequest::setVar('view', 'items');
		parent::display();
	}

	function publish() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->publish();
	}

	function unpublish() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->unpublish();
	}

	function saveorder() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->saveorder();
	}

	function orderup() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->orderup();
	}

	function orderdown() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->orderdown();
	}

	function savefeaturedorder() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->savefeaturedorder();
	}

	function featuredorderup() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->featuredorderup();
	}

	function featuredorderdown() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->featuredorderdown();
	}

	function accessregistered() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->accessregistered();
	}

	function accessspecial() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->accessspecial();
	}

	function accesspublic() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->accesspublic();
	}

	function featured() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->featured();
	}

	function trash() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->trash();
	}

	function restore() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->restore();
	}

	function remove() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->remove();
	}

	function add() {
		global $mainframe;
		$mainframe->redirect('index.php?option=com_k2&view=item');
	}

	function edit() {
		global $mainframe;
		$cid = JRequest::getVar('cid');
		$mainframe->redirect('index.php?option=com_k2&view=item&cid='.$cid[0]);
	}

	function copy() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('items');
		$model->copy();
	}

	function element() {
		JRequest::setVar('view', 'items');
		JRequest::setVar('layout', 'element');
		parent::display();
	}
	
	function import(){
		$model = & $this->getModel('items');
		$model->import();
	}
	
	function move(){
		$view = & $this->getView('items', 'html');
		$view->setLayout('move');
		$view->move();
	}
	
	function saveMove(){
		$model = & $this->getModel('items');
		$model->move();
	}

}
