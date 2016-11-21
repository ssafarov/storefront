<?php
/**
 * Cross-sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

$crosssells = WC()->cart->get_cross_sells();

if ( sizeof( $crosssells ) == 0 ) return;

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => apply_filters( 'woocommerce_cross_sells_total', $posts_per_page ),
	'orderby'             => $orderby,
	'post__in'            => $crosssells,
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = apply_filters( 'woocommerce_cross_sells_columns', $columns );

if ( $products->have_posts() ) : ?>
	<style>
		.cross-sells img.attachment-shop_catalog{
			border-radius: 5px;
		}

		.cross-sells .product-meta .price {
			display: inline;
			padding: 0;
			margin: 5px 0;
			border-style: none;
		}
		.cross-sells .product-meta .price h6 {
			color: #b0b0b0;
			font-size: 85%;
		}
		.cross-sells .block-add_to_button {
			margin: 20px 0;
		}
		.cross-sells .counter-input-group .input-lg, .cross-sells .counter-input-group .btn-lg {
			height: 40px!important;
			min-height: 40px!important;
			padding: 10px 10px;
			font-size: 20px;
		}
		.cross-sells .counter-input-group .btn-lg {
			line-height: 19px;
		}
		.cross-sells .price {
			height: 66px;
		}
		.cross-sells .product-meta h6 a{
			height: 36px!important;
			display: block;
		}
		.cross-sells .product-meta .price .amount, .cross-sells .product-meta .price sup{
			color: #9ac31c;
		}
		.cross-sells .product-meta .price sup {
			font-size: 120%;
			top: 0.1em;
			font-weight: bold;
		}
		.cross-sells .counter-input-group__wrapper{
			width: 200px!important;
		}
		.cross-sells h3 {
			padding: 10px 0;
		}
	</style>
	<div class="cross-sells">

		<h3><?php _e( 'Recommended for your Order', 'woocommerce' ) ?></h3>

		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>

<?php endif;

wp_reset_query();
