<?php
/*
Template Name: System Registration Process
*/
?>


<?php get_header(); ?>

<?php
$email = isset($_POST["email"]) ? $_POST["email"] : '';
// echo '*****For testing purposes only:'; echo $email;

// Validations
if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
	$hasError = true;
}
?>

<main class="site-main">
	<div class="container">
		<div class="row">
			<?php if ( email_exists( $email ) ) : ?>
				<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2" id="wpmem_login">
					<div class="hentry">
						<form name="loginform" id="loginform" action="<?php echo site_url(); ?>/wp-login.php" method="post">


							<h1><?php _e('Login here:', 'storefront'); ?></h1>
							<hr><br>


							<div class="form-group">
								<label for="user_login"><?php _e('Please enter your registered email or usernam *:', 'storefront'); ?></label>
								<div class="form-control-wrapper">
									<input data-validation="required" data-validation="required" type="text" name="log" id="user_login" class="form-control input-primary" value="" size="20">
								</div>
							</div>

							<div class="form-group">
								<label for="user_pass"><?php _e( 'Please enter your password *', 'storefront' ) ?></label>
								<div class="form-control-wrapper">
									<input data-validation="required" type="password" name="pwd" id="user_pass" class="form-control input-primary" value="" size="20">
								</div>
							</div>

							<!-- <div class="form-group">
								<label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember Me</label></p>
							</div> -->

							<?php if ( $hasError ) : ?>
								<div class="form-control-wrapper">
									<div class="alert alert-danger" role="alert">
										<?php _e( 'Incorrect details entered, please enter a valid username and/or password', 'storefront' ); ?>
									</div>
								</div>
							<?php endif; ?>

							<div class="form-group">
								<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary" value="Login">
								<a href="<?php echo home_url('reset-password'); ?>" class="button button-secondary"><?php _e('Forgot / don\'t know your password?', 'storefront'); ?></a>

								<input type="hidden" name="redirect_to" value="<?php print site_url(); ?>/portal/" />
							</div>
						</form>

						<br><br>
						<h3><?php _e('Don\'t have an account?', 'storefront'); ?></h3>
				 		<a href="<?php echo home_url('register', 'storefront'); ?>" class="btn btn-primary"><?php _e('Please register here', 'storefront'); ?></a>
					</div>
				</div>

			<?php elseif ( $email == '' ) : ?>

				<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2" id="wpmem_login">
					<div class="hentry">
						<form name="loginform" id="loginform" action="<?php echo site_url(); ?>/wp-login.php" method="post">

							<h1><?php _e('Login', 'storefront'); ?></h1>
							<hr><br>

							<div class="form-group">
								<label for="user_login"><?php _e('Please enter your registered email or username *', 'storefront'); ?></label>
								<div class="form-control-wrapper">
									<input data-validation="required" data-validation="required" type="text" name="log" id="user_login" class="form-control input-primary" value="" size="20">
								</div>
							</div>

							<div class="form-group">
								<label for="user_pass"><?php _e( 'Please enter your password *', 'storefront' ) ?></label>
								<div class="form-control-wrapper">
									<input data-validation="required" type="password" name="pwd" id="user_pass" class="form-control input-primary" value="" size="20">
								</div>
							</div>

							<!-- <label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Remember Me</label> -->


							<?php if ( isset($hasError) && $hasError ) : ?>
								<div class="form-control-wrapper">
									<div class="alert alert-danger" role="alert">
										<?php _e( 'Incorrect details entered, please enter a valid username and/or password', 'storefront' ); ?>
									</div>
								</div>
							<?php endif; ?>


							<div class="form-group">
								<input class="btn btn-primary" type="submit" name="wp-submit" id="wp-submit" value="<?php _e( 'Log In', 'storefront' ); ?>">
								<!-- <input type="hidden" name="redirect_to" value="--><?php //print site_url(); ?><!--/portal/" />-->
								<a href="<?php echo home_url('reset-password'); ?>" class="btn btn-secondary"><?php _e('Forgot / don\'t know your password?', 'storefront'); ?></a>
							</div>

						</form>

						<br>
						<h3><?php _e('Don\'t have an account?', 'storefront'); ?></h3>
				 		<a href="<?php echo home_url('register'); ?>" class="btn btn-primary"><?php _e('Please register here', 'storefront'); ?></a>
				 	</div>
				</div>
			<?php else : ?>

				<h4 style="text-align: center; display:none;"><?php _e( 'Register your device / Download your software', 'storefront' ); ?></h4>

				<script>
					window.location.replace("<?php print site_url(); ?>/wp-signup.php");
				</script>

			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>