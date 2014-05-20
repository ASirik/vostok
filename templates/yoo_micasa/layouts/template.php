<?php
/**
* @package   yoo_micasa
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get template configuration
include($this['path']->path('layouts:template.config.php'));
	
?>
<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>">

<head>
<?php echo $this['template']->render('head'); ?>
</head>

<body id="page" class="page <?php echo $this['config']->get('body_classes'); ?>" data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>

	<div id="page-bg">
		<div class="big-bg" style="height:300px;min-height: 1281px;"></div>
		<div>

			<?php if ($this['modules']->count('absolute')) : ?>
			<div id="absolute">
				<?php echo $this['modules']->render('absolute'); ?>
			</div>
			<?php endif; ?>
			
			<div class="wrapper grid-block">

				<header id="header" class="grid-block">

					<?php if ($this['modules']->count('toolbar-l + toolbar-r') || $this['config']->get('date')) : ?>
					<div id="toolbar" class="grid-block">

						<?php if ($this['modules']->count('toolbar-l') || $this['config']->get('date')) : ?>
						<div class="float-left">
						
							<?php if ($this['config']->get('date')) : ?>
							<time datetime="<?php echo $this['config']->get('datetime'); ?>"><?php echo $this['config']->get('actual_date'); ?></time>
							<?php endif; ?>
						
							<?php echo $this['modules']->render('toolbar-l'); ?>
							
						</div>
						<?php endif; ?>
							
						<?php if ($this['modules']->count('toolbar-r')) : ?>
						<div class="float-right"><?php echo $this['modules']->render('toolbar-r'); ?></div>
						<?php endif; ?>
						
					</div>
					<?php endif; ?>

					<?php if ($this['modules']->count('logo')) : ?>	
					<a id="logo" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['modules']->render('logo'); ?></a>
					<?php endif; ?>

					<?php if ($this['modules']->count('menu + search')) : ?>
					<div id="menubar" class="grid-block">
						
						<?php if ($this['modules']->count('menu')) : ?>
						<nav id="menu"><?php echo $this['modules']->render('menu'); ?></nav>
						<?php endif; ?>

						<?php if ($this['modules']->count('search')) : ?>
						<div id="search"><?php echo $this['modules']->render('search'); ?></div>
						<?php endif; ?>
						
					</div>
					<?php endif; ?>
				
					<?php if ($this['modules']->count('banner')) : ?>
					<div id="banner"><?php echo $this['modules']->render('banner'); ?></div>
					<?php endif; ?>
				
				</header>

				<?php if ($this['modules']->count('top-a')) : ?>
				<section id="top-a" class="grid-block"><?php echo $this['modules']->render('top-a', array('layout'=>$this['config']->get('top-a'))); ?></section>
				<?php endif; ?>
				
				<?php if ($this['modules']->count('top-b')) : ?>
				<section id="top-b" class="grid-block"><?php echo $this['modules']->render('top-b', array('layout'=>$this['config']->get('top-b'))); ?></section>
				<?php endif; ?>
				
				<?php if ($this['modules']->count('innertop + innerbottom + sidebar-a + sidebar-b') || $this['config']->get('system_output')) : ?>
				<div id="main" class="grid-block">
				
					<div id="maininner" class="grid-box">
					
						<?php if ($this['modules']->count('innertop')) : ?>
						<section id="innertop" class="grid-block"><?php echo $this['modules']->render('innertop', array('layout'=>$this['config']->get('innertop'))); ?></section>
						<?php endif; ?>

						<?php if ($this['modules']->count('breadcrumbs')) : ?>
						<section id="breadcrumbs"><?php echo $this['modules']->render('breadcrumbs'); ?></section>
						<?php endif; ?>

						<?php if ($this['config']->get('system_output')) : ?>
						<section id="content" class="grid-block"><?php echo $this['template']->render('content'); ?></section>
						<?php endif; ?>

						<?php if ($this['modules']->count('innerbottom')) : ?>
						<section id="innerbottom" class="grid-block"><?php echo $this['modules']->render('innerbottom', array('layout'=>$this['config']->get('innerbottom'))); ?></section>
						<?php endif; ?>

					</div>
					<!-- maininner end -->
					
					<?php if ($this['modules']->count('sidebar-a')) : ?>
					<aside id="sidebar-a" class="grid-box"><?php echo $this['modules']->render('sidebar-a', array('layout'=>'stack')); ?></aside>
					<?php endif; ?>
					
					<?php if ($this['modules']->count('sidebar-b')) : ?>
					<aside id="sidebar-b" class="grid-box"><?php echo $this['modules']->render('sidebar-b', array('layout'=>'stack')); ?></aside>
					<?php endif; ?>

				</div>
				<?php endif; ?>
				<!-- main end -->

				<?php if ($this['modules']->count('bottom-a')) : ?>
				<section id="bottom-a" class="grid-block"><?php echo $this['modules']->render('bottom-a', array('layout'=>$this['config']->get('bottom-a'))); ?></section>
				<?php endif; ?>
				
				<?php if ($this['modules']->count('bottom-b')) : ?>
				<section id="bottom-b" class="grid-block"><?php echo $this['modules']->render('bottom-b', array('layout'=>$this['config']->get('bottom-b'))); ?></section>
				<?php endif; ?>
				
				<?php if ($this['modules']->count('footer + debug') || $this['config']->get('warp_branding') || $this['config']->get('totop_scroller')) : ?>
				<footer id="footer" class="grid-block">

					<?php if ($this['config']->get('totop_scroller')) : ?>
					<a id="totop-scroller" href="#page"></a>
					<?php endif; ?>

					<?php
						echo $this['modules']->render('footer');
						//$this->output('warp_branding');
						echo $this['modules']->render('debug');
					?>

				</footer>
				<?php endif; ?>

			</div>
			
			<?php echo $this->render('footer'); ?>
            <!-- Yandex.Metrika informer -->
            <a href="https://metrika.yandex.ru/stat/?id=25034621&amp;from=informer"
               target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/25034621/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
                                                   style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:25034621,lang:'ru'});return false}catch(e){}"/></a>
            <!-- /Yandex.Metrika informer -->

            <!-- Yandex.Metrika counter -->
            <script type="text/javascript">
                (function (d, w, c) {
                    (w[c] = w[c] || []).push(function() {
                        try {
                            w.yaCounter25034621 = new Ya.Metrika({id:25034621,
                                webvisor:true,
                                clickmap:true,
                                trackLinks:true,
                                accurateTrackBounce:true});
                        } catch(e) { }
                    });

                    var n = d.getElementsByTagName("script")[0],
                        s = d.createElement("script"),
                        f = function () { n.parentNode.insertBefore(s, n); };
                    s.type = "text/javascript";
                    s.async = true;
                    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                    if (w.opera == "[object Opera]") {
                        d.addEventListener("DOMContentLoaded", f, false);
                    } else { f(); }
                })(document, window, "yandex_metrika_callbacks");
            </script>
            <noscript><div><img src="//mc.yandex.ru/watch/25034621" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
            <!-- /Yandex.Metrika counter -->
		</div>
	</div>

</body>
</html>