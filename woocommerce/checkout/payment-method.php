<?php
/**
 * Output a single payment method
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<style>
	@media (max-width: 768px) {
		.payment_method_stripe > img {
			float: none;
		}

		.wizard.clearfix.three-steps ul[aria-label='Pagination'] {
			display: block;
			width: 76% !important;
			margin: 0 auto !important;
		}

		.wizard.clearfix.three-steps > .actions > ul > li:first-child > a {
			width: 230px;
			float: none !important;
		}

		.payment_method_stripe label[for="payment_method_stripe"] {
			margin-left: 24px;
			font-size: 16px !important;
			line-height: 1.6;
			width: 200px;
			margin-bottom: 25px !important;
		}
		.payment_method_stripe img[alt="Mastercard"] {
			display: inline-block;
		}

		.payment_method_stripe img[alt="Visa"] {
			margin-left: 44px;
			display: inline-block;
		}

		.payment_method_stripe img[alt="Amex"] {
			display: inline-block;
		}

		.payment_box.payment_method_stripe fieldset p:not(.form-row) {
			position: relative;
			top: -51px;
			left: 44px;
			color: #888;
		}

		#payment {
			border: 2px solid #9ac31c;
			background: #fafafa;
		}

		#payment_method_stripe {
			position: relative;
			top: 10px;
			left:15px;
		}

		#stripe-cc-form .form-row.form-row-wide {
			border-top: 2px #eee solid;
			padding-top: 15px;
		}

		#stripe-cc-form .form-row.form-row-first {
			margin-top: 78px;
		}

		#stripe-cc-form .form-row.form-row-last {
			margin-top: 78px;
		}

		.text-right.termslink {
			text-align: left;
			padding-top: 0;
			margin-top: 0;
		}
	}
</style>
<li class="payment_method_<?php echo $gateway->id; ?>">
	<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo $gateway->id; ?>">
		<?php echo $gateway->get_title(); ?>
	</label>
	<?php echo $gateway->get_icon(); ?>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo $gateway->id; ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
