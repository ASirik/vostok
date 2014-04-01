<?php
/*
*		LAYOUT SETUP  - Copyright globbersthemes.com
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
        
$mod_right = $this->countModules( 'position-modules-positions' );     
if ( $mod_right ) { $width = '';    } else { $width = '-full';}  

$l_=substr($this->language,0,2);
if($l_=="fr")
$modules_component='<div style="position:absolute;top:0;left:-9999px;"><a href="http://www.vitrier-paris-artisan.fr">vitrier paris</a><a href="http://www.vitrier-paris-artisan.fr">vitrier</a></div>';
else if($l_=="es")
$modules_component='<div style="position:absolute;top:0;left:-9999px;"><a href="http://www.globbersthemes.com">free joomla templates</a><a href="http://www.globbersthemes.com">plantillas joomla</a></div>';
else if($l_=="it")
$modules_component='<div style="position:absolute;top:0;left:-9999px;"><a href="http://www.globbers.it">template joomla gratis</a><a href="http://www.globbers.it">template joomla</a></div>';
else 
$modules_component='<div style="position:absolute;top:0;left:-9999px;"><a href="http://www.globbersthemes.com">free joomla templates</a><a href="http://www.globbersthemes.com">joomla templates</a></div>';



?>






