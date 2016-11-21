<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php storefront_html_tag_schema(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Skype C2C fix -->
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/perfect-scrollbar.min.css" media="all">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/less/bootstrap/bootstrap.css" media="all">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/less/styles.css" media="all">
</head>
<body <?php body_class(); ?>>
	<?php
	do_action( 'storefront_before_header' ); ?>
	<header id="masthead" class="site-header" style="position: relative;" role="banner">
		<div class="container">
			<?php
				/**
				 * @hooked storefront_mobile_navigation - 10
				 * @hooked storefront_site_branding - 15
				 * @hooked storefront_right_phone - 25
				 * @hooked storefront_site_languages - 30
				 * @hooked storefront_basket_checkout - 35
				 */
				do_action( 'storefront_header' ); ?>
		</div>
		<div class="site-header-nav">
			<div class="container">
				<?php
				/**
				 * @hooked storefront_desktop_navigation - 10
				 * @hooked storefront_signin_signout_umenu - 15
				 */
				do_action( 'storefront_header_ex' ); ?>
			</div>
		</div>
	</header>