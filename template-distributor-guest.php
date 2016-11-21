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
						<a href="#login1" class="btn btn-primary btn-outline btn-round btn-sm" aria-controls="login1" role="tab" data-toggle="tab"><?php _e('Already A Distributor', 'storefront'); ?></a>
					</li>

					<li role="presentation"<?php echo $activeTab == 2 ? ' class="active"' : ''?>>
						<a href="#login2" class="btn btn-primary btn-outline btn-round btn-sm" aria-controls="login2" role="tab" data-toggle="tab"><?php _e('Apply To Become A Distributor', 'storefront'); ?></a>
					</li>
				</ul>

				<div class="well">
					<div class="tab-content">
						<div class="tab-pane<?php echo $activeTab == 1 ? ' active' : ''?>" role="tabpanel" id="login1">
							<form method="post" action="<?php echo site_url(); ?>/wp-login.php">
								<div class="form-group">
									<label for="email"><?php _e( 'Username or Email Address', 'storefront' ); ?> *</label>

									<div class="form-control-wrapper">
										<input data-validation="required" id="email" type="text" name="log" class="form-control input-primary">

									</div>
								</div>

								<div class="form-group">
									<label for="password"><?php _e( 'Password', 'storefront' ); ?> *</label>

									<div class="form-control-wrapper">
										<input data-validation="length" data-validation-length="min6" id="password" type="password" name="pwd" class="form-control input-primary">
									</div>
								</div>

								<?php if ( $hasError ) : ?>
									<div class="form-group">
										<div class="form-control-wrapper">
											<div class="alert alert-danger" role="alert">
												<?php _e( 'Incorrect details entered, please enter a valid username and/or password', 'storefront' ); ?>
											</div>
										</div>
									</div>
								<?php endif; ?>

								<input type="hidden" name="redirect_to" value="<?php echo home_url( 'distributor-portal' ); ?>">

								<button class="btn btn-primary"><?php _e('Login', 'storefront'); ?></button>

								<a href="<?php echo home_url('reset-password'); ?>" class="btn btn-secondary"><?php _e( 'Forgot / don\'t know your password?', 'storefront' ); ?></a>
							</form>
						</div>

						<div class="tab-pane<?php echo $activeTab == 2 ? ' active' : ''?>" role="tabpanel" id="login2">
							<form id="apply-form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="clearfix">
								<div class="row">
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label for="fname"><?php _e( 'First Name', 'storefront' ); ?> *</label>

											<div class="form-control-wrapper">
												<input data-validation="required" id="fname" type="text" class="form-control input-primary" name="first_name">
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label for="lname"><?php _e( 'Surname', 'storefront' ); ?> *</label>

											<div class="form-control-wrapper">
												<input data-validation="required" id="lname" type="text" class="form-control input-primary" name="last_name">
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="email2"><?php _e( 'Email Address', 'storefront' ); ?> *</label>

									<div class="form-control-wrapper">
										<input data-validation="email" id="email2" type="email" class="form-control input-primary" name="email">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label for="password2"><?php _e( 'Password', 'storefront' ); ?> *</label>

											<div class="form-control-wrapper">
												<input data-validation="length" data-validation-length="min6" id="password2" type="password" class="form-control input-primary" name="password">
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label for="country"><?php _e( 'Country', 'storefront' ); ?> *</label>

											<div class="form-control-wrapper">
												<select data-validation="required" name="country" class="form-control input-primary">
													<option value=""></option>
													<?php foreach ( (new WC_Countries())->countries as $code => $country ) : ?>
														<option value="<?php echo $code; ?>"><?php echo $country; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="why"><?php _e( 'Describe Why You Want To Be A Distributor', 'storefront' ); ?> *</label>

									<div class="form-control-wrapper">
										<textarea data-validation="length" data-validation-length="1-250" id="why" rows="4" class="form-control input-primary" name="why"></textarea>
									</div>
								</div>

								<input type="hidden" name="action" value="apply_to_be_a_distributor">

								<div class="form-group hidden" id="apply-response-error">
									<div class="form-control-wrapper">
										<div class="alert alert-danger" role="alert">

										</div>
									</div>
								</div>

								<button class="btn btn-primary pull-left"><?php _e( 'Apply', 'storefront' ); ?></button>
							</form>

							<div class="alert alert-success hidden" id="apply-response" role="alert">
                                <?php _e('Thank you for applying to become a sales partner. We have received your information and a member our sales team will reach out to you shortly.', 'storefront'); ?>
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

<script>
(function($) {
	$(document).ready(function() {
		$('#apply-form').submit(function() {
			var $this = $(this);
			if ( ! $this.isValid() ) return false;

			var	$msgSuccess = $('#apply-response'),
				$msgError = $('#apply-response-error'),
				url  = $this.attr('action'),
				data = $this.serialize(),
				spinner = $('<img src="<?php echo get_template_directory_uri() ?>/images/ajax-loader.gif">');

			$this.find('button').attr('disabled', 'disabled').append(spinner);

			$.post(url, data, function( response ) {
				response = JSON.parse($.trim(response));

				if ( response.success ) {
					$this.remove();
					$msgSuccess.removeClass('hidden');
				} else {
					$this.find('button').removeAttr('disabled').find(spinner).remove();

					if ( response.error ) {
						$msgError
							.removeClass('hidden')
							.find('.alert').html(response.error);
					} else if ( response.errors ) {
            $.each(response.errors, function(key, value) {
              if (typeof ($('[name="'+key+'"]')) !== 'undefined') {
                $('[name="'+key+'"]').removeClass('valid').addClass('error');
                $('[name="'+key+'"]').parent().removeClass('has-success').addClass('has-error');
                $('[name="'+key+'"]').after('<span class="help-block form-error">'+value+'</span>');
              } else {
                for ( var error in response.errors ) {
                  $msgError
                    .removeClass('hidden')
                    .find('.alert').html(response.errors[error]);
                  break;
                }
              }
            });
					}
				}
			});

			return false;
		});
	});
})(jQuery);
</script>
<?php get_footer(); ?>