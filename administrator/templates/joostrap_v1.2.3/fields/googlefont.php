<?php
/**
 * @copyright	Copyright (C) 2012 Digital Disseny, S.L. All rights reserved.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldGooglefont extends JFormFieldList {

	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Googlefont';
	
	// Google API key
	const GOOGLE_API_KEY = 'AIzaSyDCuX9p0CoInlBnfj_YmrtxFizvQ5sT1Cs';

    /**
     * Generate dropdown options
     */
	public function getOptions()
	{
		// Initialize variables.
		$options = array();
		
		$jsonResponse = @file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key=' . self::GOOGLE_API_KEY);
		if ($jsonResponse) {
		    $decodedResponse = json_decode($jsonResponse);
		    if (isset($decodedResponse->items)) {
		        $options[] = JHtml::_('select.option', '', '- Select font -');
		        foreach ($decodedResponse->items as $font) {
		            // replace spaces with + in option value
		            $value = str_replace(' ', '+', $font->family);
		            $options[] = JHtml::_('select.option', $value, $font->family);
		        }
		    } else{
		        $options[] = JHtml::_('select.option', '', 'ERROR: None or bad JSON received');
		    }
		} else {
		    $options[] = JHtml::_('select.option', '', 'ERROR: Wrong URL or google API KEY?');
		}
        
		return $options;
	}

}