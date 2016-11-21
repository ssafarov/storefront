<?php
/**
 * General functions used to integrate this theme with WooCommerce.
 *
 * @package storefront
 */


/**
 * Before Content
 * Wraps all WooCommerce content in wrappers which match the theme markup
 * @since   1.0.0
 * @return  void
 */
if ( ! function_exists( 'storefront_before_content' ) ) {
	function storefront_before_content() {
		if ( is_product() ) {
			?><main id="main" class="site-main" role="main"><?php
		} else {
			?><main id="main" class="site-main container" role="main"><?php
		}
	}
}

/**
 * After Content
 * Closes the wrapping divs
 * @since   1.0.0
 * @return  void
 */
if ( ! function_exists( 'storefront_after_content' ) ) {
	function storefront_after_content() {
		?>
		</main><!-- #main -->

		<?php do_action( 'storefront_sidebar' );
	}
}

/**
 * Default loop columns on product archives
 * @return integer products per row
 * @since  1.0.0
 */
function storefront_loop_columns() {
	return apply_filters( 'storefront_loop_columns', 3 ); // 3 products per row
}

/**
 * Add 'woocommerce-active' class to the body tag
 * @param  array $classes
 * @return array $classes modified to include 'woocommerce-active' class
 */
function storefront_woocommerce_body_class( $classes ) {
	if ( is_woocommerce_activated() ) {
		$classes[] = 'woocommerce-active';
	}

	return $classes;
}

/**
 * Cart Fragments
 * Ensure cart contents update when products are added to the cart via AJAX
 * @param  array $fragments Fragments to refresh via AJAX
 * @return array            Fragments to refresh via AJAX
 */
if ( ! function_exists( 'storefront_cart_link_fragment' ) ) {
	function storefront_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();

		storefront_cart_link();

		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

/**
 * WooCommerce specific scripts & stylesheets
 * @since 1.0.0
 */
function storefront_woocommerce_scripts() {
	global $storefront_version;

	// wp_enqueue_style( 'storefront-woocommerce-style', get_template_directory_uri() . '/inc/woocommerce/css/woocommerce.css', $storefront_version );
}

/**
 * Related Products Args
 * @param  array $args related products args
 * @since 1.0.0
 * @return  array $args related products args
 */
function storefront_related_products_args( $args = array() ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $args, $defaults );

	return $args;
}

/**
 * Product gallery thumnail columns
 * @return integer number of columns
 * @since  1.0.0
 */
function storefront_thumbnail_columns() {
	return intval( apply_filters( 'storefront_product_thumbnail_columns', 4 ) );
}

/**
 * Products per page
 * @return integer number of products
 * @since  1.0.0
 */
function storefront_products_per_page() {
	return intval( apply_filters( 'storefront_products_per_page', 12 ) );
}

/**
 * Query WooCommerce Extension Activation.
 * @var  $extension main extension class name
 * @return boolean
 */
function is_woocommerce_extension_activated( $extension = 'WC_Bookings' ) {
	return class_exists( $extension ) ? true : false;
}


/**
 * Single product subheading
 * Grabs the product_subheading custom field
 * @since   1.0.0
 * @return  void
 * @deprecated
 */
if ( ! function_exists( 'f3d_single_product_subheading' ) ) {
	function f3d_single_product_subheading() {
		echo '<h6>' . get_post_custom_values('product_subheading')[0] . '</h6>';
	}
}


/**
 * Single product content
 * Grabs product content
 * @since   1.0.0
 * @return  the_content()
 */
if ( ! function_exists( 'f3d_single_product_content' ) ) {
	function f3d_single_product_content() {
		return the_content();
	}
}



/**
 * Single product price
 * Grabs the product_price_heading custom field and the product price template
 * @since   1.0.0
 * @return  void
 */
if ( ! function_exists( 'f3d_template_single_price' ) ) {

	/**
	 * Output the product price.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function f3d_template_single_price() {
		echo '<div class="price"><h6>' . get_post_custom_values('product_price_heading')[0] .'<h6>';
		wc_get_template( 'single-product/price.php' );
		echo '</div>';
	}
}


if ( ! function_exists( 'add_order_extra_actions' ) ) {

    function add_order_extra_actions($actions)
    {
        return array_merge($actions, ['customer_canceled_order', 'customer_order_delivered_register_reminder', 'customer_order_dispatched_register_reminder', 'customer_order_dispatched_download_reminder', 'customer_order_download_follow_reminder', 'customer_before_renewal']);
    }

}


if ( ! function_exists( 'add_extra_emails_classes' ) ) {

    function add_extra_emails_classes($actions)
    {
        $actions['WC_Email_Customer_Canceled_Order'] = include(ABSPATH . 'wp-content/themes/storefront/inc/woocommerce/classes/emails/class-wc-email-customer-canceled-order.php');
        $actions['WC_Email_Customer_Order_Delivered_Registration_Reminder'] = include(ABSPATH . 'wp-content/themes/storefront/inc/woocommerce/classes/emails/class-wc-email-customer-order-delivered-registration-reminder.php');
        $actions['WC_Email_Customer_Order_Dispatched_Registration_Reminder'] = include(ABSPATH . 'wp-content/themes/storefront/inc/woocommerce/classes/emails/class-wc-email-customer-order-dispatched-registration-reminder.php');
        $actions['WC_Email_Customer_Order_Dispatched_Download_Reminder'] = include(ABSPATH . 'wp-content/themes/storefront/inc/woocommerce/classes/emails/class-wc-email-customer-order-dispatched-download-reminder.php');
        $actions['WC_Email_Customer_Order_Download_Follow_Reminder'] = include(ABSPATH . 'wp-content/themes/storefront/inc/woocommerce/classes/emails/class-wc-email-customer-order-download-follow-reminder.php');
        $actions['WC_Email_Customer_Before_Renewal'] = include(ABSPATH . 'wp-content/themes/storefront/inc/woocommerce/classes/emails/class-wc-email-customer-before-renewal.php');

        return $actions;
    }

}