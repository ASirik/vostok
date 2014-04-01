<?php

defined('JPATH_BASE') or die;

// Required objects
$app 	= JFactory::getApplication();
$doc 	= JFactory::getDocument();
$jinput = $app->input;

// Current Itemid
$itemId = (int)$jinput->get('Itemid',0);

/**
 * ==================================================
 * Frontpage check
 * ==================================================
 */
$isFrontpage = false;
$menu = JSite::getMenu();
if ($menu->getActive() == $menu->getDefault())
{
	$isFrontpage = true;
}


/**
 * ==================================================
 * Bootstrap
 * ==================================================
 */
// Load configuration
$bsMode 		= $this->params->get( 'bsMode','' );
$loadBootstrap 	= $this->params->get('loadBootstrap',1);

// Calculate mainColumns
$mainColumnWidth 	= 12;
$leftColumnWidth 	= $this->params->get( 'leftColumnWidth',3 );
$rightColumnWidth 	= $this->params->get( 'rightColumnWidth',3 );
if ($this->countModules('left'))
{
    $mainColumnWidth -= $leftColumnWidth;
}
if ($this->countModules('right'))
{
    $mainColumnWidth -= $rightColumnWidth;
}

/**
 * ==================================================
 * Google fonts
 * ==================================================
 */
$gfontsCssRequired = false;
$gfontsCssLink = 'http://fonts.googleapis.com/css?family=';
$gfontsCss = "\n<style type='text/css'>
    \n<!--
    ";
for ($i = 1; $i < 5; $i++) {

    // generate variable for google font value
    $gfontValue = 'gfontValue'.$i;
    $$gfontValue = $this->params->get( 'google-font'.$i, '' );

    // generate variable for google font name
    $gfontName = 'gfontName' . $i;
    $$gfontName = str_replace('+', ' ', $$gfontValue);

    // get the css class assigned to the google font
    $gfontCssClass = $this->params->get( 'google-font'.$i.'-class', '' );

    // user has selected a google font and set a class name add the css class
    if ($$gfontName != '' && !empty($gfontCssClass))
    {
        if (!$gfontsCssRequired )
        {
            $gfontsCssRequired = true;
        }
        else
        {
            $gfontsCssLink .= '|';
        }
        $gfontsCssLink .= $$gfontValue;
        $gfontsCss .= "\n" . $this->params->get( 'google-font'.$i.'-class' ) . " { font-family:'".$$gfontName."' !important; " . $this->params->get( 'google-font'.$i.'-css' )."}";
    }
}
$gfontsCss .= "\n -->
    \n</style>
";

// if no active google fonts avoid empty styles
if (!$gfontsCssRequired) {
    $gfontsCssLink = null;
    $gfontsCss = null;
}

/**
 * ==================================================
 * Load jQuery
 * ==================================================
 */
$loadJquery = $this->params->get('loadJquery',0);
if ($loadJquery)
{
	switch ($loadJquery) {
		// load jQuery locally
		case 1:
			$jquerySrc = $this->baseurl . "/templates/" . $this->template . "/js/jquery-1.7.2.min.js";
			break;
			// load jQuery from Google
		default:
			$jquerySrc = 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
		break;
	}
}

/**
 * ==================================================
 * Load customCss
 * ==================================================
 */
$customCssFile = JPATH_SITE . '/templates/' . $this->template . '/css/custom.css';
if (file_exists($customCssFile))
{
	$customCssFile = $this->baseurl . '/templates/' . $this->template . '/css/custom.css';
}
else
{
	$customCssFile = null;
}

/**
 * ==================================================
 * Disable mootools-more except for selected itemids to solve bootstrap conflicts
 * ==================================================
 */
$enabledMootoolsString = $this->params->get('mootoolsItemids','');
if ($itemId != 0 && $enabledMootoolsString != '')
{
	$enabledItemids = explode(',', $enabledMootoolsString);
	if ($enabledItemids)
	{
		// If the itemId isn't in items that need mootools disable mootools
		if (!in_array($itemId, $enabledItemids))
		{
			// Disable mootools javascript
			unset($doc->_scripts[$this->baseurl . '/media/system/js/mootools-core.js']);
			unset($doc->_scripts[$this->baseurl . '/media/system/js/mootools-more.js']);
			unset($doc->_scripts[$this->baseurl . '/media/system/js/core.js']);
			unset($doc->_scripts[$this->baseurl . '/media/system/js/caption.js']);
			unset($doc->_scripts[$this->baseurl . '/media/system/js/modal.js']);
			unset($doc->_scripts[$this->baseurl . '/media/system/js/mootools.js']);
			unset($doc->_scripts[$this->baseurl . '/plugins/system/mtupgrade/mootools.js']);

			// Disable css stylesheets
			unset($doc->_styleSheets[$this->baseurl . '/media/system/css/modal.css']);
		}
	}
}

?>