<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//TODO SS Remove after stage
//$order = new WC_Order(166369);

?>

<style>
	.cell {
		padding: 15px 0!important;
	}
	.cart__actions .cell{
		padding: 15px!important;
	}
	.payment_form, .shop_table, .shipping_info, .billing_info, .thankyou_info, .customer_details {
		border-collapse:separate;
		border: 1px solid #ccc;
		border-radius: 5px;
		margin-bottom: 20px;
		margin-top: 20px;
	}
	.thankyou_info h3 div {
		padding: 7px 0 0 35px;
	}

	.thankyou_info .amount {
		color: #9ac31c;
		font-weight: bold;
		font-size: 105%;
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
	.title h2, .footer h2 {
		padding: 20px 20px 10px 20px;
	}
	.title h2 img {
		margin: 5px 10px;
	}
	.add_info_block .title, .payment_block .title {
		margin-left: -35px;
		margin-right: -35px;
		border-top: 1px solid #ccc;
	}
	.title h3, .footer h3, .title h4, .footer h4 {
		padding: 20px 20px 10px 10px;
		font-size: 2.2rem;
	}
	.title h4, .footer h4 {
		padding: 20px 0 0 0;
		font-size: 1.7rem;
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

	.cart-grand-total th {
		text-align: left;
		vertical-align: middle;
	}
	.cart-grand-total td {
		text-align: right;
		vertical-align: middle;
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

	.orderInfo_block {
		margin: 20px;
	}
    .order_info h4 {
        font-family: "Lato",sans-serif;
        margin: 20px 0;
	    font-weight: 300;
    }
    /*.thankyou_info .title {*/
		/*border-bottom: 1px solid #ccc;*/
    /*}*/
	.thankyou_info .title h2 {
		border-top: 1px solid #ccc;
	}
	.saveUserInfo_form{
		width: 780px;
		margin: 5px auto 20px auto;
	}
	.order_notes_info{
		padding: 20px;
	}

	@media (max-width: 991px) {
		.shop_table .thead div.product-price, .shop_table .tbody div.product-price, .totals {
			text-align: left;
		}
		.shop_table .tbody div.product-price, .totals {
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
		.addresses_block, .woocommerce-checkout-review-order-table, .payment_block {
			margin: 0;
		}
	}

	@media (max-width: 768px) {
        .continue-shopping {
            font-size: 14px;
        }

        .continue-shopping  .fa-angle-right {
            font-size: 17px;
            font-weight: bold;
            margin-left: 15px;
        }

	    .wizard.three-steps > .steps > ul > li {
	        width:33.3%;
	    }
        .thankyou_info .title h2 div {
            font-size: 18px;
            font-weight: bold;
        }

        .thankyou_info .title .block_thankyou_info  {
            border-top: none;
        }

        .order_info.container_fluid h4{
            font-size: 16px;
            font-weight: 500;
         }

         .thankyou_info .title{
             border-bottom: 2px solid #ccc;
        }

        .orderInfo_block {
            margin-left: 0;
        }

        .order_details .date strong {
            margin-left: 20px;
        }

        .order_details .total strong {
            margin-left: 20px;
        }

        .order_details .method strong {
            margin-left: 20px;
        }

        .thankyou_info .title h2 {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #ccc;
        }

        .thankyou_info .title h2 img {
            margin: 0 10px;
        }

        .woocommerce-checkout-review-order-table {
            padding: 0 15px;
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
	}
</style>

<?php

if ( $order ) : ?>

	<?php
		$jsOrderCurrencySign = $order->get_order_currency();
		$jsOrderTotal = $order->get_total();
		$jsOrderId = $order->get_order_number();
		$jsOrderCurr = $order->get_order_currency();
	?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'storefront' ); ?></p>

		<p
		<?php
			if ( is_user_logged_in() )
				{_e( 'Please attempt your purchase again or go to your account page.', 'storefront' );}
			else
				{_e( 'Please attempt your purchase again.', 'storefront' );}
		?>
		</p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'storefront' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'storefront' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>
        <div class="row">
            <div class="col-xs-4 col-sm-9 xs-title-checkout">
                <h1><?php _e( 'Checkout', 'storefront' ); ?></h1>
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
        <div class="wizard clearfix three-steps container_fluid" role="application">

			<div class="steps clearfix">
				<ul role="tablist">
					<li aria-selected="false" aria-disabled="false" class="first done" role="tab">
						<a id="wizard-t-0" href="#wizard-h-0" aria-controls="wizard-p-0"><span class="number">1</span> Shipping &amp; Billing Details</a>
					</li>
					<li aria-selected="true" aria-disabled="false" class="last done" role="tab">
						<a id="wizard-t-1" href="#wizard-h-1" aria-controls="wizard-p-1"><span class="number">2</span> Summary &amp; Payment</a>
					</li>
					<li aria-selected="false" aria-disabled="true" class="done custom" role="tab">
						<a id="wizard-custom" href="#" aria-controls="wizard-p-3"><span class="number">3</span>Order Complete</a>
                    </li>
                </ul>
            </div>

            <div class="thankyou_info">
                <div class="title ">
                    <h2 class="block_thankyou_info">
	                    <img align="left" src="<?php echo get_template_directory_uri() . '/images/checkout/dmark.png" border="0"'; ?>"/>
	                    <div><?php _e( 'Order Received', 'storefront' ); ?></div>
                    </h2>
                </div>

                <div class="orderInfo_block container_fluid col-xs-12">
                    <div class="order_info container_fluid">

                        <h4><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'storefront' ), $order ); ?></h4>
                        <ul class="order_details">
                            <li class="order">
                                <?php _e( 'Order Number:', 'storefront' ); ?>
                                <strong><?php echo $order->get_order_number(); ?></strong>
                            </li>
                            <li class="date">
                                <?php _e( 'Date:', 'storefront' ); ?>
                                <strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
                            </li>
                            <li class="total">
                                <?php _e( 'Total:', 'storefront' ); ?>
                                <strong><?php echo $order->get_formatted_order_total('incl'); ?></strong>
                            </li>
                            <?php if ( $order->payment_method_title ) : ?>
                                <li class="method">
                                    <?php _e( 'Payment Method:', 'storefront' ); ?>
                                    <strong><?php echo $order->payment_method_title; ?></strong>
                                </li>
                            <?php endif; ?>
	                        <li class="crr">
		                        <?php if ( isUsaShop() ) : ?>
			                        <a target="_blank" href="<?php echo get_page_link(3332); ?>"><?php _e( 'Consumer Right of Return', 'storefront' ); ?></a>
		                        <?php else: ?>
			                        <a target="_blank" href="<?php echo get_page_link(2832); ?>"><?php _e( 'Consumer Right of Return', 'storefront' ); ?></a>
		                        <?php endif; ?>
	                        </li>
                        </ul>

                    </div>
                </div>


                <div class="clear"></div>
                <?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
                <div class="clear"></div>
                <?php do_action( 'woocommerce_thankyou', $order->id ); ?>
                <div class="clear"></div>
            </div>
        </div>

		<div class="row">
			<div class="col-sm-offset-9 col-sm-3">
				<a class="btn btn-primary continue-shopping" href="/shop/">CONTINUE SHOPPING<i class="nav-control-link-icon fa fa-angle-right"></i></a>
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

        <script type="text/javascript">
			var hostProtocol = (("https:" == document.location.protocol) ? "https" : "http");
			document.write('<scr'+'ipt src="', hostProtocol+'://105.xg4ken.com/media/getpx.php?cid=d0fada8c-1a4d-4104-a9ff-747e356f7b64','" type="text/JavaScript"><\/scr'+'ipt>');

			var params = new Array();
			params0='id=d0fada8c-1a4d-4104-a9ff-747e356f7b64';
			params1='type=conv';
			params2='val=<?php echo $jsOrderTotal; ?>';
			params3='orderId=<?php echo $jsOrderId; ?>';
			params4='promoCode=';
			params5='valueCurrency=<?php echo $jsOrderCurr; ?>';
			params6='GCID='; //For Live Tracking only
			params7='kw='; //For Live Tracking only
			params8='product='; //For Live Tracking only
			k_trackevent(params,'105');

			jQuery( document ).ready(function (){
				modal_dialog_open();
			});
		</script>

        <!-- Google Code for Sale Conversion Page -->
        <script type="text/javascript">
			/* <![CDATA[ */
			var google_conversion_id = 951444386;
			var google_conversion_language = "en";
			var google_conversion_format = "3";
			var google_conversion_color = "ffffff";
			var google_conversion_label = "8p_8CPrPz1sQosfXxQM";

			var google_conversion_value = "<?php echo $jsOrderTotal; ?>";
			var google_conversion_currency = "<?php echo $jsOrderCurrencySign; ?>";

			var google_remarketing_only = false;
			/* ]]> */
		</script>
        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>

		<?= str_replace('{{order_total}}', $jsOrderTotal, apply_filters('get_custom_option', '', 'checkout_thank_you_after')); ?>

	<?php endif; ?>

	<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/951444386/?label=8p_8CPrPz1sQosfXxQM&guid=ON&script=0"/>
			<img src="https://105.xg4ken.com/media/redir.php?track=1&token=d0fada8c-1a4d-4104-a9ff-747e356f7b64&type=conv&val=0.0&orderId=&promoCode=&valueCurrency=USD&GCID=&kw=&product=" width="1" height="1"/>
		</div>
	</noscript>


<?php else : ?>

	<p>
	    <?php
	        echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Something goes wrong. We were unable to determine which order do you mean.', 'woocommerce' ), null );
        ?>
    </p>

<?php endif; ?>