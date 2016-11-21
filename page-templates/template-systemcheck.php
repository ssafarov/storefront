<?php
/*
Template Name: System Registration Check Email
*/
if (count($_POST))
{
	$fields = ['user_name', 'user_email', 'first_name', 'last_name', 'invitation_code'];
	$errors = [];

	/**
	 * Validate required fields
	 */
	foreach ($fields as $field)
	{
		if (empty($_POST[$field]))
		{
			$errors[$field] = __('This field is required', 'storefront');
		}
	}

	/**
	 * Validate email address
	 */
	if ( ! filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL))
	{
		$errors['user_email'] = __('Invalid email address', 'storefront');
	}

	if (email_exists($_POST['user_email']))
	{
		$errors['user_email'] = __('Email address already registered', 'storefront');
	}

	/**
	 * Validate username
	 */
	if (strlen($_POST['user_name']) < 4)
	{
		$errors['user_name'] = __('Username should be at least 4 characters long', 'storefront');
	}

	if (username_exists($_POST['user_name']))
	{
		$errors['user_name'] = __('Username already exists', 'storefront');
	}

	/**
	 * Validate serial number
	 */
	if ( ! count($errors))
	{
		$invitation_code = isset( $_POST['invitation_code'] ) ? strtoupper( filter_var($_POST['invitation_code'], FILTER_SANITIZE_STRING) ) : '';

		if ( array_key_exists($invitation_code, $baweicmu_options['codes']) &&
		     $baweicmu_options['codes'][$invitation_code]['leftcount'] > 0)
		{
			wpmu_signup_user(
				$_POST['user_name'],
				$_POST['user_email'],
				apply_filters('add_signup_meta', [
					'first_name' => $_POST['first_name'],
					'last_name'  => $_POST['last_name'],
					'role'       => 'customer',
				])
			);

			$baweicmu_options['codes'][$invitation_code]['leftcount']--;
			$baweicmu_options['codes'][$invitation_code]['users'][] = sanitize_text_field( $_POST['user_name'] );
			update_site_option('baweicmu_options', $baweicmu_options);

			$greatSuccess = true;
		}
		else
		{
			$errors['invitation_code'] = __('Invalid serial number', 'storefront');
		}
	}
}

get_header();
?>

<main class="site-main container">
	<div class="row">
		<div class="col-xs-12">
			<h1><?php _e('Register your device', 'storefront') ?></h1>
			<hr>
		</div>

		<div class="col-sm-12 col-md-6">
			<?php if ( ! empty($greatSuccess)) { ?>
			<h2><?php printf( __( '%s is your new username' ), $_POST['user_name']) ?></h2>
			<p><?php _e( 'But, before you can start using your new username, <strong>you must activate it</strong>.', 'storefront' ) ?></p>
			<p><?php printf( __( 'Check your inbox at <strong>%s</strong> and click the link given.' ), $_POST['user_email'] ); ?></p>
			<p><?php _e( 'If you do not activate your username within two days, you will have to sign up again.', 'storefront' ); ?></p>
			<?php } else { ?>
			<form method="post">
				<h4><?php _e( 'First time users or registering your device for the first time', 'storefront' ); ?></h4>
				<br>
				<br>
				<div class="form-group">
					<label for="user_email"><?php _e( 'Username *', 'storefront' ); ?></label>

					<?php if (isset($errors['user_name'])) { ?>
						<div class="form-control-wrapper has-error">
							<input data-validation="required" class="form-control input-primary error" name="user_name" type="text"
							       value="<?php echo isset($_POST['user_name']) ? $_POST['user_name'] : '' ?>">
							<span class="help-block form-error"><?php echo $errors['user_name'] ?></span>
						</div>
					<?php } else { ?>
						<div class="form-control-wrapper">
							<input data-validation="required" class="form-control input-primary" name="user_name" type="text"
							       value="<?php echo isset($_POST['user_name']) ? $_POST['user_name'] : '' ?>">
						</div>
					<?php } ?>
				</div>

				<div class="form-group">
					<label for="user_email"><?php _e( 'Email address *', 'storefront' ); ?></label>

					<?php if (isset($errors['user_email'])) { ?>
						<div class="form-control-wrapper has-error">
							<input data-validation="email" class="form-control input-primary error" name="user_email" type="email"
							       value="<?php echo isset($_POST['user_email']) ? $_POST['user_email'] : '' ?>">
							<span class="help-block form-error"><?php echo $errors['user_email'] ?></span>
						</div>
					<?php } else { ?>
						<div class="form-control-wrapper">
							<input data-validation="email" class="form-control input-primary" name="user_email" type="email"
							       value="<?php echo isset($_POST['user_email']) ? $_POST['user_email'] : '' ?>">
						</div>
					<?php } ?>
				</div>

				<div class="form-group">
					<label for="user_email"><?php _e( 'First name *', 'storefront' ); ?></label>

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

				<div class="form-group">
					<label for="user_email"><?php _e( 'Last name *', 'storefront' ); ?></label>

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

				<div class="form-group">
					<label for="user_email"><?php _e( 'Enter your serial number *', 'storefront' ); ?></label>

					<?php if (isset($errors['invitation_code'])) { ?>
						<div class="form-control-wrapper has-error">
							<input data-validation="required" class="form-control input-primary error" name="invitation_code" type="text"
							       value="<?php echo isset($_POST['invitation_code']) ? $_POST['invitation_code'] : '' ?>">
							<span class="help-block form-error"><?php echo $errors['invitation_code'] ?></span>
						</div>
					<?php } else { ?>
						<div class="form-control-wrapper">
							<input data-validation="required" class="form-control input-primary" name="invitation_code" type="text"
							       value="<?php echo isset($_POST['invitation_code']) ? $_POST['invitation_code'] : '' ?>">
						</div>
					<?php } ?>

					<p><small><?php _e( 'Problems registering your device?', 'storefront' ); ?>&nbsp;<a target="_blank" href="https://fuel3d.zendesk.com"><?php _e( 'Click here for support', 'storefront'); ?></a></small></p>
				</div>

				<div class="form-group">
					<div class="form-control-wrapper">
						<input name="Submit" type="submit" value="<?php _e( 'Submit', 'storefront'); ?>" class="btn btn-primary btn-block">
					</div>
				</div>
			</form>
			<?php } ?>

		</div>
		<div class="col-sm-12 col-md-4 col-md-offset-2">
			<h4><?php _e('Already logged-in before?', 'storefront') ?></h4><br>

			<a href="<?php echo home_url('process'); ?>" class="btn btn-primary btn-block"><?php _e('Please click here to login', 'storefront'); ?></a>
		</div>
	</div>

</main>

<?php get_footer(); ?>