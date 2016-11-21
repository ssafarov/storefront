<style>
    .text-right.termslink {
        padding-top: 13px;
    }

    @media (max-width: 768px) {
        .payment_block .subhead {
            font-size: 18px;
            margin-left: 15px;
            margin-bottom: 0;
        }
        #payment {
            border: 2px solid #9ac31c;
        }
    }
</style>
<?php
/**
 * Checkout Payment Section
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="payment_block">
        <div class="row">
            <div class="splitter" style="width: 120%!important"></div>
            <div class="col-xs-12">
                <h3 class="subhead"><?php _e( 'Payment Details', 'storefront' ); ?></h3>
            </div>
            <div class="splitter" style="width: 120%!important"></div>
        </div>
        <?php if ( ! is_ajax() ) : ?>
            <?php do_action( 'woocommerce_review_order_before_payment' ); ?>
        <?php endif; ?>

        <div id="payment" class="container-fluid payment_form">
            <?php if ( WC()->cart->needs_payment() ) : ?>
            <ul class="payment_methods methods">
                <?php
                    if ( sizeof( $available_gateways ) ) {
                        current( $available_gateways )->set_current();
                    }
                    if ( ! empty( $available_gateways ) ) {
                        foreach ( $available_gateways as $gateway ) {
                            wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                        }
                    } else {
                        if ( ! WC()->customer->get_country() ) {
                            $no_gateways_message = __( 'Please fill in your details above to see available payment methods.', 'woocommerce' );
                        } else {
                            $no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' );
                        }

                        echo '<li>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</li>';
                    }
                ?>
            </ul>
            <?php endif; ?>

            <div class="form-row place-order">

                <noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?><br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>" /></noscript>

                <?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

                <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

                <?php echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="hidden" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>

                <?php if (!is_user_logged_in()) : ?>
                    <input type="hidden" name="account_password" value="0"/>
                <?php endif; ?>

                <?php
                $current_blog_id = $GLOBALS['blog_id'];
                if ($current_blog_id == 4):
                    // USA
                    ?>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">

                            <p class="checkbox terms">
                                <label for="terms" class="checkbox">
                                    <input type="checkbox" class="input-checkbox" name="terms" <?php checked(apply_filters('woocommerce_terms_is_checked_default',
                                        isset($_POST['terms'])), true); ?> id="terms"/>
                                    <?php printf(__('I&rsquo;ve read and accept the <a class="terms-of-sale" href="javascript:void(0)">terms &amp; conditions</a>', 'woocommerce'),
                                        esc_url(get_permalink(wc_get_page_id('terms')))); ?></label>
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <p class="text-right termslink">
                                <a  class="right-of-return" href="javascript:void(0)"><?php _e('Consumer right of return and refund', 'storefront'); ?></a>
                            </p>
                        </div>
                    </div>


                <?php else:
                    // all others incl. UK
                    if (wc_get_page_id('terms') > 0 && apply_filters('woocommerce_checkout_show_terms', true)) : ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <p class="checkbox terms">
                                    <label for="terms" class="checkbox" id="terms-and-conds">
                                        <input type="checkbox" class="input-checkbox" name="terms" id="terms"/>
                                        <?php printf(__('I&rsquo;ve read and accept the <a class="terms-of-sale" href="javascript:void(0)">terms &amp; conditions</a>', 'woocommerce'),
                                            esc_url(get_permalink(wc_get_page_id('terms')))); ?>
                                    </label>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="text-right termslink">
                                    <a  class="right-of-return" href="javascript:void(0)"><?php _e('Consumer right of return and refund', 'storefront'); ?></a>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php do_action( 'woocommerce_review_order_after_submit' ); ?>
            </div>

            <div class="clear"></div>
        </div>

        <?php if ( ! is_ajax() ) : ?>
            <?php do_action( 'woocommerce_review_order_after_payment' ); ?>
        <?php endif; ?>
</div>
