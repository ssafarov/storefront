<?php
/**
 * Order details
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

$thisWasNewUser = (isset($_SESSION)&&isset($_SESSION['new_user-flag']))?$_SESSION['new_user-flag']:false;

WC()->cart->tax_display_cart = 'excl';

$order = new WC_Order( $order_id );

?>
<style>
	@media (max-width: 768px) {
		.woocommerce-checkout-review-order-table .title-xs-custom-dtls {
			font-size: 16px;
		}

		.woocommerce-checkout-review-order-table .sub-title-xs {
			font-size: 14px;
		}

		.three-steps > .steps .done a {
			font-size: 14px;
		}

		.row.totals .col-xs-6.label{
			font-size: 14px;
		}

		.row.totals .col-xs-6.content{
			font-size: 16px;
		}

		.row.totals .row.block:last-child .amount{
			font-size: 18px;
		}

		.row.totals .row.block:not(:last-child){
			border-bottom: 1px #eee solid;
		}

		.row.totals .row.block {
			margin-top: 15px;
			padding-bottom: 15px;
		}

		.row.totals .row.block .col-xs-6.content  {
			text-align: right;
		}

		.row.totals .row.block .col-xs-6.label{
			text-align: left;
		}

	}

</style>
<div class="title">
	<h2><?php _e( 'Your order', 'storefront' ); ?></h2>
</div>
<div class="woocommerce-checkout-review-order-table">

	<div class="container-fluid">
	<div class="row customer_details">
		<div class="col-xs-12 title">
			<h3 class="title-xs-custom-dtls"><?php _e( 'Customer details', 'storefront' ); ?></h3>
		</div>
		<div class="container_fluid customer_addresses">
			<div class="col-xs-12 col-sm-6 billing_address_info">
				<div class="col-xs-12 title">
					<h4 class="sub-title-xs"><?php _e( 'Billing Address', 'storefront' ); ?></h4>
				</div>
				<div class="address">
					<p>
						<?php
						if ( ! $order->get_formatted_billing_address() ) _e( 'N/A', 'storefront' ); else echo $order->get_formatted_billing_address();
						?>
					</p>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 shipping_address_info">
				<div class="col-xs-12 title">
					<h4 class="sub-title-xs"><?php _e( 'Shipping Address', 'storefront' ); ?></h4>
				</div>
				<div class="address">
					<p>
						<?php
						if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'storefront' ); else echo $order->get_formatted_shipping_address();
						?>
					</p>
					<?php if ($order->customer_message) : ?>
						<div class="col-xs-12 title">
							<h4><?php _e( 'Order notes', 'storefront' ); ?></h4>
						</div>
						<div class="notes">
							<p class="notes"><?= $order->customer_message ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php

		if (false) : ?>

			<div class="container_fluid">
				<div class="saveUserInfo_block">
					<div class="col-xs-12 title">
						<h3><?php _e( 'Save your information for next time', 'storefront' ); ?></h3>
					</div>
					<div class="container_fluid saveUserInfo_form">

						<div class="col-xs-4 form-group">
							<label for="password"><?php esc_attr_e( 'Password', 'storefront' ); ?></label>
							<input type="password" name="password" class="input-text" size="20" value="" />
						</div>
						<div class="col-xs-4 form-group">
							<label for="password_confirm"><?php esc_attr_e( 'Confirm password', 'storefront' ); ?></label>
							<input type="password" name="password_confirm" class="input-text" size="20" value="" />
						</div>
						<div class="col-xs-4 form-group">
							<input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>589" />
							<input id="save_user" style="margin-top: 22px" type="button" class="button btn btn-primary" name="save_user" value="<?php esc_attr_e( 'Save account', 'storefront' ); ?>" />
						</div>
						<div class="clear"></div>

						<script>
							jQuery(document).ready (function(){
								jQuery('#save_user').click(function(e){
									e.preventDefault();
									e.stopPropagation();

									var password = jQuery("input[name='password']").val(),
										passwordConfirm = jQuery("input[name='password_confirm']").val(),
										user_id = jQuery("input[name='user_id']").val(),
										match = password == passwordConfirm;

									if ((match)&&(password.length > 9)) {
										jQuery.ajax({
											method: 'POST',
											url: '<?php echo admin_url('admin-ajax.php'); ?>',
											dataType: 	'json',
											afterTypeDelay: 100,
											data: {
												action   : 'save_user_prefs',
												password : password,
												user_id  : user_id
											},
											success: function(data) {
												console.log(data);
												console.log("success!");
												alert ("Success! You password has been set! You can now login to the user password.");
												jQuery ('.saveUserInfo_block').hide();
											},
											error: function(data){
												console.log(data);
												console.log("fail to set user password");
												alert ("Error! You password has not been set during the errors!");
												jQuery ('.saveUserInfo_form').show();
											}
										});
									} else {
										if (!match) {
											alert ("Passwords entered do not match, please enter the same passwords for both fields.");
										} else {
											alert ("Password you entered must meet our security requirements. It must be at least 10 characters long, including both lower and upper case letters and a number.");
										}
									}

									return false
								});
							});
						</script>
					</div>
				</div>
			</div>
		<?php
			endif;
		?>
	</div>

	<?php
		// Additional customer details hook
		do_action( 'woocommerce_order_details_after_customer_details', $order, $new_user );
	?>

	</div>
	<div class="container-fluid">
		<div class="row shop_table">
			<div class="col-xs-12 title">
				<h3 class="title-xs-custom-dtls"><?php _e( 'Order Details', 'storefront' ); ?></h3>
			</div>
			<div class="col-xs-12 table">
				<div class="thead">
					<div class="container-fluid">
						<div class="col-sm-12 col-md-9 product-thumbnail"><?php _e( 'Items', 'storefront' ); ?></div>
						<div class="hidden-xs hidden-sm col-md-3 product-price"><?php _e( 'Price', 'storefront' ); ?></div>
					</div>
				</div>
				<div class="tbody container-fluid">

					<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>

					<?php
					foreach( $order->get_items() as $item ) {
						$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
						$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );
						?>

						<div class="row <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<div class="col-xs-12 col-sm-12 col-md-9">
								<div class="row">
									<div class="col-xs-3 col-sm-3 col-md-2 product-thumbnail">
										<div class="cell">
											<?php
											$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(array( 70, 70 )), $cart_item, $cart_item_key );
											if ( ! $_product->is_visible() )
												echo $thumbnail;
											else
												printf( '<a target="_blank" href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
											?>
										</div>
									</div>
									<div class="col-xs-9 col-sm-9 col-md-10 product-name">
										<div class="cell">
											<?php
											if ( $_product && ! $_product->is_visible() )
												echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
											else
												echo apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item );

											$item_meta->display();

											if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

												$download_files = $order->get_item_downloads( $item );
												$i              = 0;
												$links          = array();

												foreach ( $download_files as $download_id => $file ) {
													$i++;

													$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
												}

												echo '<br/>' . implode( '<br/>', $links );
											}
											echo apply_filters( 'woocommerce_order_item_quantity_html', '<br/><strong class="product-quantity">' . sprintf( 'Qty %s', $item['qty'] ) . '</strong>', $item );
											?>
											<div class="product-price visible-xs" style="text-align: left;padding: 0 !important;">
												<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), null, null ); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-6 col-sm-7 col-md-3 product-price hidden-xs">
								<div class="cell">
									<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), null, null );
									?>
								</div>
							</div>
						</div>
						<?php
					}
					?>

					<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

					<div class="row totals">
						<div class="col-xs-12 col-sm-offset-6 col-sm-6 no-right-padding" id="grand_totals">
							<div class="row">
								<div class="col-xs-12 total-inf-block">
									<?php
									if ( $totals = $order->get_order_item_totals('excl') ) foreach ( $totals as $total ) :
										?>
										<div class="row block">
											<div class="col-xs-6 label"><?php echo $total['label']; ?></div>
											<div class="col-xs-6 content"><?php echo str_replace('&nbsp;', '', $total['value']); ?></div>
										</div>
										<?php
									endforeach;
									?>
								</div>
							</div>
						</div>
					</div>
					<?php
					if ( in_array( $order->status, array( 'processing', 'completed' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
					?>
					<div class="row notes">
						<div class="col-xs-12 col-sm-offset-6 col-sm-6 no-right-padding" id="grand_totals">
							<div class="row">
								<div class="col-xs-12">
									<div class="product-purchase-note">
										<?php echo apply_filters( 'the_content', $purchase_note ); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
	</div>

</div>

