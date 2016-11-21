<?php
/**
 * Currency Switcher Partial
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>


<div class="country-selector">
	<div class="row">
		<div class="col-sm-12">
			<h4><?php _e( 'Choose your country', 'storefront' ); ?></h4>

			<?php echo do_shortcode( '[aelia_cs_billing_country_selector_widget title=" " widget_type="dropdown"]' ); ?>
		</div>
	</div>
</div>