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

class TableK2Attachment extends JTable {

	var $id = null;
	var $itemID = null;
	var $filename = null;
	var $title = null;
	var $titleAttribute = null;
	var $hits = null;

	function __construct( & $db) {
		parent::__construct('#__k2_attachments', 'id', $db);
	}

}
