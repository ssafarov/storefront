<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page
 *
 * @author    WooThemes
 * @package   WooCommerce/Templates
 * @version   2.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<?php get_header(); ?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 */
	do_action( 'woocommerce_before_main_content' );
?>

<div class="well customer-home clearfix">
	<div id="distributorTabs" role="tabpanel" class="customer-content clearfix">
		<div class="tab-content clearfix">
			<div class="row tab-pane active" role="tabpanel">
				<div class="col-sm-12">
					<?php wc_print_notices(); ?>

					<div class="row">
						<div class="col-md-3 col-md-push-9">
							<a class="btn btn-secondary pull-right" href="/portal/" title="Back to portal"><?php _e('Back to portal', 'storefront'); ?></a>
						</div>

						<div class="col-md-9 col-md-pull-3">
							<p class="order-info"><?php printf( __( 'Order <mark class="order-number">%s</mark> was placed on <mark class="order-date">%s</mark> and is currently <mark class="order-status">%s</mark>.', 'woocommerce' ), $order->get_order_number(), date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ), __( $order->get_status(), 'woocommerce' ) ); ?></p>
						</div>
					</div>

					<?php if ( $notes = $order->get_customer_order_notes() ) :
						?>
						<h2><?php _e( 'Order Updates', 'storefront' ); ?></h2>
						<ol class="commentlist notes">
							<?php foreach ( $notes as $note ) : ?>
							<li class="comment note">
								<div class="comment_container">
									<div class="comment-text">
										<p class="meta"><?php echo date_i18n( __( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); ?></p>
										<div class="description">
											<?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
										</div>
										<div class="clear"></div>
									</div>
									<div class="clear"></div>
								</div>
							</li>
							<?php endforeach; ?>
						</ol>
						<?php
					endif;

					do_action( 'woocommerce_view_order', $order_id ); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 */
	do_action( 'woocommerce_after_main_content' );
?>

<?php get_footer();