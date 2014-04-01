<?php
/**
* @version 1.5.x
* @package JoomVision Project
* @email webmaster@joomvision.com
* @copyright (C) 2008 http://www.JoomVision.com. All rights reserved.
*/

///-- DEFINE --///
defined( '_JEXEC' ) or die( 'Restricted access' );
define ('_TEMPLATE_URL', JURI::base().'templates/'.$this->template.'/');
define ('_BASE_URL', JURI::base());
define ('_TEMPLATE_PATH', $this->baseurl.'templates/'.$this->template.'/');
define ('_BASE_PATH', JPATH_SITE.'/');
$jvconfig = new JConfig();
define ('_SITE_NAME',$jvconfig->sitename);
$menustyle = $this->params->get('jv_menustyle');
///-----------------------------------------------------///

///-- Behavior --///
JHTML::_('behavior.mootools');

///-- VARIABLES --///
include_once('jv_menus/jv.common.php');
$default_menu = $this->params->get('menutype');
if (!$default_menu) $default_menu = 'mainmenu';
$menu = new MenuSystem($menustyle,$default_menu,$this->template); 

// GZIP Compression
$gzip  = ($this->params->get('gzip', 1)  == 0)?"false":"true";
// Show tools
$showTools = $this->params->get('showtools',1);

$jvTools = new JV_Tools($this);
///-----------------------------------------------------/// 

///-- Auto Collapse Divs Functions --///
$modLeft = $this->countModules('left');
$modRight = $this->countModules('right');

if($modLeft) {
	$div_content = "74%";
	$float = "right";
} else {
	$div_content = "100%";
	$float = "none";
}
if($modRight) {
	$div_content_in = "66%";
	$float_in = "left";
} else {
	$div_content_in = "100%";
	$float_in = "none";
}
/**/
$front = ($jvTools->isHomePage()) ? true : false;
/**
 * Class JV_Tools
 */
class JV_Tools {
	var $_tpl = null;
	var $template = '';
	var $_params_cookie = array();
	
	
	function JV_Tools ($template, $_params_cookie = null) {
		$this->_tpl = $template;
		$this->template = $template->template;
		if(!$_params_cookie) {
			$_params_cookie = array();
		}

		if ($this->getParam('jv_font') && !in_array($this->getParam('jv_font'), $_params_cookie)) {
			$_params_cookie[]= 'jv_font';
		}
		if ($this->getParam('jv_menustyle') && !in_array($this->getParam('jv_menustyle'), $_params_cookie)) {
			$_params_cookie[]= 'jv_menustyle';
		}
		foreach ($_params_cookie as $k) {
			$this->_params_cookie[$k] = $this->_tpl->params->get($k);
		}
		$this->getUserSetting();
	}
	function getUserSetting(){
		$exp = time() + 60*60*24*355;
		if (isset($_COOKIE[$this->template.'_tpl']) && $_COOKIE[$this->template.'_tpl'] == $this->template){
			foreach($this->_params_cookie as $k=>$v) {
				$kc = $this->template."_".$k;
				if (isset($_GET[$k])){
					$v = $_GET[$k];
					setcookie ($kc, $v, $exp, '/');
				}else{
					if (isset($_COOKIE[$kc])){
						$v = $_COOKIE[$kc];
					}
				}
				$this->setParam($k, $v);
			}

		}else{
			@setcookie ($this->template.'_tpl', $this->template, $exp, '/');
		}
		return $this;
	}

	function getParam ($param, $default='') {
		if (isset($this->_params_cookie[$param])) {
			return $this->_params_cookie[$param];
		}
		return $this->_tpl->params->get($param, $default);
	}

	function setParam ($param, $value) {
		$this->_params_cookie[$param] = $value;
	}

	/**
	 * Get Current URL
	 *
	 * @return URL string
	 */
	function getCurrentURL(){
		$cururl = JRequest::getURI();
		if(($pos = strpos($cururl, "index.php"))!== false){
			$cururl = substr($cururl,$pos);
		}
		$cururl =  JRoute::_($cururl, true, 0);
		return $cururl;
	}

	function getChangeFont ($jv_tools) {
?>		
		<img style="cursor: pointer;" src="<?php echo _TEMPLATE_URL; ?>images/a-minus.png" onclick="switchFontSize('<?php echo $this->template."_jv_font";?>','dec'); return false;" alt="" />
		<img style="cursor: pointer;" src="<?php echo _TEMPLATE_URL; ?>images/a-def.png" onclick="switchFontSize('<?php echo $this->template."_jv_font";?>',<?php echo $this->_tpl->params->get('jv_font');?>); return false;" alt="" />
		<img style="cursor: pointer;" src="<?php echo _TEMPLATE_URL; ?>images/a-plus.png" onclick="switchFontSize('<?php echo $this->template."_jv_font";?>','inc'); return false;" alt="" />
        <img style="cursor: pointer;" id="s1" src="<?php echo _TEMPLATE_URL; ?>images/neon.png" alt="Neon color" />	
  		<img style="cursor: pointer;" id="s2" src="<?php echo _TEMPLATE_URL; ?>images/laser.png" alt="Laser color" />
        <img style="cursor: pointer;" id="s3" src="<?php echo _TEMPLATE_URL; ?>images/fable.png" alt="Fable color" />
        <img style="cursor: pointer;" id="s4" src="<?php echo _TEMPLATE_URL; ?>images/orange.png" alt="Orange color" />
        <img style="cursor: pointer;" id="s5" src="<?php echo _TEMPLATE_URL; ?>images/grass.png" alt="Grass color" />
  		<script type="text/javascript">var CurrentFontSize=parseInt('<?php echo $this->getParam('jv_font');?>');</script>
<?php
	}

	function calSpotlight ($spotlight, $totalwidth=100, $firstwidth=0) {

		/********************************************
		$spotlight = array ('position1', 'position2',...)
		*********************************************/
		$modules = array();
		$modules_s = array();
		foreach ($spotlight as $position) {
			if( $this->_tpl->countModules ($position) ){
				$modules_s[] = $position;
			}
			$modules[$position] = array('class'=>'-full', 'width'=>$totalwidth);
		}

		if (!count($modules_s)) return null;

		if ($firstwidth) {
			if (count($modules_s)>1) {
				$width = round(($totalwidth-$firstwidth)/(count($modules_s)-1),1) . "%";
				$firstwidth = $firstwidth . "%";
			}else{
				$firstwidth = $totalwidth . "%";
			}
		}else{
			$width = round($totalwidth/(count($modules_s)),1) . "%";
			$firstwidth = $width;
		}

		if (count ($modules_s) > 1){
			$modules[$modules_s[0]]['class'] = "-left";
			$modules[$modules_s[0]]['width'] = $firstwidth;
			$modules[$modules_s[count ($modules_s) - 1]]['class'] = "-right";
			$modules[$modules_s[count ($modules_s) - 1]]['width'] = $width;
			for ($i=1; $i<count ($modules_s) - 1; $i++){
				$modules[$modules_s[$i]]['class'] = "-center";
				$modules[$modules_s[$i]]['width'] = $width;
			}
		}
		return $modules;
	}
	
	/*
	 * @return boolean
	 */
	function isIE6 () {
		return $this->browser() == 'IE6';
	}
	/*
	 * @return boolean
	 */
	
	function isOP () {
		return isset($_SERVER['HTTP_USER_AGENT']) &&
			preg_match('/opera/i',$_SERVER['HTTP_USER_AGENT']);
	}
	/**
	 * @return boolean
	 */
	function isHomePage(){
		  $db  = & JFactory::getDBO();  
		  $db->setQuery("SELECT home FROM #__menu WHERE id=" . JRequest::getCmd( 'Itemid' ));
		  $db->query();
		  return  $db->loadResult();
	 }
	/*
	 * @return browser string
	 */
	 
	function browser () {
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if ( strpos($agent, 'Gecko') )
		{
		   if ( strpos($agent, 'Netscape') )
		   {
		     $browser = 'NS';
		   }
		   else if ( strpos($agent, 'Firefox') )
		   {
		     $browser = 'FF';
		   }
		   else
		   {
		     $browser = 'Moz';
		   }
		}
		else if ( strpos($agent, 'MSIE') && !preg_match('/opera/i',$agent) )
		{
			 $msie='/msie\s(7\.[0-9]).*(win)/i';
		   	 if (preg_match($msie,$agent)) $browser = 'IE7';
		   	 else $browser = 'IE6';
		}
		else if ( preg_match('/opera/i',$agent) )
		{
		     $browser = 'OPE';
		}
		else
		{
		   $browser = 'Others';
		}
		return $browser;
	}

	function get_cookie($item) {
		if (isset($_COOKIE[$item]))
			return urldecode($_COOKIE[$item]);
		else
			return false;
	}
	function parse_jvcolor_cookie() {
		$_tpl_cookie =  $this->get_cookie('StyleCookieSite');
		$_tpl_cookie = str_replace("\\","",$_tpl_cookie);
		if($_tpl_cookie) {
			$result =  substr($_tpl_cookie,strrpos($_tpl_cookie, '":"')+3,strlen($_tpl_cookie));
			$result =  substr($result, 0, strlen($result)-2);
		}
		elseif($_tpl_cookie == false) {
			$result = _TEMPLATE_URL . 'css/colors/' . $this->getParam('jv_color') . '.css';
		}
		return stripslashes($result);
	}
}
?>