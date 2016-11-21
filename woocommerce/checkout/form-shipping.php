<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $_POST ) ) {
	$ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 0 : 1;
} else {
	$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );
}

$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );
$billing_same_as_shipping = $ship_to_different_address == 1 ? 0 : 1;

?>

<?php if ( WC()->cart->needs_shipping_address() === true ) : ?>

	<div class="woocommerce-shipping-fields">
		<div class="shipping_address">

			<div class="row">
				<div class="col-sm-9 col-xs-12">
					<h3 class="title-block"><?php _e( 'Shipping Address', 'storefront' ); ?></h3>
				</div>
				<div class="col-sm-3 hidden-xs">
					<span class="required to-right"><b>* <?php _e( 'Marks required fields', 'storefront' ); ?></b></span>
				</div>
			</div>

			<div class="splitter" style="width: 150%!important"></div>

			<div class='step-fields'>
				<?php
				do_action( 'woocommerce_before_checkout_shipping_form', $checkout );

				$counter = 0;
				foreach ( $checkout->checkout_fields['shipping'] as $key => $field ) {
					++$counter;
					if (($counter % 2) == 1){
						echo "<div class='row'>";
					}

					echo "<div class='col-xs-12 col-sm-6'>";
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					echo "</div>";

					if (($counter % 2) == 0){
						echo "</div>";
					}
				}
				if (($counter % 2) == 1){
					echo "</div>";
				}
				do_action( 'woocommerce_after_checkout_shipping_form', $checkout );
				?>
				<div class='row order_comments'>
					<div class="col-xs-12">
						<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

						<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>

							<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) : ?>

								<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

							<?php endforeach; ?>

						<?php endif; ?>

						<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
					</div>
				</div>
				<div class="row billing_address_same">
					<div class='col-xs-12'>
						<div id="billing-address-the-same-as-shipping">
							<input id="billing-the-same-as-shipping-address-checkbox" class="input-checkbox" <?php checked( $billing_same_as_shipping, 1 ); ?> type="checkbox" name="billing_the_same_as_shipping" value="1" />
						   &nbsp;
						   <label for="billing-the-same-as-shipping-address-checkbox" class="checkbox" style="display:inline-block"><?php _e('Billing address is same as shipping address', 'storefront'); ?></label>
						</div>
						<input id="billing-the-same-as-shipping-address-checkbox-hidden" type="hidden" name="ship_to_different_address" value="1" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
