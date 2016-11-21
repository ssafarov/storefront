<?php
/**
 * Review order delivery
 *
 * @author 		Sergey Safarov
 * @package 	Fuel-3D
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<style>
    .customer, .address, .downloadable {
        padding: 20px;
    }
    .customer {
        font-weight: bold;
        padding-bottom: 0;
    }
    .address {
        font-size: 80%;
    }
</style>

<div class="addresses_block">
    <div class="shipping_info">
        <div class="title"><h3><?php _e( 'Shipping Details', 'storefront' ); ?></h3></div>
        <?php if ( WC()->cart->needs_shipping_address() === true ): ?>
        <div class="container-fluid customer"></div>
        <div class="container-fluid address"></div>
        <?php else : ?>
        <div class="container-fluid downloadable"><span><?php _e( 'Your product will be available to download once you complete your order.', 'storefront' ); ?></span></div>
        <?php endif; ?>
        <div class="container-fluid shipping">
            <?php //do_action('display_custom_shipping'); ?>
        </div>
    </div>
    <div class="billing_info">
        <div class="title"><h3><?php _e( 'Billing Details', 'storefront' ); ?></h3></div>
        <div class="container-fluid customer"></div>
        <div class="container-fluid address"></div>
        <?php
//        if (getBlogIdByIp() == 4) {
            do_action('taxexempt_after_billing_details');
//        }
        ?>
        <?php do_action('woocommerce_checkout_order_review_vat_info'); ?>
    </div>
</div>


<script type="application/javascript">
        var modalTerms = "#modalTerms",
            modalRightOfReturn = "#modalRightOfReturn",
            linkTerms = '.terms-of-sale',
            linkRightOfReturn = '.right-of-return';

        jQuery(linkTerms).on('click', function () {
            jQuery(modalTerms).modal('show');
        });

        jQuery(linkRightOfReturn).on('click', function () {
            jQuery(modalRightOfReturn).modal('show');
        });

</script>
