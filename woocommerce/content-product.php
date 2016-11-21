<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Availability
$availability = $product->get_availability();

// Ensure visibility
if ( ! $product || ! $product->is_visible() || !$availability['available'])
	return;

if ($product->get_attribute('nalpeiron_profilename_new')) {
    return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';

// Grid classes

$classes[] = 'col-xs-12 col-sm-12 col-md-6';

// Product custom fields
$product_subheading = get_post_custom_values('product_subheading')[0];
?>
<div <?php post_class( $classes ); ?>>
	<article class="well" style="height: 500px!important; overflow:hidden!important;">
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<div class="row">
			<div class="col-xs-6">
				<a href="<?php the_permalink(); ?>">
					<?php
						/**
						 * woocommerce_before_shop_loop_item_title hook
						 *
						 * @hooked woocommerce_show_product_loop_sale_flash - 10
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						do_action( 'woocommerce_before_shop_loop_item_title' );
					?>
				</a>
			</div>
			
			<div class="col-xs-6 product-meta">
				<h6><a href="<?php echo $product->get_permalink(); ?>"><?php the_title(); ?></a></h6>
				<?php
					if ( $product_subheading ) {
						printf( _x('<h7>%s</h7>', 'fuel3d'), $product_subheading );
					}
				?>

				<?php
					/**
					 * woocommerce_after_shop_loop_item_title hook
					 *
					 * @hooked woocommerce_template_loop_rating - 5
					 * @hooked woocommerce_template_loop_price - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );
				?>


				<?php

					if ( $availability['available'] ){
						echo $availability['amount_html'];
						echo '<div class="block-add_to_button">';
						woocommerce_template_loop_add_to_cart();
						echo '</div>';
					}
				?>
			</div>

		</div>
		<div class="row">
			<div class="col-xs-12 product-description">
				<?php 
					/**
					 * f3d_item_description hook
					 *
					 * @hooked woocommerce_template_single_excerpt
					 */
					do_action( 'f3d_item_description' ); 
				?>
			</div>
		</div>
	</article>
</div>
