<?php
/**
 * Login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() )
	return;
?>

	<form id="login" method="post" class="login" <?php if ( $hidden ) echo 'style="display:none;"'; ?>>

		<div class="login-box">
			<div class="col-sm-12 col-xs-12">
				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<?php if ( $message ) : ?>
					<h2 class="tiny"><?=  wptexturize( $message ); ?></h2>
				<?php endif; ?>
			</div>
			<div class="col-sm-6 col-xs-12 column-login-form border-right">
				<div class="form-group">
					<label for="username"><span class="required">*</span><?php _e( 'Username or Email Address', 'storefront' ); ?></label>
					<div class="form-control-wrapper">
						<input data-validation="required" type="text" class="input-text" name="username" id="username">
					</div>
				</div>
				<div class="form-group">
					<label for="password"><span class="required">*</span><?php _e( 'Password', 'storefront' ); ?></label>
					<div class="form-control-wrapper">
						<input data-validation="required" class="input-text" type="password" name="password" id="password">
					</div>
				</div>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="form-group row">
					<div class="col-xs-6">
					<?php wp_nonce_field( 'woocommerce-login' ); ?>
					<input type="submit" class="btn btn-primary" name="login" value="<?php _e( 'Login', 'storefront' ); ?>">
					<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>">
					</div>
					<div class="col-xs-6">
						<label class="checkbox" for="rememberme">
							<input name="rememberme" type="checkbox" id="rememberme" value="forever"> <?php _e( 'Remember me', 'storefront' ); ?>
						</label>
					</div>
				</div>


				<div class="form-group lost_password">
						<a class="underline" href="<?php echo home_url( '/reset-password/' ); ?>"><?php _e( 'Forgot your password?', 'storefront' ); ?></a>
				</div>
			</div>
			<div class="col-sm-6 col-xs-12 column-no-login" id="dont-have-acc">
				<?php do_action( 'woocommerce_login_form_end' ); ?>
			</div>
			<div class="clearfix visible-sm-block"></div>
		</div>
		<div class="clearfix"></div>
	</form>
