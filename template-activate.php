<?php
/**
 * The template for the activation page
 *
 * Template Name: Activation
 *
 * @package storefront
 */

if ( !is_multisite() OR (empty($_GET['key']) && empty($_POST['key'])) ) {
	wp_redirect( site_url( '/wp-login.php?action=register' ) );
	die();
}

if ( is_object( $wp_object_cache ) )
	$wp_object_cache->cache_enabled = false;

// Fix for page title
$wp_query->is_404 = false;

/**
 * Fires before the Site Activation page is loaded.
 *
 * @since 3.0
 */
do_action( 'activate_header' );

/**
 * Adds an action hook specific to this page that fires on wp_head
 *
 * @since MU
 */
function do_activate_header() {
	/**
	 * Fires before the Site Activation page is loaded, but on the wp_head action.
	 *
	 * @since 3.0
	 */
	do_action( 'activate_wp_head' );
}
add_action( 'wp_head', 'do_activate_header' );

get_header();

?>
<main class="site-main container">
	<div class="row">
		<div class="col-xs-12">
		<?php
			$key = !empty($_GET['key']) ? $_GET['key'] : $_POST['key'];
			$result = wpmu_activate_signup($key);
			$siteurl = site_url();
			$lostpassword = $siteurl . '/reset-password';
				if ( is_wp_error($result) ) {
					if ( 'already_active' == $result->get_error_code() || 'blog_taken' == $result->get_error_code() ) {
						$signup = $result->get_error_data();
						?>
						<h1><?php _e('Your account is already active!', 'storefront'); ?></h1>
						<hr>
						<?php
						echo '<p class="lead-in">';
						if ( $signup->domain . $signup->path == '' ) {
							printf( __('You can now use your username <strong>&#8220;%1$s&#8221;</strong> or email <strong>&#8220;%2$s&#8221;</strong> to log in to the user portal <a href="%3$s">here</a>.</p><p>Please check your email inbox at <strong>%2$s</strong> for your password and login information.</p></p>If you do not receive an email, please check your junk or spam folder. If you still do not receive an email and forget your password, you can reset your password <a href="%4$s">here</a> or via the login form by clicking <strong>Forgot / don\'t know your password?</strong> button.</p>', 'storefront'), $signup->user_login, $signup->user_email, $siteurl . '/portal', $lostpassword );
						} else {
							printf( __('Your site at <a href="%1$s">%2$s</a> is active. You may now log in to your site using your chosen username of <strong>&#8220;%3$s&#8221;</strong>.</p><p>Please check your email inbox at %4$s for your password and login instructions. If you do not receive an email, please check your junk or spam folder. If you still do not receive an email within an hour, you can <a href="%5$s">reset your password</a>.', 'storefront'), 'http://' . $signup->domain, $signup->domain, $signup->user_login, $signup->user_email, $lostpassword );
						}
						echo '</p>';
					} else {
						?>
						<h2><?php _e('An error occurred during the activation', 'storefront'); ?></h2>
						<?php
						echo '<p>'.$result->get_error_message().'</p>';
					}
				} else {
					extract($result);
					$url = get_blogaddress_by_id( (int) $blog_id);
					$user = get_userdata( (int) $user_id);
				 	$username = $user->user_login;
				 	$useremail = $user->user_email;
				?>
					<h1><?php _e('Your account is now active!', 'storefront'); ?></h1>
					<hr>

					<div id="signup-welcome">
					 	<p class="view"><?php printf( __('You can now use your username <strong>&#8220;%1$s&#8221;</strong> or email <strong>&#8220;%2$s&#8221;</strong> to log in to the user portal <a href="%3$s">here</a>.</p><p>Please check your email inbox at <strong>%2$s</strong> for your password and login information.</p></p>If you do not receive an email, please check your junk or spam folder. If you still do not receive an email and forget your password, you can reset your password <a href="%4$s">here</a> or via the login form by clicking <strong>Forgot / don\'t know your password?</strong> button.</p>', 'storefront'), $username, $useremail, $siteurl . '/portal', $lostpassword ); ?></p>
				 </div>
					<?php
				}
			?>

			<script>
				var key_input = document.getElementById('key');
				key_input && key_input.focus();
			</script>


		</div>
	</div>
</main>

<?php get_footer(); ?>