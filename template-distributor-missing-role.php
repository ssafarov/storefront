<?php get_header(); ?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 */
	do_action( 'woocommerce_before_main_content' );
?>
	
<p><?php _e('It seems you are not registered as a distributor.', 'storefront'); ?></p>
<p><?php _e('If you want to apply to be one, please', 'storefront'); ?> <a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e('log out', 'storefront'); ?></a> <?php _e('and refresh this page to access the applicants form.', 'storefront'); ?></p>


<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 */
	do_action( 'woocommerce_after_main_content' );
?>

<?php get_footer(); ?>