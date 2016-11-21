<?php
/*
Template Name: Customer portal controller
*/

if (is_user_logged_in())
{
	/**
	 * Redirect distributors to distributor portal
	 */
	if (in_array('distributor', $current_user->roles))
	{
		header('Location: /distributor-portal/');
		exit;
	}
	else
	{
		if ( ! empty($wp->query_vars['view-order']))
		{
			/**
			 * View order details
			 */
			$order_id = intval($wp->query_vars['view-order']);

			if (current_user_can( 'view_order', $order_id ) ) {
				$order = new \WC_Order( $order_id );

				wc_get_template( 'myaccount/view-order.php', array(
					'status'    => get_term_by( 'slug', $order->status, 'shop_order_status' ),
					'order'     => $order,
					'order_id'  => $order_id
				) );

				exit;
			}
		}

		/**
		 * Redirect to ROW portal
		 */
		if (isUsaShop())
		{
			header('Location: /portal/');
			exit;
		}

		/**
		 * Show the customer portal
		 */

		include('customer-portal-logged-in.php');
	}
}
else
{
	/**
	 * Redirect to ROW portal
	 */
	if (isUsaShop())
	{
		header('Location: /portal/');
		exit;
	}

	if (isset($_GET['register']) && count($_POST))
	{
		$required = ['user_name', 'user_password', 'user_password_confirm', 'user_email', 'first_name', 'last_name'];
		$errors   = [];

		/**
		 * Validate required fields
		 */
		foreach ($required as $field)
		{
			if (empty($_POST[$field]))
			{
				$errors[$field] = _('This field is required');
			}
		}

		/**
		 * Validate password
		 */
		if ($_POST['user_password'] != $_POST['user_password_confirm'])
		{
			$errors['user_password_confirm'] = _('Passwords entered do not match, please enter the same passwords for both fields');
		}
		$pattern = '/^(?=.{10,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).*/';
		$opts = array('options' => array('regexp' => $pattern));
		if ( ! filter_var($_POST['user_password'], FILTER_VALIDATE_REGEXP, $opts))
		{
			$errors['user_password'] = _('The password you entered doesn\'t meet our security requirements. It must be at least 10 characters long, including both lower and upper case letters and a number');
		}

		/**
		 * Validate email address
		 */
		if ( ! filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL))
		{
			$errors['user_email'] = _('Invalid email address');
		}

		if (email_exists($_POST['user_email']))
		{
			$errors['user_email'] = _('Email address already registered');
		}

		/**
		 * Validate username
		 */
		if (strlen($_POST['user_name']) < 4)
		{
			$errors['user_name'] = _('Username should be at least 4 characters long');
		}

		if (username_exists($_POST['user_name']))
		{
			$errors['user_name'] = _('Username already exists');
		}

		/**
		 * Validate serial number
		 */
		$invitation_code = isset( $_POST['invitation_code'] ) ? strtoupper(filter_var($_POST['invitation_code'], FILTER_SANITIZE_STRING)) : '';
		$serialNumber = substr($invitation_code, 0, -1);
		$checkDigit = substr($invitation_code, -1);

		if ($invitation_code)
		{

			if ( ! array_key_exists($serialNumber, $baweicmu_options['codes']) && (int)$checkDigit != calculate_check_digit($serialNumber))
			{
				$errors['invitation_code'] = 'Invalid serial number';
			}
		}

		if ( ! count($errors))
		{
			wpmu_signup_user(
				$_POST['user_name'],
				$_POST['user_email'],
				apply_filters('add_signup_meta', [
					'first_name' => $_POST['first_name'],
					'last_name'  => $_POST['last_name'],
					'password'   => $_POST['user_password'],
					'role'       => 'customer',
					'where_buy'  => empty($_POST['where_buy']) ? '' : $_POST['where_buy'],
				])
			);

			if ($invitation_code &&
				array_key_exists($serialNumber, $baweicmu_options['codes']))
			{
				$baweicmu_options['codes'][$serialNumber]['users'][] = sanitize_text_field( $_POST['user_name'] );
				update_site_option('baweicmu_options', $baweicmu_options);
			}

			include('customer-register-thank-you.php');
			exit;
		}
	}

	$activeTab = isset($_GET['register']) ? 2 : 1;

	include('customer-portal-guest.php');
}
