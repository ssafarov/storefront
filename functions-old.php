<?php
require get_template_directory() . '/inc/init.php';

function distributor_info()
{
	global $current_user;

	$since = substr($current_user->user_registered, 0, 10);
	$since = explode('-', $since);
	$since = array_reverse($since);
	$since = implode('/', $since);

	$countries = (new WC_Countries())->countries;

	$country = get_user_meta($current_user->ID, 'billing_country', true);
	$country = isset($countries[$country])
		? $countries[$country]
		: $country;

	$company   = get_user_meta($current_user->ID, 'billing_company', true);
	$discount  = 0;
	$discounts = get_option('wcrd', []);

	foreach ($current_user->roles as $role)
	{
		if (isset($discounts[$role]['discount']))
		{
			$discount = $discounts[$role]['discount'];
		}
	}

	return [
		'since'    => $since,
		'country'  => $country,
		'discount' => $discount,
		'currency' => 'US Dollar',
		'company'  => $company,
	];
}

function get_scanner_product()
{
	return (new WC_Product(SCANNER_PID));
}

function get_target_product()
{
	return (new WC_Product(TARGET_PID));
}

function create_new_blog_pages()
{
	$page_names = [
		'Distributor portal' => '/distributor-portal',
	];

	// These settings will be shared across all pages.
	$page_settings = array(
		'post_status' => 'publish',
		'post_type' => 'page',
	);

	// This needs to be initialized before the loop.
	$post_id = -1;
	$home_id = -1;

	$menu_id = wp_create_nav_menu('primary-nav');

	// Creates the pages.
	foreach ($page_names as $page_name => $page_uri) {
		// Generate the custom settings for each page (from their names).
		$page_settings['post_name'] = $page_name;
		$page_settings['post_title'] = $page_name;

		// This needs the template file name relative to the theme root (the
		// documentation wasn't clear on this).
		$page_settings['page_template'] = 'page-templates/' . $page_name . '.php';

		$post_id = wp_insert_post($page_settings, true);
		if ($page_name === 'home') {
			$home_id = $post_id;
		}

		// Create the menu items and make them point to the right places.
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __(ucfirst($page_name)),
			'menu-item-url' => home_url($page_uri),
			'menu-item-status' => 'publish'
		));
	}

	// Set the front page to 'home'.
	update_option('show_on_front', 'page');
	update_option('page_on_front', $home_id);
}

// Custom excerpts and content. Usage: echo excerpt(length)
function excerpt($limit)
{
	$excerpt = explode(' ', do_shortcode(get_the_content()), $limit);

	if (count($excerpt)>=$limit)
	{
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	}
	else
	{
		$excerpt = implode(" ",$excerpt);
	}
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
		return $excerpt;
}

function f3d_check_login($user, $password) {
	return ( ! $user || in_array('inactive', $user->roles))
		? null
		: $user;
}
add_filter('wp_authenticate_user', 'f3d_check_login', 10, 2);