<?php
	get_header();

// Validations
// TODO: Have Server side validation fo each individual field
if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
	$hasError = true;
}
?>
	<?php
		/**
		 * storefront_before_content hook
		 *
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

<div class="row">
	<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">

		<div class="distributor-login clearfix">
			<div role="tabpanel">
				<ul class="tabs" role="tablist">
					<li role="presentation"<?php echo $activeTab == 1 ? ' class="active"' : ''?>>
						<a href="#login1" class="btn btn-primary btn-outline btn-round btn-sm" aria-controls="login1" role="tab" data-toggle="tab"><?php _e('Already Have An Account', 'storefront' ); ?></a>
					</li>

					<li role="presentation"<?php echo $activeTab == 2 ? ' class="active"' : ''?>>
						<a href="#login2" class="btn btn-primary btn-outline btn-round btn-sm" aria-controls="login2" role="tab" data-toggle="tab"><?php _e('Register An Account', 'storefront' ); ?></a>
					</li>
				</ul>

				<div class="well">
					<div class="tab-content">
						<div class="tab-pane<?php echo $activeTab == 1 ? ' active' : ''?>" role="tabpanel" id="login1">
							<form method="post" action="<?php echo site_url(); ?>/wp-login.php">
								<div class="form-group">
									<label for="email"><?php _e( 'Username or Email Address *', 'profile' ); ?></label>

									<div class="form-control-wrapper">
										<input data-validation="required" id="email" type="text" name="log" class="form-control input-primary">

									</div>
								</div>

								<div class="form-group">
									<label for="password"><?php _e( 'Password *', 'profile' ); ?></label>

									<div class="form-control-wrapper">
										<input data-validation="length" data-validation-length="min6" id="password" type="password" name="pwd" class="form-control input-primary">
									</div>
								</div>

                                <?php if (isset($hasError) && $hasError) : ?>
									<div class="form-group">
										<div class="form-control-wrapper">
											<div class="alert alert-danger" role="alert">
												<?php _e( 'Incorrect details entered, please enter a valid username and/or password', 'profile' ); ?>
											</div>
										</div>
									</div>
								<?php endif; ?>

								<input type="hidden" name="redirect_to" value="<?php echo home_url( 'portal' ); ?>">

								<button class="btn btn-primary"><?php _e('Login', 'storefront'); ?></button>

								<a href="<?php echo home_url('reset-password'); ?>" class="btn btn-secondary"><?php _e( 'Forgot / don\'t know your password?', 'storefront' ); ?></a>
							</form>
						</div>

						<div class="tab-pane<?php echo $activeTab == 2 ? ' active' : ''?>" role="tabpanel" id="login2">
							<form id="apply-form" action="?register" method="post" class="clearfix">
								<div class="form-group">
									<label for="username"><?php _e( 'Username *', 'storefront' ); ?></label>

									<?php if (isset($errors['user_name'])) { ?>
									<div class="form-control-wrapper has-error">
										<input data-validation="required" id="username" type="text" class="form-control input-primary error" name="user_name"
										       value="<?php echo isset($_POST['user_name']) ? $_POST['user_name'] : '' ?>">
										<span class="help-block form-error"><?php echo $errors['user_name'] ?></span>
									</div>
									<?php } else { ?>
									<div class="form-control-wrapper">
										<input data-validation="required" id="username" type="text" class="form-control input-primary" name="user_name"
										       value="<?php echo isset($_POST['user_name']) ? $_POST['user_name'] : '' ?>">
									</div>
									<?php } ?>
								</div>

								<div class="form-group">
									<?php if (isset($errors['user_password']) || isset($errors['user_password_confirm'])) { ?>
										<label for="userpassword"><?php _e( 'Password *', 'storefront' ); ?></label>
										<div class="form-control-wrapper has-error">
											<input data-validation="required" id="userpassword" type="password" class="form-control input-primary error" name="user_password" value="">
											<?php if (isset($errors['user_password'])) : ?>
												<span class="help-block form-error"><?php echo $errors['user_password'] ?></span>
											<?php endif; ?>
										</div>
										<label for="userpasswordconfirm"><?php _e( 'Confirm password *', 'storefront' ); ?></label>
										<div class="form-control-wrapper has-error">
											<input data-validation="required" id="userpasswordconfirm" type="password" class="form-control input-primary error" name="user_password_confirm" value="">
											<?php if (isset($errors['user_password_confirm'])) : ?>
												<span class="help-block form-error"><?php echo $errors['user_password_confirm'] ?></span>
											<?php endif; ?>
										</div>
									<?php } else { ?>
										<label for="userpassword"><?php _e( 'Password *', 'storefront' ); ?></label>
										<div class="form-control-wrapper">
											<input data-validation="required" id="userpassword" type="password" class="form-control input-primary" name="user_password" value="">
										</div>
										<label for="userpasswordconfirm"><?php _e( 'Confirm password *', 'storefront' ); ?></label>
										<div class="form-control-wrapper">
											<input data-validation="required" id="userpasswordconfirm" type="password" class="form-control input-primary" name="user_password_confirm" value="">
										</div>
									<?php } ?>
								</div>

								<div class="form-group">
									<label for="email2"><?php _e( 'Email Address *', 'storefront' ); ?></label>

									<?php if (isset($errors['user_email'])) { ?>
									<div class="form-control-wrapper has-error">
										<input data-validation="email" id="email2" type="email" class="form-control input-primary error" name="user_email"
										       value="<?php echo isset($_POST['user_email']) ? $_POST['user_email'] : '' ?>">
										<span class="help-block form-error"><?php echo $errors['user_email'] ?></span>
									</div>
									<?php } else { ?>
									<div class="form-control-wrapper">
										<input data-validation="email" id="email2" type="email" class="form-control input-primary" name="user_email"
										       value="<?php echo isset($_POST['user_email']) ? $_POST['user_email'] : '' ?>">
									</div>
									<?php } ?>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label for="fname"><?php _e( 'First Name *', 'storefront' ); ?></label>

											<?php if (isset($errors['first_name'])) { ?>
												<div class="form-control-wrapper has-error">
													<input data-validation="required" class="form-control input-primary error" name="first_name" type="text"
													       value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>">
													<span class="help-block form-error"><?php echo $errors['first_name'] ?></span>
												</div>
											<?php } else { ?>
												<div class="form-control-wrapper">
													<input data-validation="required" class="form-control input-primary" name="first_name" type="text"
													       value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>">
												</div>
											<?php } ?>
										</div>
									</div>
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label for="lname"><?php _e( 'Surname *', 'storefront' ); ?></label>

											<?php if (isset($errors['last_name'])) { ?>
												<div class="form-control-wrapper has-error">
													<input data-validation="required" class="form-control input-primary error" name="last_name" type="text"
													       value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>">
													<span class="help-block form-error"><?php echo $errors['last_name'] ?></span>
												</div>
											<?php } else { ?>
												<div class="form-control-wrapper">
													<input data-validation="required" class="form-control input-primary" name="last_name" type="text"
													       value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>">
												</div>
											<?php } ?>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label for="serial"><?php _e( 'Serial Number', 'storefront' ); ?></label>

											<?php if (isset($errors['invitation_code'])) { ?>
												<div class="form-control-wrapper has-error">
													<input id="serial" class="form-control input-primary error" name="invitation_code" type="text" v
													       alue="<?php echo isset($_POST['invitation_code']) ? $_POST['invitation_code'] : '' ?>">
													<span class="help-block form-error"><?php echo $errors['invitation_code'] ?></span>
												</div>
											<?php } else { ?>
												<div class="form-control-wrapper">
													<input id="serial" class="form-control input-primary" name="invitation_code" type="text"
													       value="<?php echo isset($_POST['invitation_code']) ? $_POST['invitation_code'] : '' ?>">
												</div>
											<?php } ?>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label for="where"><?php _e( 'Where did you buy SCANIFY?', 'storefront' ); ?></label>

											<div class="form-control-wrapper">
												<input data-validation="length" data-validation-length="0-250" id="where" class="form-control input-primary" name="where_buy"
												       value="<?php echo isset($_POST['where_buy']) ? $_POST['where_buy'] : '' ?>">
											</div>
										</div>
									</div>
								</div>

								<p>Problems registering? <a href="https://fuel3d.zendesk.com/" target="_blank"><?php _e('Click here for support', 'storefront' ); ?></a></p>

								<div class="form-group hidden" id="apply-response-error">
									<div class="form-control-wrapper">
										<div class="alert alert-danger" role="alert">

										</div>
									</div>
								</div>
								<input type="hidden" name="mc4wp-subscribe" value="1" />
								<button class="btn btn-primary pull-left"><?php _e( 'Register', 'storefront' ); ?></button>
							</form>

							<div class="alert alert-success hidden" id="apply-response" role="alert">
								<?php _e( 'Thank you for applying to be a distributor. We endeavour to respond to all applications within 2 weeks.', 'profile' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	<?php
		/**
		 * storefront_before_content hook
		 *
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
<?php get_footer(); ?>