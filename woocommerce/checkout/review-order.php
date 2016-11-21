<?php
/**
 * Review order table
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

WC()->cart->tax_display_cart = 'excl';

?>
<style>
	@media (max-width: 768px) {
		.col-xs-12.cart-total-xs {
			padding: 0;
		}

		#grand_totals .cart-subtotal {
			border-bottom: 1px #eee solid;
		}

		#grand_totals .shipping {
			border-bottom: 1px #eee solid;
		}

		.addresses_block .shipping_info h3, .addresses_block .billing_info h3 {
			font-size: 14px;
		}

		#grand_totals .shipping .title {
			border-bottom: none;
		}

		#grand_totals .tax-rate{
			border-bottom: 1px #eee solid;
		}

		#grand_totals .order-total th{
			font-weight: 500;
		}

		.footer{
			border-top: 2px solid #ccc;
		}

		.shop_table .title-xs {
			font-size: 14px;
		}
	}

</style>
<div class="woocommerce-checkout-review-order-table">

	<div class="container-fluid">
		<div class="row shop_table">
			<div class="col-xs-12 title">
				<h3 class="title-xs"><?php _e( 'Order Details', 'storefront' ); ?></h3>
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
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) )
						{
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
													echo '<span class="product-description"><strong>';
													echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
													echo WC()->cart->get_item_data( $cart_item ) . '</strong><br/>';
													echo apply_filters( 'woocommerce_checkout_cart_item_quantity', __( 'Qty ', 'storefront') . sprintf( ' %s', $cart_item['quantity'] ), $cart_item, $cart_item_key );
													echo '</span>';
												?>
												<div class="product-price visible-xs" style="text-align: left;padding: 0 !important;">
													<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-6 col-sm-7 col-md-3 product-price hidden-xs">
									<div class="cell">
										<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>

					<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

					<div class="row totals">
						<div class="col-xs-12 col-sm-offset-6 col-sm-6 no-right-padding" id="grand_totals">
							<div class="row">
								<div class="col-xs-12 cart-total-xs">
									<?php woocommerce_cart_totals(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 footer">
				<?php do_action( 'woocommerce_review_order_coupon_insert' ); ?>
			</div>
		</div>
	</div>
</div>