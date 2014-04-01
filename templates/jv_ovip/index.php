<?php
/**
* @version 1.5.x
* @package JoomVision Project
* @email webmaster@joomvision.com
* @copyright (C) 2009 http://www.JoomVision.com. All rights reserved.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
include_once (dirname(__FILE__).DS.'jv_tools.php');

// Javascript
    unset($this->_scripts[$this->baseurl . '/media/system/js/mootools.js']);
    unset($this->_scripts[$this->baseurl . '/media/system/js/caption.js']);
    
    if($gzip == "true") :
    $this->_scripts = array_merge(array(_TEMPLATE_URL . 'js/jv.script.js.php' => 'text/javascript'), $this->_scripts);
     else:
	$this->_scripts = array_merge(array(_TEMPLATE_URL . 'js/jv.collapse.js' => 'text/javascript'), $this->_scripts);
	$this->_scripts = array_merge(array(_TEMPLATE_URL . 'js/jv.script.js' => 'text/javascript'), $this->_scripts);
    $this->_scripts = array_merge(array(_TEMPLATE_URL . 'js/mootools.js' => 'text/javascript'), $this->_scripts);
    endif;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo _BASE_URL; ?>templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo _BASE_URL; ?>templates/system/css/general.css" type="text/css" />
<?php if($gzip == "true") : ?>
    <link rel="stylesheet" href="<?php echo _TEMPLATE_URL; ?>css/template.css.php" type="text/css" />
<?php else: ?>
    <link rel="stylesheet" href="<?php echo _TEMPLATE_URL; ?>css/default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo _TEMPLATE_URL; ?>css/template.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo _TEMPLATE_URL; ?>css/typo.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo _TEMPLATE_URL; ?>css/k2.css" type="text/css" />
<?php endif; ?>

<link rel="stylesheet" href="<?php echo $jvTools->parse_jvcolor_cookie(); ?>" type="text/css" />
<script type="text/javascript">
	var baseurl = "<?php echo _BASE_URL; ?>";
	var pathcolor = '<?php echo _TEMPLATE_URL; ?>css/colors/';
	var tmplurl = '<?php echo _TEMPLATE_URL;?>';
	if (typeof(jQuery) != 'undefined') {
     	jQuery.noConflict();
	}
</script>

<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php echo _TEMPLATE_URL; ?>css/ie7.css" type="text/css" />
<![endif]-->

<!--[if lte IE 6]>
<link rel="stylesheet" href="<?php echo _TEMPLATE_URL; ?>css/ie6.css" type="text/css" />
<script type="text/javascript" src="<?php echo _TEMPLATE_URL ?>js/ie_png.js"></script>
<script type="text/javascript">

window.addEvent ('load', function() {
	ie_png.fix('.png');
    fixIEPNG($$('img'));
});

</script>
<![endif]-->
</head>

<body id="bd" class="font<?php echo $this->params->get('jv_font'); ?> png" >
<div id="jv-wrapper" class="<?php echo $jvclass = $front? "" : "jv-wrapper"; ?>">
	<div id="jv-wrapper-pad" class="png">
<!-- BEGIN HEADER -->
	<div id="jv-headerwrap">
    	<div id="jv-header">
        	<a id="jv-logo" class="png" href="<?php echo JURI::base(); ?>" title="<?php echo _SITE_NAME; ?>"><img src="<?php echo _TEMPLATE_URL; ?>images/blank.gif" alt="<?php echo _SITE_NAME; ?>" /></a>
        	<div id="jv-menuwrap">
            	<div id="jv-menu-tr" class="png">
                    <div id="jv-menu-tl" class="png">
                    </div>
                </div>
                <div id="jv-menu-br" class="png">
                	<div id="jv-menu-bl" class="png">
                    	<?php $menu->show(); ?>
                    </div>
                </div>
            </div>
            
            <?php if($this->countModules('user4')) : ?>
            <div id="jv-search" class="png">
            	<jdoc:include type="modules" name="user4" />
            </div>
            <?php endif; ?>
            <?php if($showTools) : ?>
            <div id="jv-tools">
            	
            </div>
            <?php endif; ?>
        </div>
    </div>
<!-- END HEADER -->

<!-- BEGIN CONTAINER --> 
	<div id="jv-containerwrap">
    	<div id="jv-container">
        
        	<?php if($modLeft) : ?>
        	<div id="jv-colleft">
            	<div id="jv-colleft-inner">
                	<jdoc:include type="modules" name="left" style="jvxhtml" />
                </div>
            </div>
            <?php endif; ?>
            
            <div id="jv-content" style="float: <?php echo $float; ?>; width: <?php echo $div_content; ?>;">
            	<div id="jv-content-inner">
                
					<?php if($this->countModules('slide')) : ?>
                    <div id="jv-slide">
                        <jdoc:include type="modules" name="slide" />
                    </div>
                    <?php endif; ?>
                    <div id="jv-content-pad">
                    	<div id="jv-conponent" style="float: <?php echo $float_in; ?>; width: <?php echo $div_content_in; ?>;">
                        	<div id="jv-component-inner">
                                <jdoc:include type="message" />
                                <jdoc:include type="component" />
                            </div>
                        </div>
                        <?php if($modRight) : ?>
                        <div id="jv-colright">
                            <div id="jv-colright-inner">
                                <jdoc:include type="modules" name="right" style="jvxhtml" />
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- BEGIN: User5, User6, User7, User8 -->
                    <?php
                        $spotlight = array ('user5','user6', 'user7','user8');
                        $botsl = $jvTools->calSpotlight ($spotlight,$jvTools->isOP()?100:99.9);
                        if( $botsl ) :
                    ?>
                    <div id="jv-box-wrap" class="clearfix">
                       <?php if( $this->countModules('user5') ): ?>
                        <div class="jv-box<?php echo $botsl['user5']['class']; ?>" style="width: <?php echo $botsl['user5']['width']; ?>;">
                            <jdoc:include type="modules" name="user5" style="jvxhtml" />
                        </div>
                      <?php endif; ?>
                      <?php if( $this->countModules('user6') ): ?>
                        <div class="jv-box<?php echo $botsl['user6']['class']; ?>" style="width: <?php echo $botsl['user6']['width']; ?>;">
                            <jdoc:include type="modules" name="user6" style="jvxhtml" />
                        </div>
                      <?php endif; ?>
                    
                      <?php if( $this->countModules('user7') ): ?>
                        <div class="jv-box<?php echo $botsl['user7']['class']; ?>" style="width: <?php echo $botsl['user7']['width']; ?>;">
                            <jdoc:include type="modules" name="user7" style="jvxhtml" />
                        </div>
                      <?php endif; ?>
                      
                      <?php if( $this->countModules('user8') ): ?>
                        <div class="jv-box<?php echo $botsl['user8']['class']; ?>" style="width: <?php echo $botsl['user8']['width']; ?>;">
                            <jdoc:include type="modules" name="user8" style="jvxhtml" />
                        </div>
                      <?php endif; ?>
                      
                    </div>
                    <?php endif; ?>
                    <!-- END: User5, User6, User7, User8 -->
                    
                    <?php if($this->countModules('banner')) : ?>
                    <div id="jv-banner">
                        <jdoc:include type="modules" name="banner" />
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>	
	</div>
	<!-- END MAIN CONTENT -->

<!-- FOOTER -->
    <div id="jv-footerwrap">
        
        <div id="jv-footer">
        	<div id="jv-footernav">
                 <jdoc:include type="modules" name="user3" />
            </div>
            <!-- Begin Copyright -->
           <!-- <div class="jv-copyright"><p>Copyright &copy; 2008 - 2009 <a title="Visit JoomVision.Com !" target="_blank" href="http://www.qoodo.ru">JoomVision.Com.</a> All rights reserved. <a href="http://www.qoodo.ru" title="Joomla! Templates"> Professional Joomla Templates Club</a> - <a href="http://www.qoodo.ru" title="Joomla! Extensions">Professional Joomla Extensions Club</a></p></div>-->
            <!-- End copyright -->
        </div>
        
        
	</div>
<!-- END FOOTER -->
    </div>
</div>
<?php if($jvTools->browser() == "IE6" && $jvTools->isHomePage()) : ?>
<!--[if lte IE 6]>
<div id="jv-ie6">
	<?php include_once (dirname(__FILE__).DS.'/ie6.php'); ?>
</div>
<![endif]-->
<?php endif; ?>
<jdoc:include type="modules" name="debug" />
</body>
</html>

