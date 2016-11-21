<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

$product_price_heading = get_post_custom_values('product_price_heading', $product->id)[0];
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<div class="price">
		<?php 
			if ( $product_price_heading ) {
				printf( _x('<h6>%s</h6>', 'fuel3d'), $product_price_heading );
			}
		?>
		
		<sup><?php echo get_woocommerce_currency_symbol(); ?></sup>
		<span class="amount"><?php echo $product->get_price(); ?></span>
		<?php echo $product->get_price_suffix(); ?>
	</div>
<?php endif; ?>