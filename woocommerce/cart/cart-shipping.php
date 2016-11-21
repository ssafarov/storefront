<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<script>
	jQuery( function($) {
		// Frontend Chosen selects
		$('select.shipping_method').chosen( {disable_search: true} );
	});
</script>
<style>
	abbr[title] {
		border-bottom: none;
	}
	.chzn-search{display: none}
	.shipping .title{
		font-size: 16px;
	}
	.shipping td p{
		font-size: 16px;
	}
</style>

<tr class="shipping">
	<th class="title"><?php
		if ( $show_package_details ) {
			printf( __( 'Shipping #%d', 'storefront' ), $index + 1 );
		} else {
			_e( 'Shipping & Handling', 'storefront' );
		}
	?></th>
	<td>
		<?php

		if ( ! empty( $available_methods ) ) : ?>

			<select name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>" class="shipping_method">
				<?php foreach ( $available_methods as $method ) : ?>
					<option value="<?php echo esc_attr( $method->id ); ?>" <?php selected( $method->id, $chosen_method ); ?>><?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?></option>
				<?php endforeach; ?>
			</select>

		<?php elseif ( ( WC()->countries->get_states( WC()->customer->get_shipping_country() ) && ! WC()->customer->get_shipping_state() ) || ! WC()->customer->get_shipping_postcode() ) : ?>

			<?php if ( is_cart() && get_option( 'woocommerce_enable_shipping_calc' ) === 'yes' ) : ?>

				<p><?php _e( 'Please use the shipping calculator to see available shipping methods.', 'woocommerce' ); ?></p>

			<?php elseif ( is_cart() ) : ?>

				<p><?php _e( 'Please continue to the checkout and enter your full address to see if there are any available shipping methods.', 'woocommerce' ); ?></p>

			<?php else : ?>

				<p><?php _e( 'Please fill in your details to see available shipping methods.', 'woocommerce' ); ?></p>

			<?php endif; ?>

		<?php else : ?>

			<?php if ( is_cart() ) : ?>

				<?php echo apply_filters( 'woocommerce_cart_no_shipping_available_html',
					'<p>' . __( 'Please continue to the checkout to see the available shipping methods.', 'woocommerce' ) . '</p>'
				); ?>

			<?php else : ?>

				<?php echo apply_filters( 'woocommerce_no_shipping_available_html',
					'<p>' . __( 'There are no shipping methods available. Please double check your address, or contact us if you need any help.', 'woocommerce' ) . '</p>'
				); ?>

			<?php endif; ?>

		<?php endif; ?>

		<?php if ( $show_package_details ) : ?>
			<?php
				foreach ( $package['contents'] as $item_id => $values ) {
					if ( $values['data']->needs_shipping() ) {
						$product_names[] = $values['data']->get_title() . ' &times;' . $values['quantity'];
					}
				}

				echo '<p class="woocommerce-shipping-contents"><small>' . __( 'Shipping', 'woocommerce' ) . ': ' . implode( ', ', $product_names ) . '</small></p>';
			?>
		<?php endif; ?>

		<?php if ( is_cart() ) : ?>
			<?php woocommerce_shipping_calculator(); ?>
		<?php endif; ?>
	</td>
</tr>
