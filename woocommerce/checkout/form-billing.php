<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** @global WC_Checkout $checkout */

remove_action('woocommerce_after_checkout_billing_form', ['WC_EU_VAT_Number', 'vat_number_field']);
?>

<?php
	if ( empty( $_POST ) ) {
		$ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 0 : 1;
	} else {
		$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );
	}

	$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );
	$billing_same_as_shipping = $ship_to_different_address == 1 ? 0 : 1;

	$iniDisplay = WC()->cart->needs_shipping_address() && $billing_same_as_shipping == 1 ? 'display:none;':'';
?>

<div class="woocommerce-billing-fields">
	<div class="billing_address" style="<?= $iniDisplay; ?>">
		<?php if ( WC()->cart->needs_shipping() ) : ?>
		<div class="splitter" style="width: 180%!important"></div>
		<?php endif; ?>
		<div class="row">
			<div class="col-sm-9 col-xs-12">
				<?php if ( WC()->cart->ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
				<h3><?php _e( 'Billing &amp; Shipping', 'storefront' ); ?></h3>
				<?php else : ?>
		        <h3><?php _e( 'Billing Address', 'storefront' ); ?></h3>
				<?php endif; ?>
			</div>
			<div class="col-sm-3 hidden-xs">
				<span class="required to-right"><b>* <?php _e( 'Marks required fields', 'storefront' ); ?></b></span>
			</div>
		</div>
		<div class="splitter" style="width: 180%!important"></div>
	<?php
		do_action( 'woocommerce_before_checkout_billing_form', $checkout );
	?>

		<div class='row step-fields'>
	<?php
		$counter = 0;
		foreach ( $checkout->checkout_fields['billing'] as $key => $field ) {
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
	?>
		</div>
	</div>

	<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>
	<?php if(!is_user_logged_in()):?>
	<script>
		jQuery(function ($) {
			var billing_country = '#billing_country';

			$(billing_country).change(function () {
				$.ajax({
					method: "POST",
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'set_country_unregister_user',
						country: $(this).val()
					},
					success: function (data) {
					},
					error: function (data) {
						console.log(data);
						console.log("SetCountryUnregisterUser  is not installed.");
					}
				});
			});
		});
	</script>
	<?php endif;?>
</div>
