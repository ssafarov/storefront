<?php
/**
 * storefront hooks
 *
 * @package storefront
 */

/**
 * General
 * @see  storefront_setup()
 * @see  storefront_widgets_init()
 * @see  storefront_scripts()
 * @see  storefront_header_widget_region()
 * @see  storefront_get_sidebar()
 */
add_action( 'after_setup_theme',			'storefront_setup' );
add_action( 'widgets_init',					'storefront_widgets_init' );
add_action( 'wp_enqueue_scripts',			'storefront_scripts',				10 );
add_action( 'storefront_before_content',	'storefront_header_widget_region',	10 );
add_action( 'storefront_sidebar',			'storefront_get_sidebar',			10 );

/**
 * Header
 * Redesigned
 */
add_action( 'storefront_header', 'storefront_div_row_ml10r0_start',	1 );
add_action( 'storefront_header', 'storefront_div_end',			    2 );

add_action( 'storefront_header', 'storefront_div_row_ml10r0_start',	5 );
add_action( 'storefront_header', 'storefront_mobile_navigation',    10 );
add_action( 'storefront_header', 'storefront_site_branding',	    15 );
add_action( 'storefront_header', 'storefront_right_nav_control_start',	20 );
add_action( 'storefront_header', 'storefront_right_phone',	25 );
add_action( 'storefront_header', 'storefront_language_selector',	30 );
add_action( 'storefront_header', 'storefront_currency_selector',	31 );
add_action( 'storefront_header', 'storefront_basket_checkout',	35 );
add_action( 'storefront_header', 'storefront_div_end',	37 );
add_action( 'storefront_header', 'storefront_div_end',	38 );
add_action( 'storefront_header', 'storefront_div_end',	39 );

add_action( 'storefront_header', 'storefront_div_row_ml10r0_start',		41 );
add_action( 'storefront_header', 'storefront_div_end',			42 );

add_action( 'storefront_header_ex', 'storefront_desktop_navigation',		10 );
add_action( 'storefront_header_ex', 'storefront_signin_signout_umenu',	    15 );

/**
 * Footer
 * @see  storefront_footer_widgets()
 * @see  storefront_credit()
 */
add_action( 'storefront_footer', 'storefront_footer_widgets',	10 );
// add_action( 'storefront_footer', 'storefront_credit',			20 );

/**
 * Homepage
 * @see  storefront_homepage_content()
 * @see  storefront_product_categories()
 * @see  storefront_recent_products()
 * @see  storefront_featured_products()
 * @see  storefront_popular_products()
 * @see  storefront_on_sale_products()
 */
add_action( 'homepage', 'storefront_homepage_content',		10 );
add_action( 'homepage', 'storefront_product_categories',	20 );
add_action( 'homepage', 'storefront_recent_products',		30 );
add_action( 'homepage', 'storefront_featured_products',		40 );
add_action( 'homepage', 'storefront_popular_products',		50 );
add_action( 'homepage', 'storefront_on_sale_products',		60 );

/**
 * Posts
 * @see  storefront_post_header()
 * @see  storefront_post_meta()
 * @see  storefront_post_content()
 * @see  storefront_paging_nav()
 * @see  storefront_single_post_header()
 * @see  storefront_post_nav()
 * @see  storefront_display_comments()
 */
add_action( 'storefront_loop_post',			'storefront_post_header',		10 );
add_action( 'storefront_loop_post',			'storefront_post_meta',			20 );
add_action( 'storefront_loop_post',			'storefront_post_content',		30 );
add_action( 'storefront_loop_after',		'storefront_paging_nav',		10 );
add_action( 'storefront_single_post',		'storefront_post_header',		10 );
/*add_action( 'storefront_single_post',		'storefront_post_meta',			20 );*/
add_action( 'storefront_single_post',		'storefront_post_content',		30 );
add_action( 'storefront_single_post_after',	'storefront_post_nav',			10 );
// add_action( 'storefront_single_post_after',	'storefront_display_comments',	10 );

/**
 * Pages
 * @see  storefront_page_header()
 * @see  storefront_page_content()
 * @see  storefront_display_comments()
 */
add_action( 'storefront_page', 			'storefront_page_header',		10 );
add_action( 'storefront_page', 			'storefront_page_content',		20 );
// add_action( 'storefront_page_after', 	'storefront_display_comments',	10 );

/**
 * Extras
 * @see  storefront_setup_author()
 * @see  storefront_wp_title()
 * @see  storefront_body_classes()
 * @see  storefront_page_menu_args()
 */
add_action( 'wp',					'storefront_setup_author' );
add_filter( 'body_class',			'storefront_body_classes' );
add_filter( 'wp_page_menu_args',	'storefront_page_menu_args' );