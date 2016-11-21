<?php
/**
 * Cart Page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//wc_print_notices();
WC()->cart->tax_display_cart = 'excl';

do_action( 'woocommerce_before_cart' ); ?>

<script>
    jQuery(function ($) {

        // Orderby
        $('.woocommerce-ordering').on('change', 'select.orderby', function () {
            $(this).closest('form').submit();
        });

        // Quantity buttons
        $('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)').addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');

        // Target quantity inputs on product pages
        $('input.qty:not(.product-quantity input.qty)').each(function () {
            var min = parseFloat($(this).attr('min'));

            if (min && min > 0 && parseFloat($(this).val()) < min) {
                $(this).val(min);
            }
        });

        $(document).delegate('.plus, .minus, .remove', 'click', function () {
            // Get values
            var $qty = $(this).closest('.cart_item').find('.qty');

            if ($qty.length == 0){
                $qty = $(this).closest('.cart_item').find('.qty-cross');
            }



            var currentVal = parseFloat($qty.val()),
                max = parseFloat($qty.attr('max')),
                min = parseFloat($qty.attr('min')),
                step = $qty.attr('step');

            // Format values
            if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
            if (max === '' || max === 'NaN') max = '';
            if (min === '' || min === 'NaN') min = 0;
            if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

            if ($(this).is('.remove')) {
                // remove the value
                $qty.val(0);
            } else if ($(this).is('.plus')) {
                // Change the value
                if (max && ( max == currentVal || currentVal > max )) {
                    $qty.val(max);
                } else {
                    $qty.val(currentVal + parseFloat(step));
                }
            } else if ($(this).is('.minus')) {

                if (min && ( min == currentVal || currentVal < min )) {
                    $qty.val(min);
                } else if (currentVal > 0) {
                    $qty.val(currentVal - parseFloat(step));
                }
            }
            // Trigger change event
            $qty.trigger('change');
        });

        $(document).on('click', '.checkout_button', function (event) {
            event.preventDefault;
            event.stopPropagation;
            var $form = $('form[name="cart_form"]');
            $("<input type='hidden' name='proceed' value='1'>").appendTo($form);
            $form.submit();
            return false;
        });

        $(document).on('change', '.qty-cross', function () {
            $(this).closest('.product-meta').find('.add_to_cart_button').attr('data-quantity', $(this).val());
        });

        var $siteMain = $('.site-main');
        var checkoutButton = '<div style="padding:0 0 30px 0px;"><a href="<?php  WC()->cart->get_checkout_url(); ?>" class="btn btn-block btn-primary checkout_button wc-forward" style="padding: 5px;"><i class="nav-control-link-icon fa fa-lock"></i><span><?php _e( 'Checkout securely', 'storefront' ); ?></span><i class="nav-control-link-icon fa fa-angle-right"></i></a></div>';
        $siteMain.find('h1').each(function(){
            $(this).replaceWith( "<h3 style='margin-top:10px;'>" + $(this).html() + "</h3>" );
        });
        $siteMain.find('.splitter').remove();
        $siteMain.find('.row').find ('div').first().removeClass ('col-md-12').addClass('col-sm-8 col-md-9');
        $siteMain.find('.row').first().append('<div class="hidden-xs col-sm-4 col-md-3" id="chk_top">'+checkoutButton+'</div>');
    });
</script>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" name="cart_form" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

    <style>
        .cell {
            padding: 15px 0!important;
        }
        .cart__actions .cell{
            padding: 15px!important;
        }
        .shop_table {
            border-collapse:separate;
            border: 1px solid #eeeeee;
            border-radius: 5px;
        }
        .thead, .tbody {
            padding: 0;
            border-bottom: 2px solid #dedede;
        }
        .shop_table .thead div {
            color: #9ac31c;
            background-color: #fafafa;
            font-weight: bold!important;
        }
        .shop_table .thead div, .shop_table .tbody .cart_item, .totals {
            font-weight: 400;
            padding: 10px 0;
            font-size: 1.8rem;
            vertical-align: middle;
        }

        .shop_table .thead div.product-thumbnail {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        .shop_table .thead div.product-thumbnail, .shop_table .tbody div.product-thumbnail {
            padding-left: 40px!important;
        }
        .shop_table .thead div.product-price, .shop_table .tbody div.product-price {
            padding-right: 40px!important;
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

        .cart-grand-total th {
            text-align: left;
            vertical-align: middle;
        }
        .cart-grand-total td {
            text-align: right;
            vertical-align: middle;
            padding-right: 0!important;
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
            color: #c20e34!important;
            font-size: 75%;
            font-weight: bold;
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
            color: #000!important;
        }
        .cart_totals .amount {
            color: #9ac31c!important;
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

        .wc-backward .left-arrow {
            vertical-align: text-top;
            display: inline-block;
        }

        .wc-backward span {
            margin-left: 10px;
        }

        .col-md-8.cell.back_to_shop {
            padding-left: 40px !important;
        }
        @media (max-width: 991px) {
            .shop_table .thead div.product-price, .shop_table .tbody div.product-price, .totals {
                text-align: left;
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
        }
    </style>

    <div class="container-fluid">
        <div class="row cart shop_table">
            <div class="container-fluid thead">
                <div class="col-sm-12 col-md-7 product-thumbnail"><?php _e( 'Items', 'storefront' ); ?></div>
                <div class="hidden-xs hidden-sm col-md-3 product-quantity"><?php _e( 'Quantity', 'storefront' ); ?></div>
                <div class="hidden-xs hidden-sm col-md-2 product-price"><?php _e( 'Price', 'storefront' ); ?></div>
            </div>
            <div class="tbody container-fluid">
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $_product_id  = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        ?>
                        <div class="row <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                            <div class="col-xs-12 col-sm-12 col-md-7">
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-3 product-thumbnail cell">
                                        <?php
                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image([100, 100]), $cart_item, $cart_item_key );

                                        if ( ! $_product->is_visible() ) {
                                            echo $thumbnail;
                                        } else {
                                            printf( '<a href="%s" class="thumbnail_image">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail );
                                        }
                                        ?>
                                    </div>
                                    <div class="col-xs-8 col-sm-8 col-md-9 product-name cell">
                                        <div class="cell">
                                        <?php
                                        if ( ! $_product->is_visible() ) {
                                            echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
                                        } else {
                                            echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s </a>', esc_url( $_product->get_permalink( $cart_item ) ), $_product->get_title() ), $cart_item, $cart_item_key );
                                        }

                                        // Meta data
                                        echo WC()->cart->get_item_data( $cart_item );

                                        // Backorder notification
                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                            echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
                                        }

                                        // Remove mark
                                        echo sprintf(
                                            '<br/><a href="#" class="remove" title="%s" data-product_key="%s" data-product_id="%s" data-product_sku="%s">&times; Remove</a>',
                                            __( 'Remove this item', 'woocommerce' ),
                                            esc_attr( $cart_item_key ),
                                            esc_attr( $_product_id ),
                                            esc_attr( $_product->get_sku() )
                                        );
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3 product-quantity no-left-padding cell">
                                <div class="cell">

                                <?php
                                if ( $_product->is_sold_individually() ) {
                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" class="qty" />', $cart_item_key );
                                } else {
                                    $product_quantity = woocommerce_quantity_input( array(
                                        'input_name'  => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                        'min_value'   => '0'
                                    ), $_product, false );
                                }

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                ?>
                                    </div>
                            </div>

                            <div class="col-xs-6 col-sm-7 col-md-2 product-price cell">
                                <div class="cell">
                                    <?php
                                        WC()->cart->tax_display_cart = 'excl';
                                        //$_tax = new WC_Tax();//looking for appropriate vat for specific product
                                        //$rates = array_shift($_tax->get_rates( $_product->get_tax_class() ));

                                        echo apply_filters( 'woocommerce_cart_item_price',  WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

                                        //if (!empty($rates)){
                                        //
                                        //    if (isset($rates['rate'])) { //vat found
                                        //        if ($rates['rate'] == 0) { //if 0% vat
                                        //            $prima_tax_rate='0% VAT.';
                                        //        } else {
                                        //            $prima_tax_rate="Incl. ".round($rates['rate'])."% VAT. ";
                                        //        }
                                        //    } else {//FailSafe: just in case ;-)
                                        //        $prima_tax_rate='No tax rate found';
                                        //    }
                                        //    echo '<br/>' . '<span style="font-size:smaller; margin-left:0.6em;color:#808080">(' .$prima_tax_rate . ')</span>';
                                        //} else {
                                        //   /* echo "<br/><span style=\"font-size:smaller; margin-left:1em;\">".__('Exc. VAT', 'storefront')."</span>";*/
                                        //}
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }

                do_action( 'woocommerce_cart_contents' );
                do_action( 'woocommerce_after_cart_contents' );
                ?>
                <style>
                .note-cart {
                    background: #fafafa;
                    border: solid 1px #eeeeee;
                    color: #808080;
                    padding:  20px !important;
                }

                .note-cart p {
                    margin: 0;
                }
                </style>
                <div class="row totals">
                    <div class="col-md-5 col-sm-10 col-xs-10">
                        <?php if ( WC()->cart->get_cart_tax() ) : ?>
                            <div class="well col-md-10 col-xs-12 note-cart">
                                <p class="wc-cart-shipping-notice">
                                    <small>
                                        <strong><?php echo __('Note:', 'woocommerce')?></strong>
                                        <?php
                                            $estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
                                                ? sprintf( ' ' . __( ' (taxes estimated for %s)', 'woocommerce' ), WC()->countries->estimated_for_prefix() . __( WC()->countries->countries[ WC()->countries->get_base_country() ], 'woocommerce' ) )
                                                : '';
                                            printf( __( ' Shipping and taxes are estimated %s and will be updated during checkout based on your billing and shipping information.', 'storefront' ), $estimated_text );
                                        ?>
                                    </small></p>
                            </div>
                        <?php else : ?>
                            <div class="well col-md-10 col-xs-12 note-cart">
                                <p class="wc-cart-shipping-notice">
                                    <small>
                                        <strong><?php echo __('Note:', 'woocommerce')?></strong>
                                        <?php
                                            _e( ' Shipping and other taxes are not included at this step and will be updated later during the checkout process based on your billing and shipping information.', 'storefront' );
                                        ?>
                                    </small>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-1 col-hidden-sm"></div>
                    <div class="col-md-6 no-right-padding" id="grand_totals">
                        <div class="row">
                            <div class="col-md-12">
                                <?php woocommerce_cart_totals(); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row cart__actions border-top">
                <div class="col-md-4 cell col-md-push-8">
                    <a href="<?php  WC()->cart->get_checkout_url(); ?>" class="btn btn-block btn-primary checkout_button wc-forward">
                        <i class="nav-control-link-icon fa fa-lock"></i><span><?php _e( 'Checkout securely', 'storefront' ); ?></span><i class="nav-control-link-icon fa fa-angle-right"></i>
                    </a>
                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field( 'woocommerce-cart' ); ?>
                </div>
                <div class="col-md-8 cell back_to_shop col-md-pull-4">
                    <a class="wc-backward" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><img
                            class="left-arrow" src="<?php echo get_home_url().'/wp-content/themes/storefront/images/left-row.png'?>">
                        <span><?php _e('Continue Shopping', 'storefront') ?></span></a>
                </div>
            </div>
        </div>
    </div>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">
    <?php remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals'); ?>
	<?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
