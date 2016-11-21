<?php
	get_template_part('header-fullwidth');

	/**
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' );
?>


<?php do_action( 'storefront_content_top' ); ?>
