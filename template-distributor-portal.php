<?php
/*
Template Name: Distributor portal controller
*/

if (is_user_logged_in())
{
	if (in_array('distributor', $current_user->roles))
	{
		// Notice the distributor about setting up the default currency
		if ( ! esc_attr(get_user_meta($current_user->ID, 'default_currency', true)))
		{
			wc_add_notice( __("You have not setup your default currency. For an improved and faster user experience, you can set it up <a href='/edit-profile/'>here</a>"), $notice_type = 'notice' );
		}

		if (count($_POST))
		{
			/**
			 * Submit order
			 */

			$scannerQuantity = isset($_POST['scanner_quantity']) ? intval($_POST['scanner_quantity']) : 1;
			$targetQuantity  = isset($_POST['target_quantity'])  ? intval($_POST['target_quantity'])  : 0;

			///**
			// * Round to the nearest multiple of 5, with a minimum of 10 units ordered
			// */
			//$scannerQuantity = max(5, $scannerQuantity);
			//$scannerQuantity = $scannerQuantity % 5 ? ceil($scannerQuantity / 5) * 5 : $scannerQuantity;

			WC()->cart->empty_cart(); // clear cart
			WC()->cart->add_to_cart(SCANNER_PID, $scannerQuantity); // populate with new order
			WC()->cart->add_to_cart(TARGET_PID,  $targetQuantity);

			if (isset($_REQUEST['scanner_quantity'])) {
				header('Location: ' . WC()->cart->get_checkout_url()); // go to the checkout page
				exit;
			}

		}

		$info    = distributor_info();
		$scanner = get_scanner_product();
		$target  = get_target_product();

		include('template-distributor-logged-in.php');
	}
	else
	{
		include('template-distributor-missing-role.php');
	}
}
else
{
	$activeTab = isset($_GET['apply']) ? 2 : 1;

	include('template-distributor-guest.php');
}
