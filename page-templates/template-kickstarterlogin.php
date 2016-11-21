<?php

/*

Template Name: Kickstarter Login

*/

// Validations
if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
	$hasError = true;
}

get_header(); ?>


<main class="site-main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2" id="wpmem_login">

				<div class="hentry">

					<form name="loginform" id="loginform" action="<?php echo site_url(); ?>/wp-login.php" method="post">
						<h1><?php _e('Kickstarter Beta-tester User Login', 'storefront') ?></h1>
						<hr>
						<br>
						<br>


						<div class="form-group">
							<label for="user_login"><?php _e( 'Email (please see your introductory letter) *', 'storefront' ); ?></label>
							<div class="form-control-wrapper">
								<input data-validation="email" type="text" name="log" id="user_login" class="form-control input-primary" value="" size="20">
							</div>
						</div>

						<div class="form-group">
							<label for="user_pass"><?php _e( 'Serial Number (please see reverse of your Fuel3D scanner) *', 'storefront' ); ?></label>
							<div class="form-control-wrapper">
								<input data-validation="required" type="text" name="pwd" id="user_pass" class="form-control input-primary" value="" size="20">
							</div>
						</div>

						<?php
						// <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Remember Me</label></p>
						?>


						<?php if ( $hasError ) : ?>

							<div class="form-control-wrapper">
								<div class="alert alert-danger" role="alert">
									<?php _e( 'Incorrect details entered, please enter a valid username and/or password', 'storefront' ); ?>
								</div>
							</div>
						<?php endif; ?>


						<div class="form-group">
							<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary" value="<?php _e( 'Log In', 'storefront' ); ?>">
							<input type="hidden" name="redirect_to" value="<?php print site_url(); ?>/kickstarter-account">
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>


</main>
<?php get_footer(); ?>