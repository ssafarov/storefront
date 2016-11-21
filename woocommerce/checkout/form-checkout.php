<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce;
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form');

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

wc_print_notices();

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() );
$new_user = true && is_user_logged_in();

if (isset($_SESSION)) {
	$_SESSION['new_user-flag'] = $new_user;
}

?>

<style>

	.modal-open{
		overflow: visible !important;
		padding-right: 0 !important;
	}

	#modalRightOfReturn  ul li {
		margin-left: 48px;
	}

	h2.tiny {
		padding-top: 15px;
		font-size: 20px;
		line-height: 30px;
		line-height: 1.25;
	}
	.cell {
		padding: 15px 0!important;
	}
	.cart__actions .cell{
		padding: 15px!important;
	}
	.payment_form, .shop_table, .shipping_info, .billing_info {
		border-collapse:separate;
		border: 1px solid #ccc;
		border-radius: 5px;
		margin-bottom: 20px;
		margin-top: 20px;
	}
	.shop_table{
		margin-top: 0;
	}
	.payment_form{
		padding: 10px!important;
	}
	.title, .thead {
		padding: 0;
		/*border-top: 1px solid #ccc;*/
		border-bottom: 1px solid #ccc;
	}
	.tbody {
		padding: 0;
	}
	.footer {
		padding: 20px;
		border-top: 1px solid #ccc;
	}
	.footer  {
		text-align: center;
	}
	.footer > .header {
		margin: 0 auto;
		width: 350px;
		height: 30px;
	}
	.footer a, .footer p {
		color: #888;
		margin: 20px 0!important;
	}
	.checkout_coupon > p.description {
		margin: 0 0 10px 0!important;
	}
	.add_info_block .title, .payment_block .title {
		margin-left: -35px;
		margin-right: -35px;
		border-top: 1px solid #ccc;
	}
	.title h3, .footer h3 {
		padding: 20px;
		margin-bottom: 0;
		font-size: 1.8rem;
	}
	.shop_table .thead div {
		color: #333;
		background-color: #fff;
		font-weight: bold!important;
	}
	.shop_table .thead div, .shop_table .tbody .cart_item, .totals {
		font-weight: 400;
		padding: 10px 0;
		font-size: 1.4rem;
		vertical-align: middle;
	}
	.shop_table .thead div.product-thumbnail, .shop_table .tbody div.product-thumbnail {
		padding-left: 40px!important;
	}
	.shop_table .thead div.product-price, .shop_table .tbody div.product-price {
		padding-left: 5px!important;
		text-align: left;
	}
	.shop_table .tbody div.product-price {
		text-align: right;
	}
	.cart_item, .totals, .cart__actions {
		margin: 0;
	}
	.cart_item {
		border-bottom: 1px #eee solid;
	}
	.no-right-padding{
		padding-right: 0!important;
	}
	.no-left-padding{
		padding-left: 0!important;
	}

	.cart-sub-total th{
		font-weight: normal;
		font-size: 120%;
	}
	.cart-grand-total th {
		text-align: left;
		vertical-align: middle;
		font-weight: normal;
		font-size: 110%;
	}
	.order-total th {
		font-weight: bold;
		font-size: 120%;
	}
	.cart-grand-total td {
		text-align: right;
		vertical-align: middle;
		font-weight: bold;
		padding: 10px 0 10px 0!important;
	}
	.tbody div.product-quantity div {
		padding: 0;
	}
	.thumbnail_image img {
		border: 1px solid #eee;
		border-radius: 5px;
	}
	.shop_table .product-name a{
		color: #000!important;
	}
	.shop_table a.remove {
		color: #f00!important;
		font-size: 85%;
	}
	.product-quantity{
		text-align: center;
	}
	.quantity, .price {
		line-height: 2.1;
		margin: 0!important;
	}
	.quantity [type="button"]{
		line-height: 2.1;
		height: auto;
	}
	.quantity .qty{
		border-top: 1px solid #ccc;
		border-bottom: 1px solid #ccc;
		float: none!important;
		font-size: 1.05em;
		width: 60px;
		height: 54px;
		border-radius: 0;
		padding: 5px 3px 8px;
	}
	.quantity [type="button"]{
		border: solid 1px #ccc!important;
	}
	.back_to_shop{
		text-transform:uppercase;
		color: #000;
		font-size: 85%;
		margin-top: 20px;
	}
	.cart_item .amount {
		color: #9ac31c!important;
		font-size: 120%;
		font-weight: bold;
	}
	.cart_totals .amount {
		color: #9ac31c!important;
		font-size: 180%;
	}
	.cart-subtotal .amount {
		color: #9ac31c!important;
		font-size: 140%!important;
	}
	.chosen-container{
		font-size: 16px!important;
		padding: 0!important;
	}
	.cart_totals a.chosen-single{
		height: 35px;
		padding-top: 3px;
	}
	.cart_totals a.chosen-single b, .chosen-container-active.chosen-with-drop .chosen-single div b {
		background-position: 0px 8px
	}
	.border-top{
		border-top: 1px solid #ccc;
	}
	.totals a.checkout_button span {
		margin: 0 10px;
	}
	.wc-cart-shipping-notice {
		text-align: left;
	}
	.cart-collaterals{
		margin-top: 20px;
	}
	.cart_totals {
		padding-right: 40px!important;
	}
	a.checkout_button span {
		margin: 0 15px;
	}
	a.underline, a:hover.underline, a:visited.underline, a:active.underline {
		text-decoration: underline;
	}
	.cart .order-total th, .cart .order-total td, .cart .shipping th, .cart .shipping td, .cart .tax-rate th, .cart .tax-rate td {
		border-top: solid 1px #eee;
	}
	.cart .order-total th, .cart .order-total td {
		font-weight: 700;
	}
	.wc-backward {
		color: #000;
	}
	.checkout_button span {
		text-transform: uppercase;
		font-size: 90%  ;
	}
	.totals .well {
		padding: 10px 20px;
		margin-left: 25px;
	}
	.totals .well p {
		font-size: 90%;
	}
	.addresses_block, .woocommerce-checkout-review-order-table, .payment_block, .add_info_block {
		margin: 20px;
		margin-bottom: 0;
	}

	a.showcoupon {
		font-size: 85%;
		font-weight: bold;
		text-decoration: underline;
		vertical-align: bottom;
	}
	a.showcoupon:hover {
		text-decoration: none;
	}
	.checkout_coupon input{
		display: inline-block!important;
		width: auto!important;
		border-radius: 5px;
		height: 55px!important;
		text-transform: uppercase;
	}
	.checkout_coupon input[type="submit"]{
		border:none!important;
		margin-top: -5px;
	}
	.payment_method_stripe > img {
		float: left;
	}

	.payment_method_stripe img[alt="Discover"] {
		display: none;
	}

	.payment_method_stripe img[alt="JCB"] {
		display: none;
	}

	.payment_method_stripe img[alt="Diners"] {
		display: none;
	}

	#term-conditions {
		width: 80%;
	}

	#consumer-right {
		width: 80%;
	}

	@media (max-width: 991px) {
		.shop_table .thead div.product-price, .shop_table .tbody div.product-price, .totals {
			text-align: left;
		}
		.shop_table .tbody div.product-price, .totals {
			text-align: right;
		}
		.shop_table .thead div.product-thumbnail, .shop_table .tbody div.product-thumbnail {
			padding-left: 10px !important;
		}
		.totals {
			padding: 0!important;
		}
		.no-right-padding {
			padding-right: 15px!important;
		}
		.addresses_block, .woocommerce-checkout-review-order-table, .payment_block {
			margin: 0;
		}
	}
	@media (max-width: 768px) {
		.wizard.three-steps > .steps > ul > li {
			width: 33%;
		}

		.wizard > .steps .disabled a {
			font-size: 14px;
		}

		.wizard > .steps .disabled a span {
			line-height: 27px;
		}

		.wizard > .steps .disabled a{
			line-height: 13px;
			letter-spacing: 0.3px;
		}

		.wizard > .steps .current a{
			font-size: 14px;
		}

		.wizard > .steps .done a {
			font-size: 14px;
		}

		.wizard > .content > .body label{
			font-size: 90%
		}

		.woocommerce form.login input[type="submit"] {
			font-size: 14px;
		}

		.login-box > .column-no-login > .actions a {
			font-size: 14px;
		}

		h2.tiny{
			text-align: justify;
			font-size: 16px;
			color: black;
			letter-spacing: 0px;
		}

		.login-box  .col-xs-6 label.checkbox{
			font-size: 15px;
			font-weight: 500;
		}

		.col-sm-6.col-xs-12.column-no-login {
			padding-top: 0;
		}

		.actions.clearfix li {
			float: none !important;
			margin: 0 auto;
			display: block;
			text-align: center;
		}

		.wizard > .content > .body {
			padding: 0;
		}

		.xs-title-checkout h1  {
			font-size: 18px;
			font-weight: 600;
		}

		.secured-block {
			position:  relative;
			top: -12px;
		}

		#button-secured {
			top: 12px;
		}

		.secured-block .col-sm-10 {
			position: absolute;
			top: 50%;
			left: 53px;
			margin-top: -18px;
		}

		.secured-block .fa.fa-lock.big-font {
			font-size: 158%;
			color: #00a0d2;
			position: absolute;
			top: 8px;
		}

		#button-secured .fa.fa-lock.big-font {
			font-size: 300%;
			color: #00a0d2;
			position: absolute;
			top: -1px;
		}

		.addresses-tab-contents  .title-block{
			font-size: 18px;
			margin-top: 15px;
			margin-bottom: 0;
		}

		.wizard > .actions a, .wizard > .actions a:hover {
			font-size: 14px;
		}

		.wizard.clearfix.four-steps ul[aria-label='Pagination'] {
			width: 80% !important;
			display: block !important;
			margin: auto !important;
		}

		.wizard.clearfix.three-steps ul[aria-label='Pagination'] {
			width: 100% !important;
		}

		.wizard > .actions > ul > li:first-child {
			width: 246px;
			text-align: center;
			margin-bottom: 15px;
		}

		.ordersummary-tab-contents  .title-block{
			font-size: 18px;
			margin-top: 15px;
			margin-bottom: 0;
			margin-left: 15px;
		}

		div.splitter{
			height: 2px;
		}

		#billing-address-the-same-as-shipping label {
			font-size: 15px;
		}

		.addresses_block .title {
			border-bottom: 2px solid #ccc;
		}

		.woocommerce-checkout-review-order-table  .col-xs-12.title{
			border-bottom: 2px solid #ccc;
		}

		#term-conditions {
			width: auto;
		}

		#consumer-right {
			width: auto;
		}

		.addresses_block {
			padding: 0 15px;
		}

		.woocommerce-checkout-review-order-table {
			padding: 0 15px;
		}

		#payment {
			margin: 0 15px 10px 15px !important;
		}

		.shipping_address {
			padding: 0 15px;
		}

		.billing-content .row.step-fields{
			padding: 0 15px;
		}

		.billing-content .col-sm-9.col-xs-12 h3{
			font-size: 22px;
			margin-bottom: 0;
			margin-left: 15px;
		}
	}
</style>
<div class="row">
	<div class="col-xs-4 col-sm-9 xs-title-checkout">
		<h1><?php the_title(); ?></h1>
	</div>
	<div class="col-xs-8 col-sm-3">
		<div class="content">
			<div class="alert alert-info secured to-right secured-block">
				<div class="col-sm-2">
					<i class="fa fa-lock big-font"></i>
				</div>
				<div class="col-sm-10">
					<p class="blue upper"><?php _e( 'Secure checkout', 'storefront' ); ?></p>
					<p class="small-font grey"><?php _e( '256bit Secure Encryption', 'storefront' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<form  name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">
			<div id="wizard"><!---Start of jQuery Wizard -->
				<?php do_action('woocommerce_multistep_checkout_before'); ?>

				<?php if (sizeof($checkout->checkout_fields) > 0) : ?>
					<?php do_action('woocommerce_checkout_before_customer_details'); ?>
						<h1 class="title-addresses">
							<?php
								if (WC()->cart->needs_shipping_address() === true) {
									echo get_option('wmc_shipping_billing_label') ? __(get_option('wmc_shipping_billing_label'), 'storefront') : __('Shipping Details', 'storefront');
								} else {
									echo get_option('wmc_billing_label') ? __(get_option('wmc_billing_label'), 'storefront') : __('Billing details', 'storefront');
								}
							?>
						</h1>
						<div class="addresses-tab-contents">
							<?php do_action('woocommerce_multistep_checkout_before_shipping'); ?>
							<?php do_action('woocommerce_multistep_checkout_before_billing'); ?>
							<div class="shipping-content">
								<?php do_action('woocommerce_checkout_shipping'); ?>
							</div>
							<div class="billing-content">
								<?php do_action('woocommerce_checkout_billing'); ?>
							</div>
							<?php do_action('woocommerce_multistep_checkout_after_shipping'); ?>
							<?php do_action('woocommerce_multistep_checkout_after_billing'); ?>
							<?php do_action('woocommerce_checkout_after_customer_details'); ?>
						</div>
				<?php endif; ?>

				<?php if (get_option('wmc_merge_order_payment_tabs') != "true" || get_option('wmc_merge_order_payment_tabs') == ""): ?>
					<h1 class="title-ordersummary"><?php echo get_option('wmc_orderinfo_label') ? __(get_option('wmc_orderinfo_label'), 'woocommerce-multistep-checkout') : __('Summary & Payment', 'woocommerce-multistep-checkout'); ?></h1>
					<div class="ordersummary-tab-contents" data-step="placeOrder">
						<div class="row">
							<div class="col-xs-12">
								<h3 class="title-block title-xs-order-sum"><?php _e('Order Summary', 'storefront');?></h3>
							</div>
							<div class="splitter" style="width: 120%!important"></div>
						</div>

						<!-- Modal Terms -->
						<div class="modal fade" id="modalTerms" tabindex="-1" role="dialog"
							 aria-labelledby="myModalLabel">
							<div class="modal-dialog" id="term-conditions" role="document" style="margin-top:120px;height: 70%; overflow-y: scroll;">
								<div class="modal-content">
									<div class="modal-header">
										<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span></button>-->
										<h4 class="modal-title" id="myModalLabel"><?php _e('Terms & Conditions', 'storefront');?></h4>
									</div>
									<div class="modal-body modalIframe">
										<?php echo getPageBySlug('terms-of-sale')?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary"
												data-dismiss="modal">Close
										</button>
										<!--                                    <button type="button" class="btn btn-primary">Save changes</button>-->
									</div>
								</div>
							</div>
						</div>

						<!-- Modal right of return -->
						<div class="modal fade" id="modalRightOfReturn" tabindex="-1" role="dialog"
							 aria-labelledby="myModalLabel">
							<div class="modal-dialog" id="consumer-right" role="document" style="margin-top:120px;height: 70%; overflow-y: scroll;">
								<div class="modal-content">
									<div class="modal-header">
										<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span></button>-->
										<h4 class="modal-title" id="myModalLabel"><?php _e('Consumer Right of Cancellation and Refund', 'storefront');?></h4>
									</div>
									<div class="modal-body modalIframe">
										<?php echo getPageBySlug('right-of-return')?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary"
												data-dismiss="modal">Close
										</button>
										<!--                                    <button type="button" class="btn btn-primary">Save changes</button>-->
									</div>
								</div>
							</div>
						</div>


						<?php do_action('woocommerce_multistep_checkout_before_order_info'); ?>
						<?php do_action('woocommerce_multistep_checkout_before_order_contents'); ?>
						<?php do_action('woocommerce_multistep_checkout_before_payment'); ?>
						<?php do_action('woocommerce_checkout_before_order_review' ); ?>
						<!---->
						<?php do_action('woocommerce_checkout_order_review'); ?>
						<?php do_action('woocommerce_checkout_order_processed'); ?>
						<!---->
						<?php do_action('woocommerce_checkout_after_order_review' ); ?>
						<?php do_action('woocommerce_multistep_checkout_after_order_contents'); ?>
						<?php do_action('woocommerce_multistep_checkout_after_order_info'); ?>
					</div>
				<?php endif?>
				<?php do_action('woocommerce_multistep_checkout_after'); ?>
			</div>
		</form>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div id="button-secured" class="alert alert-info secured secured-block">
			<div class="col-sm-2">
				<i class="fa fa-lock big-font"></i>
			</div>
			<div class="col-sm-10">
				<p class="blue upper"><?php _e( 'Secure checkout', 'storefront' ); ?></p>
				<p class="small-font grey"><?php _e( '256bit Secure Encryption', 'storefront' ); ?></p>
			</div>
		</div>
	</div>
</div>
<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
