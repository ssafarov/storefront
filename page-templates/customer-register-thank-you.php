<?php get_header(); ?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 */
	do_action( 'woocommerce_before_main_content' );
?>

<div class="row">
	<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
		<div class="distributor-login clearfix">
			<div class="well">
				<div class="tab-content">
					<h2><?php printf( __( '<strong style="font-weight:400;">%s</strong> is your new username' ), $_POST['user_name']) ?></h2>
					<p><?php _e( 'But, before you can start using your new username, <strong>you must activate it</strong>.', 'storefront' ) ?></p>
					<p><?php printf( __( 'Check your inbox at <strong>%s</strong> and click the link given.' ), $_POST['user_email'] ); ?></p>
					<p><?php _e( 'If you do not activate your username within two days, you will have to sign up again.', 'storefront' ); ?></p>
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

<?php get_footer(); ?>