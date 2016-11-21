<?php
/*
Template Name: Edit Profile Page
*/

/**
 * Let's process received data first
 * Load the registration file.
 */

//require_once(ABSPATH . WPINC . '/registration.php');
$error = [];

/* If profile was saved, update profile. */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'update-user')
{
	if(isset($_POST['vat_number']) && get_current_user_id()){
		$wc_eu_vat_number = new WC_EU_VAT_Number;
		$wc_eu_vat_number->process_checkout();
		$notices = WC()->session->get('wc_notices', array());
		if (!isset($notices['error'])) {
			update_user_meta(get_current_user_id(), 'vat_number', $_POST['vat_number']);
		}
	}
	$checkout_helper = new WC_Checkout();
	$checkout_fields = $checkout_helper->checkout_fields;

	// Clear unwanted fieldsets
	unset($checkout_fields['account']);
	unset($checkout_fields['order']);

	//  Some further sanitisation
	$show_fields = $checkout_fields;

	//  Validate Entries
	foreach ($show_fields as $fieldset_key => $fieldset)
	{
		foreach ($fieldset as $key => $field)
		{
			// Validation rules
			if ( ! empty($field['validate']) && is_array($field['validate']))
			{
				foreach ($field['validate'] as $rule)
				{
					switch ($rule)
					{
						case 'postcode':
							$_POST[$key] = strtoupper(str_replace(' ', '', $_POST[$key]));

							if ( ! WC_Validation::is_postcode($_POST[$key], $_POST[$fieldset_key . '_country']))
							{
								wc_add_notice(__('Please enter a valid postcode/ZIP.', 'woocommerce'), 'error');
							}
							else
							{
								$_POST[$key] = wc_format_postcode($_POST[$key], $_POST[$fieldset_key . '_country']);
							}
							break;

						case 'phone':
							$_POST[$key] = wc_format_phone_number($_POST[$key]);
							if ( ! WC_Validation::is_phone($_POST[$key]))
							{
								wc_add_notice('<strong>' . $field['label'] . '</strong> ' . __('is not a valid phone number.', 'woocommerce'), 'error');
							}
							break;
						case 'email':
							$_POST[$key] = strtolower($_POST[$key]);
							if ( ! is_email($_POST[$key]))
							{
								wc_add_notice('<strong>' . $field['label'] . '</strong> ' . __('is not a valid email address.', 'woocommerce'), 'error');
							}
							break;
						case 'state':
							// Get valid states
							$valid_states = WC()->countries->get_states($_POST[$fieldset_key . '_country']);

							if ($valid_states)
							{
								$valid_state_values = array_flip(array_map('strtolower', $valid_states));
							}

							// Convert value to key if set
							if (isset($valid_state_values[strtolower($_POST[$key])]))
							{
								$_POST[$key] = $valid_state_values[strtolower($_POST[$key])];
							}

							// Only validate if the country has specific state options
							if ($valid_states && sizeof($valid_states) > 0)
							{
								if (!in_array($_POST[$key], array_keys($valid_states)))
								{
									wc_add_notice('<strong>' . $field['label'] . '</strong> ' . __('is not valid. Please enter one of the following:', 'woocommerce') . ' ' . implode(', ', $valid_states), 'error');
								}
							}
							break;
					}
				}
			}
		}
	}

	/* Update user password. */
	if ( ! empty($_POST['pass1']) && !empty($_POST['pass2']))
	{
		if ($_POST['pass1'] == $_POST['pass2'])
		{
			wp_update_user(array('ID' => $current_user->ID, 'user_pass' => esc_attr($_POST['pass1'])));
		}
		else
		{
			$error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
		}
	}

	/* Update user information. */
	if ( ! empty($_POST['url']))
	{
		update_user_meta($current_user->ID, 'user_url', esc_url($_POST['url']));
	}

	if (!empty($_POST['email']))
	{
		if ( ! is_email(esc_attr($_POST['email'])))
		{
			$error[] = __('The Email you entered is not valid.  please try again.', 'profile');
		}
		else
		{
			$exists_id = email_exists(esc_attr($_POST['email']));

			if ($exists_id === false)
			{
				wp_update_user(array('ID' => $current_user->ID, 'user_email' => esc_attr($_POST['email'])));
			}
			elseif ($exists_id != $current_user->ID)
			{
				$error[] = __('This email is already used by another user.  try a different one.', 'profile');
			}
		}
	}

	if ( ! empty($_POST['first-name']))
	{
		update_user_meta($current_user->ID, 'first_name', esc_attr($_POST['first-name']));
	}

	if ( ! empty($_POST['last-name']))
	{
		update_user_meta($current_user->ID, 'last_name', esc_attr($_POST['last-name']));
	}

	if ( ! empty($_POST['description']))
	{
		update_user_meta($current_user->ID, 'description', esc_attr($_POST['description']));
	}

	/** All basic data was updated... let's update woocomm data */
	$customer = new WC_Admin_Profile();
	$customer->save_customer_meta_fields($current_user->ID);

	/* Redirect so the page will show updated info.*/
	if (count($error) == 0) {
		//action hook for plugins and extra fields saving
		do_action('edit_user_profile_update', $current_user->ID);
		wp_redirect(get_permalink());
		exit;
	}
}

function remove_icky_woocommerce_tags($markup)
{
	if (stripos($markup, '<noscript>') !== false)
	{
		$start  = stripos($markup, '<noscript>');
		$end    = stripos($markup, '</noscript>');
		$markup = substr($markup, 0, $start) . substr($markup, $end + 11);
	}

	if (stripos($markup, '<label') !== false)
	{
		$start  = stripos($markup, '<label');
		$end    = stripos($markup, '</label>');
		$markup = substr($markup, 0, $start) . substr($markup, $end + 8);
	}

	return strip_tags($markup, '<select><option><input>');
}

function render_woocomm_fields()
{
	// Load required woocomm fields for checkout
	$checkout_instance = new WC_Checkout();
	$checkout_fields = $checkout_instance->checkout_fields;

	//  Clear unwanted fieldsets
	unset($checkout_fields['account']);
	unset($checkout_fields['order']);

	//  Some further sanitisation
	$show_fields = $checkout_fields;
	$show_fields['billing']['title'] = __("Billing details");
	$show_fields['shipping']['title'] = __("Shipping details");
	//TODO
	$show_fields['billing']['billing_email'] = [
			'label' => "Email Address",
			'required' => true,
			'type' => "email",
			'class' => ["form-row-first"],
			'validate' => ["email"]
	];
	$show_fields['billing']['billing_phone'] = [
			'label' => "Phone",
			'required' => true,
			'type' => "text",
			'class' => ["form-row-first"],
			'validate' => ["number"]
	];
	$uid = get_current_user_id();

	//  Render them
	foreach ($show_fields as $fieldset)
	{
	?>
		<div class="col-sm-6">
			<h3><?php echo $fieldset['title']; ?></h3>
			<?php
				foreach ($fieldset as $key => $field_data)
				{
					if ( ! empty($field_data['type']) && $field_data['type'] == 'country')
					{
						$field_data['type'] = 'select';
						$field_data['options'] = WC()->countries->countries;
					}

					if ($key == 'billing_county') {
						$key = 'billing_state';
					}

					if ($key == 'shipping_county') {
						$key = 'shipping_state';
					}

					if ($key == 'title') continue;

					/**
					 * Set custom field data
					 */
					$field_data['return'] = true;
					$label = isset($field_data['label']) ? $field_data['label'] : null;
					$field_data['label'] = null;

					if( ! empty($field_data['required']))
					{
						$label .= ' *';

                        if (!isset($field_data['custom_attributes']) || !is_array($field_data['custom_attributes'])) {
                            $field_data['custom_attributes'] = [];
                        }

						$field_data['custom_attributes']['data-validation'] = 'required';
					}
					?>
					<div class="form-group">
						<label><?=$label?></label>

						<div class="form-control-wrapper">
							<?php echo remove_icky_woocommerce_tags(woocommerce_form_field($key, $field_data, esc_attr(get_user_meta($uid, $key, true)))); ?>
						</div>
					</div><?php // @todo Add error display after each field instead of top listing
				}
			?>
		</div>
	<?php
	}
}

$info = distributor_info();

get_header();
include_once "edit-profile-form.tpl.php";
get_footer();