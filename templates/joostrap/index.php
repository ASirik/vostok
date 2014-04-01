<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

// Load template logic
include_once (dirname(__FILE__) . '/functions/tpl-init.php');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<?php if ($loadJquery): ?>
    <script src="<?php echo $jquerySrc; ?>"></script>
	<script type="text/javascript">jQuery.noConflict();</script>
<?php endif; ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php if ($loadBootstrap): ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bootstrap-responsive.min.css" type="text/css" media="screen" />
	
<?php endif; ?>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/joomla.css" type="text/css" media="screen" />
<?php if ($customCssFile): ?>
	<link rel="stylesheet" href="<?php echo $customCssFile; ?>" type="text/css" media="screen" />
<?php endif; ?>

<link rel="apple-touch-icon" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/icons/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/icons/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/iconsapple-touch-icon-114x114.png">

<?php if ($gfontsCssRequired): ?>
    <link href='<?php echo $gfontsCssLink; ?>' rel='stylesheet' type='text/css'>
    <?php // user google custom css styles
    echo $gfontsCss;
    ?>
<?php endif; ?>

<jdoc:include type="head" />

<script type="text/javascript">
window.addEvent('load', function() {
				new JCaption('img.caption');
			});
</script>

</head>
<body <?php if ($itemId): ?> id="item<?php echo $itemId; ?>" <?php endif; ?> class="<?php if ($isFrontpage): ?>frontpage <?php endif; ?>">
<!-- Navbar -->
<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<?php if($this->countModules('top-nav')) : ?>
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php endif; ?>
			<div class="search visible-desktop">
				<jdoc:include type="modules" name="search" style="none" />
			</div>
			<?php if($this->countModules('top-nav')) : ?>
			<div class="nav-collapse">
				<jdoc:include type="modules" name="top-nav" style="none" />
				<jdoc:include type="modules" name="login" style="navbar" />
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="container-fluid">
    <?php if(($this->countModules('header') || $this->countModules('sub-nav'))) : ?>
    <!-- Header -->
    <div id="header">

    	<header>
    			<?php if($this->countModules('header')) : ?>
    			<jdoc:include type="modules" name="header" />
    			<?php endif; ?>
    			<?php if($this->countModules('sub-nav')) : ?>
    			<div class="subnav">
    				<jdoc:include type="modules" name="sub-nav" />
    			</div>
    			<?php endif; ?>
    	</header>
    	</div>

    <?php endif; ?>

    <?php if($this->countModules('above')) : ?>
    <!-- Above Module Position -->
    <div id="above">
    	<div class="container<?php echo $bsMode; ?>">
    		<div class="row<?php echo $bsMode; ?>">
    			<jdoc:include type="modules" name="above" />
    		</div>
    	</div>
    </div>
    <?php endif; ?>
	<!-- Content -->
		<?php if($this->countModules('top')) : ?>
		<!-- Top Module Position -->
		<div id="top" class="row<?php echo $bsMode; ?>">
			<jdoc:include type="modules" name="top" />
		</div>
		<?php endif; ?>
		<jdoc:include type="message" />

		<?php if($this->countModules('breadcrumbs')) : ?>
		<!-- Breadcrumb Module Position -->
		<div id="breadcrumbs" class="row<?php echo $bsMode; ?>">
			<jdoc:include type="modules" name="breadcrumbs" />
		</div>
		<?php endif; ?>
		  <!-- Search Module Position -->
			<div class="search hidden-desktop">
				<jdoc:include type="modules" name="search" />
			</div>

		<div id="main" class="row<?php echo $bsMode; ?>">
			<?php if($this->countModules('left')) : ?>
			<!-- Left Module Position -->
			<div id="sidebar" class="span<?php echo $leftColumnWidth; ?>">
				<jdoc:include type="modules" name="left" style="xhtml" />
			</div>
			<?php endif; ?>
			<!-- Component -->
			<div id="content" class="span<?php echo $mainColumnWidth; ?>">
				<?php if($this->countModules('above-content')) : ?>
				<!-- Above Content Module Position -->
				<div id="above-content" class="row<?php echo $bsMode; ?>">
					<jdoc:include type="modules" name="above-content" />
				</div>
				<hr />
				<?php endif; ?>
				<jdoc:include type="component" />
				<?php if($this->countModules('below-content')) : ?>
				<!-- Below Content Module Position -->
				<hr />
				<div id="below-content" class="row<?php echo $bsMode; ?>">
					<jdoc:include type="modules" name="below-content" />
				</div>
				<?php endif; ?>
			</div>
    		<?php if($this->countModules('right')) : ?>
    	    <!-- Right Module Position -->
    		<div id="sidebar-2" class="span<?php echo $rightColumnWidth; ?>">
    			<jdoc:include type="modules" name="right" style="xhtml" />
    		</div>
    		<?php endif; ?>
		</div>


		<?php if($this->countModules('bottom')) : ?>
		<!-- Bottom Module Position -->
		<div id="bottom" class="row<?php echo $bsMode; ?>">
			<jdoc:include type="modules" name="bottom" />
		</div>
		<?php endif; ?>

	<?php if($this->countModules('below')) : ?>
	<!-- Below Module Position -->
	<div id="below" class="row<?php echo $bsMode; ?>">
		<jdoc:include type="modules" name="below" />
	</div>
	<?php endif; ?>
	<!-- Footer Module Position -->
	<footer class="footer row-fluid">
		<jdoc:include type="modules" name="footer" />
	</footer>
	<jdoc:include type="modules" name="debug" />
</div>
<?php if ($loadBootstrap): ?>
	<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/bootstrap.min.js"></script>
<?php endif; ?>
</body>
</html>