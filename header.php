<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="mh-wrapper">
<header class="mh-header">
	<div class="header-wrap clearfix">
		<?php mh_newsdesk_lite_logo(); ?>
	</div>
	<div class="header-menu clearfix">
		<nav class="main-nav clearfix">
			<div id="testcenter">
			<?php wp_nav_menu(array('theme_location' => 'main_nav')); ?>
			</div>
		</nav>
	</div>
</header>