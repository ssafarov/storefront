<?php
/*
Template Name: Reset Page
*/

get_header()
?>

<?php
	global $wpdb;

	$error = '';
	$success = '';
	$mail = false;
	$email = false;
	$message = false;
	$subject = false;
	$needEmailSend = false;

if (isset( $_POST['action'])){

	// check if we're in reset form
	if ( isset( $_POST['action'] ) && 'reset' == $_POST['action'] ) {
		$email = trim($_POST['user_login']);

		if ( empty( $email ) ) {
			$error .= __('Enter your e-mail address...'."\n", 'storefront');
		} else if ( ! is_email( $email ) ) {
			$error .= __('Invalid e-mail address.', 'storefront');
		} else if ( ! email_exists( $email ) ) {
			$error .= __('There is no user registered with email address you have entered. Can\'t proceed to reset password.'."\n", 'storefront');
		} else {
			$random_password = wp_generate_password( 12, false );
			$user = get_user_by( 'email', $email );
			if ($user === false){
				$error .= __('We were unable to find the user with e-mail address given. Unable to sent email'."\n", 'storefront');
			} else {
				$needEmailSend = wp_update_user( array (
					'ID' => $user->ID,
					'user_pass' => $random_password
				));
				$subject = __('Fuel3D - Your new credentials', 'storefront');
				$message = __('The credentials stored in our database is the following:', 'storefront') . '<strong>'.$user->user_login . '</strong><br><br>';
				$message .= __('Your username is:', 'storefront') . ' <strong>'.$user->user_login . '</strong><br><br>';
				$message .= __('Your email is:', 'storefront') . ' <strong>'.$user->user_email . '</strong><br><br>';
				$message .= __('Your new password is:', 'storefront') . ' <strong>'.$random_password . '</strong><br><br><br>';
				$message .= __('Please ', 'storefront') . '<a style="color: #3FB89A;" href="' . site_url() . '/portal/">'.__('click here', 'storefront').'</a> ';
				$message .= __('to login to your account', 'storefront').'.';
			}
		}
	}

	// check if we're in reset form and want retreive username instead of generate new password
	if ( isset( $_POST['action'] ) && 'retreive' == $_POST['action'] ) {
		$email = trim($_POST['user_login']);
		if ( empty( $email ) ) {
			$error .= __('Enter your e-mail address...'."\n", 'storefront');
		} else if ( ! is_email( $email ) ) {
			$error .= __('Invalid e-mail address entered.'."\n", 'storefront');
		} else if ( ! email_exists( $email ) ) {
			$error .= __('There is no user registered with email address you have entered. Can\'t proceed to find username.'."\n", 'storefront');
		} else {
			// Found the user via email and then sent to him email with his username
			$user = get_user_by( 'email', $email );
			if ($user === false){
				$error .= __('We were unable to find the user with e-mail address given.'."\n", 'storefront');
			} else {
				$needEmailSend = $user->user_email == $email;
				$subject = __('Fuel3D - Your credentials', 'storefront');
				$message = __('The credentials stored in our database is the following:', 'storefront') . '<strong>'.$user->user_login . '</strong><br><br>';
				$message .= __('Your username is:', 'storefront') . ' <strong>'.$user->user_login . '</strong><br><br>';
				$message .= __('Your email is:', 'storefront') . ' <strong>'.$user->user_email . '</strong><br><br>';
				$message .= __('Please ', 'storefront') . '<a style="color: #3FB89A;" href="' . site_url() . '/portal/">'.__('click here', 'storefront').'</a> ';
				$message .= __('to login to your account', 'storefront').'.';
			}
		}
	}


	// if  update user return true then lets send user an email containing the new password
	if ( $needEmailSend  && ($email != false) && ($message != false) && ($subject != false)) {
		$to = $email;
		$sender = 'noreply@fuel-3d.com';
		$headers[] = 'MIME-Version: 1.0' . "\r\n";
		$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers[] = "X-Mailer: PHP \r\n";
		$headers[] = 'From: Fuel3D < '.$sender.'>' . "\r\n";

		$mail = wp_mail( $to, $subject, $message, $headers );

		if ( $mail ) {
			$success .= __('Please check your email for your credentials requested.'."\n", 'storefront');
		} else {
			$error .= __('Oops! Something went wrong during email sent. Please try again later.'."\n", 'storefront');
		}

	}
}
?>

<main class="site-main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2" id="wpmem_login">
				<div class="hentry">
					<h1><?php _e('Request your credentials', 'storefront'); ?></h1>
					<hr><br>

					<?php if ( ! empty($error) || ! empty($success)) { ?>
						<div class="alert <?php if (empty($error)) echo 'alert-success'; else echo 'alert-danger'; ?> alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span></button><?php echo ($error); echo ($success); ?></div>
					<?php } ?>

					<form method="post" class="hidsub" id="user_details_manage">

						<div class="form-group">
							<?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
							<label for="user_login"><?php _e('Please enter your email address to receive your credentials', 'storefront'); ?>&nbsp;*</label>
							<div class="form-control-wrapper">
								<input data-validation="email" class="form-control input-primary" type="text" name="user_login" id="user_login"
								       value="<?php echo $user_login; ?>">
							</div>
						</div>

						<div class="form-group">
							<input type="hidden" name="action" value="reset">
							<input type="button" value="<?php _e('Get New Password', 'storefront'); ?>" class="btn btn-primary" data-action="reset">
							<input type="button" value="<?php _e('Retrieve Username', 'storefront'); ?>" class="btn btn-primary" data-action="retreive">
						</div>

					</form>

					<script>
						jQuery(document).ready(function() {
							jQuery(document).on ("click", "input[type='button']", function (e){
								var self = jQuery(this),
									form   = self.closest("form"),
									action = form.find("input[name='action']"),
									selectedAction = self.data('action');
								if ((selectedAction.length > 0)&&(action.length > 0)&&(form.length > 0)) {
									action.val(selectedAction);
									form.submit();
								}
								e.preventDefault();
								e.stopPropagation();
								return false;
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>