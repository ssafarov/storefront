<?php
/**
 * Currency Switcher Partial
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>


<div class="currency-selector">
	<div class="row">
		<div class="col-sm-12 col-md-4">
			<h4><?php _e( 'Switch Currency?', 'storefront' ); ?></h4>
		</div>
		<div class="col-sm-12 col-md-8 text-right">
			<?php echo do_shortcode( '[aelia_currency_selector_widget title="" widget_type="buttons"]' ); ?>
		</div>
	</div>
</div>

<div class="row">
	<div>

