<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}
$info_message = apply_filters( 'woocommerce_checkout_coupon_message', '<a href="#" class="showcoupon"><img align="left" src="' . get_template_directory_uri() . '/images/checkout/cpn-ico.png" border="0"/>' . __( 'Have a coupon? Click here to enter your code', 'storefront' ) . '</a>' );
?>
<style>
	@media (max-width: 768px) {

		.footer > .header {
			margin: 0 auto;
			width: 189px;
			height: 30px;
			text-align: left;
			line-height: 1;
		}

		.footer > .header .showcoupon img {
			position: relative;
			left: -7px;
		}
	}
</style>
<div class="header">
<?= $info_message; ?>
</div>
<div id="checkout_coupon_form" class="checkout_coupon" style="display: none;">

	<div class="row">
		<div class="col-xs-12">
			<p class="description"><?php _e('Enter a promotional or gift voucher code below to apply a discount to your order','storefront');?></p>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-8 col-xs-offset-2">
			<div class="row form-row" style="text-align: left;">
				<div class="col-xs-12 col-sm-6">
					<div class="form-control-wrapper">
						<label for="coupon_code"><?php esc_attr_e( 'Coupon code', 'storefront' ); ?></label><br/>
						<input type="text" name="coupon_code" id="coupon_code" value="" style="width:100%!important;" />
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-control-wrapper" style="float: right;">
						<label for="apply_coupon">&nbsp;</label><br/>
						<input style="padding: 10px 50px;" type="button" class="button btn-quaternary btn" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'storefront' ); ?>" />
						<a href="#" class="hidecoupon"><?php _e('[Cancel]','storefront');?></a>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
