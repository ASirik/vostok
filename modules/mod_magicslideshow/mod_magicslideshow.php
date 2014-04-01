<?php

/*------------------------------------------------------------------------
# mod_magicslideshow - Magic Slideshow for Joomla
# ------------------------------------------------------------------------
# Magic Toolbox
# Copyright 2011 MagicToolbox.com. All Rights Reserved.
# @license - http://www.opensource.org/licenses/artistic-license-2.0  Artistic License 2.0 (GPL compatible)
# Website: http://www.magictoolbox.com/magicslideshow/modules/joomla/
# Technical Support: http://www.magictoolbox.com/contact/
/*-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access.');


    require_once(dirname(__FILE__) . "/magicslideshow.module.core.class.php");

    class modMagicSlideshow {
        var $params = Array();
        var $conf = Array();
        var $content = "";
        var $baseurl = "";
        var $page = "";
        var $coreClass = "";
        var $replaced = false;

        function modMagicSlideshow ($params) {
            $this->params = $params;

            //$this->baseurl = JURI::base() . '/modules/mod_magicslideshow/core';
            $this->baseurl = JURI::base() . 'modules/mod_magicslideshow/core';

            $coreClassName = 'MagicSlideshowModuleCoreClass';
            $this->coreClass = new $coreClassName;

            if(isset($_REQUEST["page"])) $this->page = trim($_REQUEST["page"]);

            $this->loadConf();

            $this->fixCSSfile();

            $this->registerEvent('onAfterRender', 'modMagicSlideshowLoad');
        }

        //for fix url's in css files
        function fixCSSfile() {
                if($this->params->get('fix_css_file')) {
                    $fileContents = file_get_contents(dirname(__FILE__) . '/core/magicslideshow.css');
                    $cssPath = "/" . preg_replace('/https?:\/\/[^\/]+\//is', '', JURI::base()) . 'modules/mod_magicslideshow/core';
                    $pattern = '/url\(\s*(?:\'|")?(?!'.preg_quote($cssPath, '/').')\/?([^\)\s]+?)(?:\'|")?\s*\)/is';
                    $replace = 'url(' . $cssPath . '/$1)';
                    $fixedFileContents = preg_replace($pattern, $replace, $fileContents);
                    if($fixedFileContents != $fileContents) {
                        file_put_contents(dirname(__FILE__) . '/core/magicslideshow.css', $fixedFileContents);
                    }
                    $this->params->set('fix_css_file', 0);
                    $db =& JFactory::getDBO();
                    $db->setQuery("SELECT params FROM #__modules WHERE module='mod_magicslideshow'");
                    $row = $db->loadObject();
                    //1.5 - 'fix_css_file=1'
                    //1.6 - '"fix_css_file":"1"'
                    $row->params = preg_replace('/("?fix_css_file(?:":"|=))1/s', '${1}0', $row->params);
                    $query = "UPDATE #__modules SET params=".$db->quote($row->params)." WHERE module='mod_magicslideshow'";
                    $db->setQuery($query);
                    $db->query();
                }
        }

        function registerEvent($event,$handler) {
            /* can't use $mainframe->registerEvent function when System.Cache plugin activated */
            $dispatcher =& JDispatcher::getInstance();
            if(class_exists('joomlaVersion')) {
                //old joomla, 1.0.x
                $versionObj = new joomlaVersion();
            } elseif(class_exists('JVersion')) {
                $versionObj = new JVersion();
            }
            if(version_compare($versionObj->getShortVersion(), '1.6.0', '<')) {
                $obs = Array("event" => $event, "handler" => $handler);
                $dispatcher->_observers = array_merge(Array($obs), $dispatcher->_observers);
            } else {
                $dispatcher->register($event,$handler);
            }
        }

        function loadConf() {

            $this->conf = & $this->coreClass->params;

            foreach($this->conf->getArray() as $key => $c) {
                $value = $this->params->get($key, "__default__");
                if($c["type"] == 'text' && $value == 'none') $value = "";
                if($value !== "__default__") {
                    $this->conf->set($key, $value);
                }
            }

            // for MT module
            $this->conf->set('caption-source', 'Title');

            // for MM and MMP modules (centered thumbnails)
            $this->conf->set('containerDisplay', 'inline-block');


        }

        function load() {

            $this->content = JResponse::toString();


            $this->content = preg_replace_callback('/<(p|div)([^>]*?class="[^"]*?MagicSlideshow[^"]*?"[^>]*)>(.*?)<\/\1>/is', array(&$this,"loadIMGCallback"), $this->content);

            /* load JS and CSS */
            if($this->replaced && !defined('MagicSlideshow_HEADERS_LOADED')) {
                $pattern = '/<\/head>/is';
                $this->content = preg_replace_callback($pattern, array(&$this,"loadJSCSSCallback"), $this->content, 1);
                define('MagicSlideshow_HEADERS_LOADED', true);
            }

            JResponse::setBody($this->content);
        }

        function loadJSCSSCallback($matches) {
            return $this->coreClass->headers($this->baseurl) . $matches[0];
        }

        function loadIMGCallback($matches) {


            $html = "<div{$matches[2]}>{$matches[3]}</div>";

            $this->replaced = true;

            return $html;
        }
    }

    $GLOBALS["magictoolbox"]["magicslideshow"] = new modMagicSlideshow($params);

    function modMagicSlideshowLoad() {
        $GLOBALS["magictoolbox"]["magicslideshow"]->load();
    }

?>
