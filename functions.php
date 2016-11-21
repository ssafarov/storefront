<?php
require get_template_directory() . '/inc/init.php';
include_once ABSPATH . 'wp-content/plugins/woocommerce-aelia-currencyswitcher/lib/classes/widgets/wc-aelia-currencyswitcher-widget.php';
include_once ABSPATH . 'wp-content/plugins/woocommerce-aelia-currencyswitcher/lib/classes/session/aelia-session-manager.php';

require get_template_directory() . '/woocommerce-aelia-currencyswitcher/WooCommerceCurrencySwitcher.php';
require_once(get_template_directory() . '/inc/class/TimelineEvents.php');

require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/class-wc-order.php';
require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/wc-conditional-functions.php';
//require_once ABSPATH . 'wp-content/plugins/woocommerce-subscriptions/classes/class-wc-subscriptions-product.php';


if ( ! defined( 'ENCRYPTION_KEY' ) ) {
    define( 'ENCRYPTION_KEY', "!$@%^#&*" );
}

if ( ! defined( 'SCRIPT_DEBUG' ) ) {
    define( 'SCRIPT_DEBUG', true );
}

if (! defined( 'USA_STORE' )) {
    define('USA_STORE', 'fu_4_capabilities');
}

if (! defined( 'ROW_STORE' )) {
    define('ROW_STORE', 'fu_capabilities');
}
/**
 * @param        $user
 * @param        $user_email
 * @param        $key
 * @param string $meta
 *
 * @return bool
 */
function f3d_wpmu_signup_user_notification($user, $user_email, $key, $meta = '')
{
    $sitename = get_bloginfo('name');
    $blog_id = get_current_blog_id();
    // Send email with activation link.
    $admin_email = get_option('admin_email');
    if ($admin_email == '') {
        $admin_email = 'info@' . $_SERVER['SERVER_NAME'];
    }
    $from_name = get_option('blogname') == '' ? $sitename : esc_html(get_option('blogname'));
    $message_headers = "From: \"{$from_name}\" <{$admin_email}>\n" . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
    $message = sprintf(
        apply_filters('wpmu_signup_user_notification_email',
            __("Hi %s,

Thank you for registering with %s.

To activate your account, please click the following link:

%s

You will then receive an email with your login details."),
            $user, $user_email, $key, $meta
        ),
        $user,
        $sitename,
        site_url("activate/?key=$key")
    );

    $message = str_replace("\n", '<br>', $message);

    // TODO: Don't hard code activation link.
    $subject = sprintf(
        apply_filters('wpmu_signup_user_notification_subject',
            __('%3$s - Activate your account', 'storefront'),
            $user, $user_email, $key, $meta
        ),
        $from_name,
        $user,
        $sitename
    );
    wp_mail($user_email, $subject, $message, $message_headers);

    return false;
}

add_filter('wpmu_signup_user_notification', 'f3d_wpmu_signup_user_notification', 10, 4);

/**
 * @param $welcome_email
 * @param $blog_id
 * @param $user_id
 * @param $password
 * @param $title
 * @param $meta
 *
 * @return mixed
 */
function f3d_welcome_email($welcome_email, $user_id, $password, $meta)
{
    // Override the email body:
    $welcome_email = __('Dear User,<br>
<br>
Your new account has been successfully created at: ' . site_url() . '/<br>
<br>
You may now log in with the following information:<br>
<br>
Username: USERNAME<br>
Password: PASSWORD<br>
Log in here: ' . site_url() . '/portal/<br>
<br>
We hope you enjoy your new Scanify. Thanks!<br>
<br>
--The Fuel3D Team', 'storefront');

    $current_site = get_current_site();
    $user = get_userdata($user_id);

    return str_replace(
        array('SITE_NAME', 'USERNAME', 'PASSWORD'),
        array($current_site->site_name, $user->user_login, $password),
        $welcome_email
    );
}

add_filter('update_welcome_user_email', 'f3d_welcome_email', 10, 4);

/**
 *  * Returns all the orders made by the user
 *  *
 *  * @param int $user_id
 *  * @param string $status (completed|processing|canceled|on-hold etc)
 *  * @return array
 *  */
function f3d_get_all_user_orders($user_id, $status = 'completed')
{
    $return = [];

    foreach ([1, 4] as $blogId) {
        switch_to_blog($blogId);

        $orders = get_posts([
            'numberposts' => - 1,
            'meta_key' => '_customer_user',
            'meta_value' => $user_id,
            'post_type' => 'shop_order',
            'post_status' => 'publish',
            // 'tax_query'   => [[
            // 'taxonomy' => 'shop_order_status',
            // 'field'    => 'slug',
            // 'terms'    => $status
            // ]],
        ]);

        foreach ($orders as $order) {
            $return[] = [
                'id' => $order->ID,
                'date' => implode('/', array_reverse(explode('-', substr($order->post_date, 0, 10)))),
                'shop' => $blogId,
            ];
        }
    }

    restore_current_blog();

    return $return;
}

//return apply_filters( 'woocommerce_get_view_order_url', $view_order_url, $this );
add_filter('woocommerce_get_view_order_url', 'portal_get_view_order_url', 10, 2);
/**
 * @param          $view_order_url
 * @param WC_Order $order
 *
 * @return string
 */
function portal_get_view_order_url($view_order_url, WC_Order $order)
{
    return get_site_url() . "/portal/view-order/{$order->id}/";
}

/**
 * @return string
 */
function get_distributor_portal_url()
{
    return get_site_url() . "/distributor-portal/";
}

/**
 * Returns basic distributor info for logged in user
 *
 * @return array
 */
function distributor_info()
{
    global $current_user;

    $since = substr($current_user->user_registered, 0, 10);
    $since = explode('-', $since);
    $since = array_reverse($since);
    $since = implode('/', $since);

    $countries = (new WC_Countries())->countries;

    $country = get_user_meta($current_user->ID, 'shipping_country', true);
    $country = isset($countries[$country])
        ? $countries[$country]
        : $country;

    $company = get_user_meta($current_user->ID, 'shipping_company', true);
    $discount = [];
    $discounts = get_option('wcrd', []);

    $defCurrency = esc_attr(get_user_meta($current_user->ID, 'default_currency', true));
    $currency = WC_Aelia_CurrencySwitcher::instance()->get_selected_currency();
    $currency = ($currency) ? $currency : $defCurrency;

    switch ($currency) {
        case 'EUR':
            $currency_sign = '&euro;';
            break;
        case 'USD':
            $currency_sign = '$';
            break;
        default:
            $currency_sign = '&pound;';
            break;
    }

    foreach ($current_user->roles as $role) {
        if (isset($discounts[$role]['discount'])) {
            array_push($discount, $discounts[$role]['discount']);
        }
    }

    $address = [];

    foreach (['shipping', 'billing'] as $type) {
        $address[$type] = [
            get_user_meta($current_user->ID, "{$type}_address_1", true),
            get_user_meta($current_user->ID, "{$type}_address_2", true),
            get_user_meta($current_user->ID, "{$type}_city", true),
            get_user_meta($current_user->ID, "{$type}_state", true),
            get_user_meta($current_user->ID, "{$type}_county", true),
            get_user_meta($current_user->ID, "{$type}_postcode", true) . ', ' . get_user_meta($current_user->ID,
                "{$type}_country", true),
        ];

        foreach ($address[$type] as $key => $value) {
            $address[$type][$key] = trim($value, ', ');

            if (!$address[$type][$key]) {
                unset($address[$type][$key]);
            }
        }

        $address[$type] = empty($address[$type])
            ? '<p>' . _("No {$type} address specified") . '</p>'
            : '<p>' . implode('</p><p>', $address[$type]) . '</p>';
    }

    $events = new TimelineEvents();
    $orders = f3d_get_all_user_orders($current_user->ID);
    $events->add('Applied to be a distributor', $since, 'handshake');

    $signedAgreement = get_user_meta($current_user->ID, 'signed_agreement', true);

    if (preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $signedAgreement)) {
        $events->add(__('Signed agreement', 'storefront'), $signedAgreement, 'pen');
    }

    foreach ($orders as $order) {
        $events->add(__('Placed order', 'storefront') . "#{$order['id']}", $order['date'], 'mini-cart');
    }

    $subDistributors = get_users(array('meta_key' => '_sub-distributor-of', 'meta_value' => $current_user->ID));

    return [
        'events' => $events,
        'since' => $since,
        'country' => $country,
        'discount' => $discount,
        'default_currency' => $defCurrency,
        'currency' => $currency,
        'currency_sign' => $currency_sign,
        'currency_name' => $currency == __("Not set") ? "GBP" : $currency,
        'company' => $company,
        'shipping_address' => $address['shipping'],
        'billing_address' => $address['billing'],
        'sub_distributors' => $subDistributors,
    ];

    // get all sub distributors of the current user

}

/**
 * Get scanner product
 *
 * @param string $country
 *
 * @return WC_Product
 */
function get_scanner_product($country = 'ROW')
{
    // fixme hack
    switch ($country) {
        case 'USA':
            return (new WC_Product(USA_SCANNER_PID));
        default:
            return (new WC_Product(SCANNER_PID));
    }
}

/**
 * Get scanner targets product
 *
 * @param string $country
 *
 * @return WC_Product
 */
function get_target_product($country = 'ROW')
{
    // fixme hack
    switch ($country) {
        case 'USA':
            return (new WC_Product(USA_TARGET_PID));
        default:
            return (new WC_Product(TARGET_PID));
    }
}

function create_new_blog_pages()
{
    $page_names = [
        'Distributor portal' => '/distributor-portal',
    ];

    // These settings will be shared across all pages.
    $page_settings = [
        'post_status' => 'publish',
        'post_type' => 'page',
    ];

    // This needs to be initialized before the loop.
    $post_id = - 1;
    $home_id = - 1;

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
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => __(ucfirst($page_name)),
            'menu-item-url' => home_url($page_uri),
            'menu-item-status' => 'publish'
        ]);
    }

    // Set the front page to 'home'.
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
}

// Custom excerpts and content. Usage: echo excerpt(length)
function excerpt($limit)
{
    $excerpt = explode(' ', do_shortcode(get_the_content()), $limit);

    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt) . '...';
    } else {
        $excerpt = implode(" ", $excerpt);
    }

    $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);

    return $excerpt;
}

// We need to add a new dropdown for selecting default currency in user settings page //
add_action('show_user_profile', 'default_currency_dropdown');
add_action('edit_user_profile', 'default_currency_dropdown');

function default_currency_dropdown($user)
{
    $selected = esc_attr(get_user_meta($user->ID, 'default_currency', true));
    ?>
    <tr class="currency-default">
        <th>
            <label for="default_currency">Choose your default currency</label>
        </th>
        <td>
            <select name="default_currency" id="default_currency">
                <option
                    value="GBP" <?php ($selected == 'GBP') ? print "selected='selected'" : ""; ?>> <?php _e('Pounds Sterling [&pound;]',
                        'storefront'); ?> </option>
                <option
                    value="EUR" <?php ($selected == 'EUR') ? print "selected='selected'" : ""; ?>> <?php _e('Euros [&euro;]',
                        'storefront'); ?> </option>
                <option
                    value="USD" <?php ($selected == 'USD') ? print "selected='selected'" : ""; ?>> <?php _e('US Dollars [$]',
                        'storefront'); ?> </option>
            </select>
        </td>
    </tr>
    <?php
}

// Save  shipping_email
add_action( 'personal_options_update', 'save_shipping_email' );
add_action( 'edit_user_profile_update', 'save_shipping_email' );

function save_shipping_email( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    update_user_meta( $user_id, 'shipping_email', $_POST['shipping_email'] );
}

// Now let's hook in and save default currency
add_action('personal_options_update', 'save_currency_dropdown_option');
add_action('edit_user_profile_update', 'save_currency_dropdown_option');

function save_currency_dropdown_option($user_id)
{
    if (!add_user_meta($user_id, 'default_currency', sanitize_text_field($_POST['default_currency']), true)) {
        update_user_meta($user_id, 'default_currency', sanitize_text_field($_POST['default_currency']));
    }
}

// Replace currency switcher shortcode callback to the overriden class method that is "Currency Aware"
remove_shortcode('aelia_currency_selector_widget');
add_shortcode('aelia_currency_selector_widget', array('WooCommerceCurrencySwitcher', 'render_currency_selector'));
// End of shortcode replace

add_action('init', 'clear_currency_dropdown_cookie');

function clear_currency_dropdown_cookie()
{
    unset($_COOKIE['aelia_op_currency']);
    setcookie('aelia_op_currency', isset($_POST['aelia_cs_currency']) ? $_POST['aelia_cs_currency'] : '',
        time() - 60 * 10, COOKIEPATH, COOKIE_DOMAIN, false);

    Aelia_SessionManager::set_value(AELIA_CS_USER_CURRENCY, isset($_POST['aelia_cs_currency']) ? $_POST['aelia_cs_currency'] : '');
}


function f3d_check_login($user, $password)
{
    return (!$user || in_array('inactive', $user->roles))
        ? null
        : $user;
}

add_filter('wp_authenticate_user', 'f3d_check_login', 10, 2);

// Remove admin bar from public website
add_filter('show_admin_bar', '__return_false');


// Remove unwanted Woo styles
// Remove each style one by one
// add_filter( 'woocommerce_enqueue_styles', 'fuel_woo_dequeue_styles' );
// function fuel_woo_dequeue_styles( $enqueue_styles ) {
//   unset( $enqueue_styles['woocommerce-general'] );  // Remove the gloss
//   unset( $enqueue_styles['woocommerce-layout'] );   // Remove the layout
//   unset( $enqueue_styles['woocommerce-smallscreen'] );  // Remove the smallscreen optimisation
//   return $enqueue_styles;
// }

function my_front_end_login_fail($username)
{
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';  // where did the post submission come from?
    // if there's a valid referrer, and it's not the default log-in screen
    if (!empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
        wp_redirect(@reset(explode('?',
                $referrer)) . '?login=failed');  // let's append some information (login=failed) to the URL for the theme to use
        exit;
    }
}

add_action('wp_login_failed', 'my_front_end_login_fail');  // hook failed login

/**
 * Fix TG currency issue as per the reported bug:
 * PORT-117: Euro orders appearing as Sterling in TradeGecko
 */
add_filter('wc_tradegecko_new_order_query', 'fix_tradegecko_currency', 10, 2);

function fix_tradegecko_currency($order_info, $order_id)
{
    $tg_currencies = (new WC_CurrencyTG())->get_currencies();
    $order_currency = (new WC_Order($order_id))->get_order_currency();

    if (!$order_currency) {
        $order_currency = Aelia_SessionManager::get_value(AELIA_CS_USER_CURRENCY, false);
    }

    // Forward user selected currency to Trade Gecko
    foreach ($tg_currencies as $active_currency) {
        if (strtolower($active_currency->iso) == strtolower($order_currency)) {
            $order_info['order']['currency_id'] = $active_currency->id;
        }
    }

    return $order_info;
}

// We need this to instantiate and request currencies from TG
if (class_exists('WC_TradeGecko_Init')) {
    class WC_CurrencyTG extends WC_TradeGecko_Init
    {
        public function get_currencies()
        {
            try {
                $wc_api = new WC_TradeGecko_API();
                $tg_currencies_data = $this->get_decoded_response_body($wc_api->process_api_request('GET',
                    'currencies'));
                $currencies = isset($tg_currencies_data->currencies) ? $tg_currencies_data->currencies : $tg_currencies_data->currency;
            } catch (Exception $e) {
                return false;
            }

            return $currencies;
        }
    }
}

/**
 * Save user meta when activating account
 */
function add_f3d_meta_fields_to_user($user_id, $password, $meta)
{
    global $wpdb;

    if (is_array($meta)) {
        $data = $meta;
        $data['ID'] = $user_id;

        wp_update_user($data);
    }

    if (!empty($meta['where_buy'])) {
        update_user_meta($user_id, 'where_buy', $meta['where_buy']);
    }

    if (!is_admin()) {
        $caps = get_user_meta($user_id, $wpdb->prefix . 'capabilities');
        if (!empty($caps)) {
            delete_user_meta($user_id, $wpdb->prefix . 'capabilities');
            $blogId = getBlogIdByIp();
            if ($blogId == 4) {
                add_user_meta($user_id, USA_STORE, $caps);
            } else {
                add_user_meta($user_id, ROW_STORE, $caps);
            }
        }
    }
}

add_action('wpmu_activate_user', 'add_f3d_meta_fields_to_user', 10, 3);


/**
 * Alters the currency labels that will be displayed on the currency selector widget.
 *
 * @param array  currencies An array of currencies passed by the widget.
 * @param string widget_type The type of widget beind displayed (e.g. 'dropdown' or 'buttons').
 * @param string widget_title The widget title
 * @param string widget_template_name The template file that is going to be used to render the widget.
 *
 * @return array
 */
function f3d_currency_labels($currencies, $widget_type = null, $widget_title = null, $widget_template_name = null)
{
    $currencies['GBP'] = '&pound; Sterling';
    $currencies['USD'] = '$ Dollar';
    $currencies['EUR'] = '&euro; Euro';

    return $currencies;
}

add_filter('wc_aelia_currencyswitcher_widget_currency_options', 'f3d_currency_labels', 10, 4);

function disallow_distributors_to_access_cart_and_store()
{
    global $current_user;

    if (is_user_logged_in() && in_array('distributor', $current_user->roles)) {
        if (is_page(get_option('woocommerce_shop_page_id', 0)) || is_page(get_option('woocommerce_cart_page_id', 0))) {
            wp_redirect(home_url('/distributor-portal/#tab1'));
            exit;
        }

        // it seems the above check sometimes fails for the shop, so as a failsafe we are going to also check URLs
        if (stripos($_SERVER['REQUEST_URI'], '/shop/') !== false || stripos($_SERVER['REQUEST_URI'],
                '/cart/') !== false
        ) {
            wp_redirect(home_url('/distributor-portal/#tab1'));
            exit;
        }
    }
}

add_action('template_redirect', 'disallow_distributors_to_access_cart_and_store');


function f3d_lostpassword_url_fix($lostpassword_url, $redirect)
{
    return home_url('/reset-password/');
}

add_filter('lostpassword_url', 'f3d_lostpassword_url_fix', 11, 2);


/**
 * Change In Stock / Out of Stock Text
 */

add_filter('woocommerce_get_availability', 'f3d_itkem_availability', 1, 2);
function f3d_itkem_availability($availability, $_product)
{

    $availability['available'] = $_product->is_in_stock();

    $availability['amount'] = 0;
    $availability['amount_min'] = 0;
    $availability['amount_max'] = 0;
    $availability['amount_html'] = '';
    $availability['is_sold_individually'] = $_product->is_sold_individually();
    $availability['backorders_allowed'] = $_product->backorders_allowed();

    $min_value = 1;
    $max_value = $_product->get_stock_quantity();
    $min_value_html = apply_filters('woocommerce_quantity_input_min',
        is_numeric($min_value) && !$availability['is_sold_individually'] ? 'min="' . $min_value . '"' : '', $_product);
    $max_value_html = apply_filters('woocommerce_quantity_input_max',
        is_numeric($max_value) && !$availability['is_sold_individually'] ? 'max="' . $max_value . '"' : '', $_product);

    if ($availability['available']) {
        // Change In Stock Text
        $availability['availability'] = __('<i class="fa fa-check"></i> ' . __('In Stock', 'storefront'), 'storefront');
        $availability['class'] = __('available', 'storefront');
        $availability['stock_quantity'] = $_product->get_stock_quantity();

        $availability['amount_html'] = '<div class="row"><div class="col-xs-5 counter-input-group__wrapper cart_item"><div class="input-group counter-input-group quantity_wrapper">';
        $availability['amount_html'] .= '<span class="input-group-btn"><input class="btn btn-lg minus" type="button" value="-"/></span>';
        $availability['amount_html'] .= '<input type="number" step="1" ' . $min_value_html . ' ' . $max_value_html . ' name="quantity" value="1" title="' . esc_attr_x('Qty',
                'Product quantity input tooltip',
                'woocommerce') . '" class="form-control input-lg qty-cross" size="4" />';
        $availability['amount_html'] .= '<span class="input-group-btn"><input class="btn btn-lg plus" type="button" value="+"/></span>';
        $availability['amount_html'] .= '</div></div></div>';
    } else {
        // Change Out of Stock Text
        $availability['availability'] = __('<i class="fa fa-times"></i> ' . __('Sold Out', 'storefront'), 'storefront');
        $availability['class'] = __('not-available', 'storefront');
        $availability['amount_html'] = $availability['availability'];
    }

    return $availability;
}


/**
 * Grab the Product description and add it to the Shop page
 */
add_action('f3d_item_description', 'woocommerce_template_single_excerpt', 5);


/**
 * Add custom wrappers to checkout forms
 */
function f3d_woocommerce_form_field($key, $args, $value)
{ ?>
    <div class="form-group">
        <label
            for="<?php echo $key; ?>"><?php echo isset($args['label']) ? $args['label'] : ''; ?><?php echo (isset($args['required']) && $args['required']) ? ' *' : '' ?></label>

        <?php
        $args['label'] = null;
        $args['class'][] = 'form-control-wrapper';

        if (!isset($args['required'])) {
            $args['required'] = null;
        }
        if (!isset($args['validate'][0])) {
            $args['validate'][0] = null;
        }

        if ($args['required'] && $args['validate'][0] != 'email' && $args['validate'][0] != 'phone') {
            $args['custom_attributes']['data-validation'] = 'required';
        } else if ($args['validate'][0] == 'email') {
            $args['custom_attributes']['data-validation'] = 'email';
        } else if ($args['validate'][0] == 'phone') {
            $args['custom_attributes']['data-validation'] = 'number';
        }

        woocommerce_form_field($key, $args, $value);
        ?>

    </div>
<?php }

/**
 * Change number of thumbnail columns
 */
add_filter('woocommerce_product_thumbnails_columns', 'f3d_product_thumbnails_columns');
function f3d_product_thumbnails_columns()
{
    return 1; // .last class applied to every 4th thumbnail
}

/**
 * Remove tabs on single product page
 */
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);

function woo_remove_product_tabs($tabs)
{

    unset($tabs['description']);            // Remove the description tab
    unset($tabs['reviews']);                    // Remove the reviews tab
    unset($tabs['additional_information']);    // Remove the additional information tab

    return $tabs;

}


/**
 * UK blog ID
 * @return int
 *
 */
function isUkShop()
{
    return get_current_blog_id() == 1;
}

/**
 * USA/CA/MX blog ID
 * @return bool
 *
 */
function isUsaShop()
{
    return get_current_blog_id() == 4;
}

/**
 * Returns whether the current logged in user is a US-based customer*
 * Returns false if
 *
 * @return boolean
 */
function isUsaCustomer()
{

    $current_user = wp_get_current_user();

    if ($current_user->exists())
    {
        $redirectCountries = ['US', 'CA', 'MX'];
        $shippingCountry   = get_user_meta($current_user->ID, 'shipping_country', true);
        $billingCountry    = get_user_meta($current_user->ID, 'billing_country', true);

        return in_array($shippingCountry, $redirectCountries) || in_array($billingCountry, $redirectCountries);

    } else {

        return (getBlogIdByIp() == 4);

    }

}

add_filter('add_custom_options', 'add_custom_options');
function add_custom_options($options)
{
    $options['html_in_footer'] = [
        'title' => 'HTML code in the footer',
        'default' => '',
        'autoload' => 'no',
        'type' => 'textarea', // text
    ];

    $options['checkout_thank_you_after'] = [
        'title' => 'HTML code for the Thank You Page',
        'default' => '',
        'autoload' => 'no',
        'type' => 'textarea', // text
    ];

    return $options;
}

add_action('wp_footer', 'add_html_footer', 100);
function add_html_footer()
{
    echo apply_filters('get_custom_option', '', 'html_in_footer');
}

# schedule_custom_woo_orders
require 'inc/custom_emails.php';

# custom shipping
require 'inc/custom_shipping.php';

# subscriptions
require 'inc/subscription.php';

# upgrade
require 'inc/upgrade.php';

add_filter('aelia_session_key_user', 'fix_aelia_session_key_user');
function fix_aelia_session_key_user($key)
{
    if (is_user_logged_in()) {
        $key .= get_current_user_id();
    }

    return $key;
}

// todo: create cron-init file and move to
if (defined('DOING_CRON') && DOING_CRON) {
    /**
     * @see wpMandrill::mail()
     * @see wp_mail()
     *
     * $atts = apply_filters( 'wp_mail', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) );
     */
    add_filter('wp_mail', 'debug_cron_wp_mail');
    function debug_cron_wp_mail($atts)
    {
        $to = apply_filters('get_custom_option', '', 'debug_cron_email');
        if ($to) {
            $atts['subject'] .= ' ( ' . $atts['to'] . ' )';
            $atts['to'] = $to;
        }

        return $atts;
    }
}

add_filter('add_custom_options', 'add_custom_option_debug_cron');
function add_custom_option_debug_cron($options)
{
    $options['debug_cron_email'] = [
        'title' => 'Cron - Auto change of email',
        'default' => '',
        'autoload' => 'no',
        'type' => 'email',
    ];

    return $options;
}


add_filter('add_custom_options', 'box_client_id');
function box_client_id($options)
{
    $options['box_client_id'] = [
        'title' => 'BOX Authorization[BOX_CLIENT_ID]',
        'default' => 'obqeoot8ypve6t2xvtkkupyaksurhd6r',
        'autoload' => 'no',
        'type' => 'text',
    ];

    return $options;
}

add_filter('add_custom_options', 'box_client_secret');
function box_client_secret($options)
{
    $options['box_client_secret'] = [
        'title' => 'BOX Authorization[BOX_CLIENT_SECRET]',
        'default' => 'GldFwIR76QXiGrgCR6Nj7bAqJ9GiyyIz',
        'autoload' => 'no',
        'type' => 'text',
    ];

    return $options;
}

add_filter('add_custom_options', 'box_upload_folder_id');
function box_upload_folder_id($options)
{
    $options['box_upload_folder_id'] = [
        'title' => 'BOX Authorization[BOX_UPLOAD_FOLDER_ID]',
        'default' => '3197887577',
        'autoload' => 'no',
        'type' => 'text',
    ];

    return $options;
}

add_filter('add_custom_options', 'box_shared_folder_id');
function box_shared_folder_id($options)
{
    $options['box_shared_folder_id'] = [
        'title' => 'BOX Authorization[BOX_SHARED_FOLDER_ID]',
        'default' => '3482826220',
        'autoload' => 'no',
        'type' => 'text',
    ];

    return $options;
}

/**
 * Returns whether the given order contains at least a scanner
 *
 * @param WC_Order $order
 *
 * @return boolean
 */
function order_contains_scanner_product($order)
{
    $scannerProductId = isUsaShop()
        ? USA_SCANNER_PID
        : SCANNER_PID;

    foreach ($order->get_items() as $orderItem) {
        if ($orderItem['product_id'] == $scannerProductId) {
            return true;
        }
    }

    return false;
}

add_action('wp_footer', 'trigger_checkout_error');
function trigger_checkout_error()
{
    // todo: move to nalpeiron plugin
    ?>
    <script>
        jQuery(document).ready(function ($) {
            jQuery('body').on('checkout_error', function () {
                var nalpeironError = jQuery('.woocommerce-error .nalpeiron-error').text();
                if (nalpeironError) {
                    jQuery('#customer_details, .cart-total').remove();
                }
            });
        });
    </script>
    <?php
}

add_filter('wpcf7_special_mail_tags', 'wpcf7_special_mail_tag_userdata', 10, 2);
function wpcf7_special_mail_tag_userdata($output, $name)
{
    // For backwards compat.
    $name = preg_replace('/^wpcf7\./', '_', $name);
    global $current_user;
    if ('user_author_login' == $name) {
        $output = $current_user->user_login;
    } elseif ('user_author_email' == $name) {
        $output = $current_user->user_email;
    }

    return $output;
}

/**
 * Return JS code for hotjar marketing on the specific pages
 *
 * @param bool|true $showInFront
 *
 * @return string
 */
function show_hotjar_marketing_pixel($showInFront = true)
{
    $pageID = get_the_ID();
    $targetIDs = [769, 5517, 2299, 2300, 2301];

    $show = $showInFront ? is_front_page() : false;
    $show = $show || in_array($pageID, $targetIDs);

    if ($show) {
        $output = '<script src="' . get_template_directory_uri() . '/js/hotjarcode.js"></script>';
    } else {
        $output = '';
    }

    return $output;

}


/**
 * Returns true if the order contains a downloadable product(s) ONLY.
 *
 * @param WC_Order $order
 *
 * @return bool
 * @internal param $
 */
add_filter('wc_order_has_downloadable_items_only', 'has_downloadable_items_only');
function has_downloadable_items_only(WC_Order $order)
{
    $has_downloadable_items_only = (count($order->get_items()) > 0) ? true : false;

    foreach ($order->get_items() as $item) {
        $_product = $this->get_product_from_item($item);
        $has_downloadable_items_only = $has_downloadable_items_only && ($_product && $_product->is_downloadable());
    }

    return $has_downloadable_items_only;
}

add_filter('woocommerce_cart_subtotal', 'cart_subtotal_extra', 11, 100);
function cart_subtotal_extra($cart_subtotal, $compound, $cart)
{
    $asterisk = strpos($cart_subtotal, __('now then', 'storefront')) !== false? '*':'';

    $out = str_replace(__('now then', 'storefront'),
        '<br/><small>'.__('Please note that your Studio software will automatically renew after 12 months from date of purchase at a cost of', 'storefront').'</small>&nbsp;',
        $cart_subtotal);
    $out .= $asterisk;

    return $out;
}

add_filter('woocommerce_cart_total', 'cart_total_extra', 11, 100);
function cart_total_extra($cart_total)
{
    $out = $cart_total;
    if (strpos($cart_total, 'now then') !== false) {
        $out .= '<br/><small>' . __('* Terms and conditions apply.','storefront') . '</small>';
    }

    return $out;
}

// Load theme depended CSS and JS files
add_action('wp_enqueue_scripts', 'f3d_scripts_load');
function f3d_scripts_load()
{
    $assets_path          = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
    $frontend_script_path = $assets_path . 'js/frontend/';

    $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    $folder = get_template_directory_uri();
    $folderCSS = $folder . '/css';
    $folderJS = $folder . '/js';

    $ajax_cart_en = 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' );

    wp_enqueue_style('owl-carousel', $folderCSS . '/owl.carousel.css');
    wp_enqueue_style('dropzone', $folderCSS . '/dropzone.min.css');
    wp_enqueue_style('tooltipster', $folderCSS . '/tooltipster.css');

    wp_enqueue_script('jquery-ui-spinner');

    wp_enqueue_script('owl-carousel-js', $folderJS . '/owl.carousel.min.js', ['jquery']);
    wp_enqueue_script('form-validator-js', $folderJS . '/form-validator/jquery.form-validator' . $suffix . '.js', ['jquery']);
    wp_enqueue_script('form-validator-js-security', $folderJS . '/form-validator/security' . $suffix . '.js', ['jquery']);
    wp_enqueue_script('dropzone-js',$folderJS . '/dropzone.min.js');
    wp_enqueue_script('tooltipster-js', $folderJS . '/jquery.tooltipster.min.js');

    wp_enqueue_script('bootstrap-js', $folderJS . '/bootstrap' . $suffix . '.js', [], false, true);

    wp_enqueue_script('scrollbar-js', $folderJS . '/perfect-scrollbar.jquery' . $suffix . '.js', [], false, true);

    wp_enqueue_script('application-js', $folderJS . '/app' . $suffix . '.js', [], false, true);
    wp_enqueue_script('navigation-js', $folderJS . '/navigation.min.js', [], false, true);
    wp_enqueue_script('app-link-service-js', $folderJS . '/skip-link-focus-fix.min.js', [], false, true);

    wp_deregister_script('chosen');

    if ( $ajax_cart_en ) {
        wp_deregister_script('wc-add-to-cart');
        wp_register_script ('wc-add-to-cart', $folderJS . '/checkout/add-to-cart' . $suffix . '.js');
        wp_enqueue_style ( 'wc-add-to-cart');
    }

    if (is_checkout() || is_cart() || is_page(get_option('woocommerce_myaccount_page_id')) || is_page('edit-profile')) {
        wp_enqueue_style('country-select-chosen', $folderCSS . '/country-select-chosen.css');
        wp_enqueue_script('chosen-jquery-js', $folderJS . '/chosen/chosen.jquery' . $suffix . '.js', ['jquery'], false);
        wp_enqueue_script('chosen-frontend-js', $folderJS . '/chosen/chosen-frontend' . $suffix . '.js', ['jquery', 'chosen-jquery-js'], false, true);
        wp_enqueue_script('wc-country-select');
        wp_enqueue_script('country_selector-js', $folderJS . '/country-select/country-selector' . $suffix . '.js', ['jquery', 'chosen-jquery-js', 'chosen-frontend-js'], false, true);
    }

    if (is_checkout()) {
        wp_deregister_script('wc-checkout');
        wp_register_script('wc-checkout', $folderJS . '/checkout/checkout' . $suffix . '.js', array('jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n'));
        wp_enqueue_script('wc-checkout');
        wp_deregister_style('jquery-steps');
        wp_register_style('jquery-steps', $folderCSS . '/jquery.steps-checkout.css');
        wp_enqueue_style('jquery-steps');
    }
}

add_filter('woocommerce_default_address_fields', 'set_country_field_top_address_fields_reorder');
function set_country_field_top_address_fields_reorder($defaultFields)
{

    $keyCountry = 'country';

    $outFields = array(
        'first_name' => array(
            'label' => __('First Name', 'storefront'),
            'placeholder' => __('Your first name', 'storefront'),
            'required' => true,
            'custom_attributes' => array('data-validation'=>'required', 'required'=>'required'),
            'class' => array('form-row-wide', 'customer-field')
        ),
        'last_name' => array(
            'label' => __('Last Name', 'storefront'),
            'placeholder' => __('Your last name', 'storefront'),
            'required' => true,
            'custom_attributes' => array('data-validation'=>'required', 'required'=>'required'),
            'class' => array('form-row-wide', 'customer-field')
        ),
        'company' => array(
            'label' => __('Company', 'storefront'),
            'placeholder' => __('Your company (optional)', 'storefront'),
            'required' => false,
            'class' => array('form-row-wide', 'customer-field')
        ),
        'country' => array(
            'type'     => 'country',
            'label'    => __( 'Country', 'storefront' ),
            'required' => true,
            'custom_attributes' => array('data-validation'=>'required', 'required'=>'required'),
            'class'    => array( 'form-row-wide', 'address-field' ),
        ),
        'address_1' => array(
            'label' => __('Address Line 1', 'storefront'),
            'placeholder' => __('Street address', 'storefront'),
            'required' => true,
            'custom_attributes' => array('data-validation'=>'required', 'required'=>'required'),
            'class' => array('form-row-wide', 'address-field')
        ),
        'address_2' => array(
            'label' => __('Address Line 2', 'storefront'),
            'placeholder' => __('Apartment, suite, unit etc. (optional)', 'storefront'),
            'class' => array('form-row-wide', 'address-field'),
            'required' => false
        ),
        'county' => array(
            'label' => __('County', 'storefront'),
            'placeholder' => __('County or state', 'storefront'),
            'class' => array('form-row-wide', 'county-field', 'address-field'),
            'required' => false
        ),
        'email' => array(
            'label' => __('Email Address', 'storefront'),
            'placeholder' => __('Email address', 'storefront'),
            'required' => true,
            'validate' => array('email'),
            'custom_attributes' => array('data-validation'=>'email', 'required'=>'required'),
            'class' => array('form-row-wide', 'email-field')
        ),
        'city' => array(
            'label'       => __( 'Town / City', 'storefront' ),
            'placeholder' => __( 'Town or city', 'storefront' ),
            'required'    => true,
            'custom_attributes' => array('data-validation'=>'required', 'required'=>'required'),
            'class'       => array( 'form-row-wide', 'address-field' )
        ),
        'state' => array(
            'type'        => 'state',
            'label'       => __( 'State', 'storefront' ),
            'placeholder' => __( 'State or county', 'storefront' ),
            'required'    => false,
            'class'       => array('form-row-wide', 'county-field', 'address-field'),
            'validate'    => array( 'state' )
        ),
        'postcode' => array(
            'label'       => __( 'Postcode / Zip', 'storefront' ),
            'placeholder' => __( 'Postcode or Zip', 'storefront' ),
            'required'    => true,
            'class'       => array( 'form-row-last', 'address-field' ),
            'clear'       => true,
            'custom_attributes' => array('data-validation'=>'required', 'required'=>'required'),
            'validate'    => array( 'postcode' )
        ),
    );

    $outFields = array_merge($defaultFields, $outFields);

    if (array_key_exists($keyCountry, $outFields)) {
        $topField = [$keyCountry => $outFields[$keyCountry]];
        unset($outFields[$keyCountry]);
        $outFields = $topField + $outFields;
    }

    return $outFields;
}

add_filter( 'woocommerce_billing_fields', 'remove_billing_email', 20 );
function remove_billing_email( $fields ) {
    $keyEmail = 'billing_email';

    // we don't need the billing email for logged users
    if (is_user_logged_in() && array_key_exists($keyEmail, $fields)){
        unset($fields[$keyEmail]);
    }

    return $fields;
}

add_filter( 'woocommerce_shipping_fields', 'remove_shipping_email', 20 );
function remove_shipping_email( $fields ) {
    //return $fields;
    $keyEmail = 'shipping_email';

    // we don't need the billing email for logged users
    if (is_user_logged_in() && array_key_exists($keyEmail, $fields)){
        unset($fields[$keyEmail]);
    }

    return $fields;
}

add_filter('woocommerce_update_order_review_fragments', 'ss_handle_addresses_fields');
function ss_handle_addresses_fields ($fragments) {
    ob_clean();
    ob_start();
    do_action('woocommerce_checkout_shipping');
    $checkout_shipping_address = ob_get_clean();
    ob_clean();
    ob_start();
    do_action('woocommerce_checkout_billing');
    $checkout_billing_address = ob_get_clean();

    $fragments['.shipping_address'] = $checkout_shipping_address;
    $fragments['.billing_address'] = $checkout_billing_address;

    return $fragments;
}

add_filter('woocommerce_states', 'ss_handle_state_fields');
function ss_handle_state_fields($states)
{

    $states['GB'] = [
        'AV' => 'Avon',
        'BE' => 'Bedfordshire',
        'BK' => 'Berkshire',
        'BU' => 'Buckinghamshire',
        'CA' => 'Cambridgeshire',
        'CH' => 'Cheshire',
        'CL' => 'Cleveland',
        'CO' => 'Cornwall',
        'CD' => 'County Durham',
        'CU' => 'Cumbria',
        'DE' => 'Derbyshire',
        'DV' => 'Devon',
        'DO' => 'Dorset',
        'ES' => 'East Sussex',
        'EX' => 'Essex',
        'GL' => 'Gloucestershire',
        'HA' => 'Hampshire',
        'HE' => 'Herefordshire',
        'HT' => 'Hertfordshire',
        'IW' => 'Isle of Wight',
        'KE' => 'Kent',
        'LA' => 'Lancashire',
        'LE' => 'Leicestershire',
        'LI' => 'Lincolnshire',
        'LO' => 'London',
        'ME' => 'Merseyside',
        'MI' => 'Middlesex',
        'NO' => 'Norfolk',
        'NH' => 'North Humberside',
        'NY' => 'North Yorkshire',
        'NS' => 'Northamptonshire',
        'NL' => 'Northumberland',
        'NT' => 'Nottinghamshire',
        'OX' => 'Oxfordshire',
        'SH' => 'Shropshire',
        'SO' => 'Somerset',
        'SM' => 'South Humberside',
        'SY' => 'South Yorkshire',
        'SF' => 'Staffordshire',
        'SU' => 'Suffolk',
        'SR' => 'Surrey',
        'TW' => 'Tyne and Wear',
        'WA' => 'Warwickshire',
        'WM' => 'West Midlands',
        'WS' => 'West Sussex',
        'WY' => 'West Yorkshire',
        'WI' => 'Wiltshire',
        'WO' => 'Worcestershire',
        'ABD' => 'Scotland / Aberdeenshire',
        'ANS' => 'Scotland / Angus',
        'ARL' => 'Scotland / Argyle & Bute',
        'AYR' => 'Scotland / Ayrshire',
        'CLK' => 'Scotland / Clackmannanshire',
        'DGY' => 'Scotland / Dumfries & Galloway',
        'DNB' => 'Scotland / Dunbartonshire',
        'DDE' => 'Scotland / Dundee',
        'ELN' => 'Scotland / East Lothian',
        'EDB' => 'Scotland / Edinburgh',
        'FIF' => 'Scotland / Fife',
        'GGW' => 'Scotland / Glasgow',
        'HLD' => 'Scotland / Highland',
        'LKS' => 'Scotland / Lanarkshire',
        'MLN' => 'Scotland / Midlothian',
        'MOR' => 'Scotland / Moray',
        'OKI' => 'Scotland / Orkney',
        'PER' => 'Scotland / Perth and Kinross',
        'RFW' => 'Scotland / Renfrewshire',
        'SB' => 'Scotland / Scottish Borders',
        'SHI' => 'Scotland / Shetland Isles',
        'STI' => 'Scotland / Stirling',
        'WLN' => 'Scotland / West Lothian',
        'WIS' => 'Scotland / Western Isles',
        'AGY' => 'Wales / Anglesey',
        'GNT' => 'Wales / Blaenau Gwent',
        'CP' => 'Wales / Caerphilly',
        'CF' => 'Wales / Cardiff',
        'CAE' => 'Wales / Carmarthenshire',
        'CR' => 'Wales / Ceredigion',
        'CW' => 'Wales / Conwy',
        'DEN' => 'Wales / Denbighshire',
        'FLN' => 'Wales / Flintshire',
        'GLA' => 'Wales / Glamorgan',
        'GWN' => 'Wales / Gwynedd',
        'HAM' => 'Wales / Hampshire',
        'MT' => 'Wales / Merthyr Tydfil',
        'MON' => 'Wales / Monmouthshire',
        'PT' => 'Wales / Neath Port Talbot',
        'NP' => 'Wales / Newport',
        'PEM' => 'Wales / Pembrokeshire',
        'POW' => 'Wales / Powys',
        'RT' => 'Wales / Rhondda Cynon Taff',
        'SS' => 'Wales / Swansea',
        'TF' => 'Wales / Torfaen',
        'WX' => 'Wales / Wrexham',
        'ANT' => 'Northern Ireland / County Antrim',
        'ARM' => 'Northern Ireland / County Armagh',
        'DOW' => 'Northern Ireland / County Down',
        'FER' => 'Northern Ireland / County Fermanagh',
        'LDY' => 'Northern Ireland / County Londonderry',
        'TYR' => 'Northern Ireland / County Tyrone',
    ];

    $stateCountriesWhiteList = ['US','CA','MX'];

    $states = array_intersect_key ( $states, array_flip($stateCountriesWhiteList) );

    return $states;
}

function get_order_lines(WC_Order $order)
{
    $item_format = [];

    foreach ($order->get_items() as $item_id => $item) {
        $item['id'] = $item_id;

        $product = $order->get_product_from_item($item);
        $vend = $product ? $product->get_sku() : '';
        $vend .= "3PL";

        // discard taxes and fees
        if (isset($item['type']) && 'line_item' !== $item['type']) {
            continue;
        }
        // get the product
        $product = $order->get_product_from_item($item);

        $order_item = [];

        $order_item['POD'] = $order->id;
        $order_item['VendorPart'] = $vend;
        $order_item['CustomerPart'] = $product ? $product->get_sku() : '';  // handling for permanently deleted product
        $order_item['Quantity'] = $item['qty'];
        $order_item['Description'] = $item['name'];
        $order_item['Price'] = $order->get_item_total($item);

        array_push($item_format, $order_item);
    }

    return $item_format;
}

add_filter('wc_customer_order_xml_export_suite_order_export_order_list_format', 'ss_make_xml_export_order_list_format',
    10, 3);
function ss_make_xml_export_order_list_format($format, WC_Order $order)
{

    $order->order_date = substr($order->order_date, 0, 10);
    $order->completed_date = substr($order->completed_date, 0, 10);

    $order->order_date = str_replace("-", "", $order->order_date);
    $order->completed_date = str_replace("-", "", $order->completed_date);

    $format = array(
        'PO' => $order->id,
        'CustomerOrderNumber' => '#' . $order->get_order_number(),
        'OrderDate' => $order->order_date,
        'ShipName' => $order->shipping_first_name . ' ' . $order->shipping_last_name,
        'CompanyName' => $order->shipping_company,
        'ShipAddr1' => $order->shipping_address_1,
        'ShipAddr2' => $order->shipping_address_2,
        'ShipCity' => $order->shipping_city,
        'ShipState' => $order->shipping_state,
        'ShipZip' => $order->shipping_postcode,
        'ShipCountry' => $order->shipping_country,
        'OrderDetail' => get_order_lines($order)

    );

    return $format;
}

add_filter('wc_customer_order_xml_export_suite_order_export_format', 'ss_make_xml_export_order_export_format', 10, 2);
function ss_make_xml_export_order_export_format($format, $orders)
{
    $format = array('Batch' => array('Order' => $orders));

    return $format;
}

/*-------------------------------------------------------------------------------------------*/
/* AJAX FRAGMENTS */
/*-------------------------------------------------------------------------------------------*/
add_filter('woocommerce_add_to_cart_fragments', 'header_add_to_cart_fragment', 10);
add_filter('add_to_cart_fragments', 'header_add_to_cart_fragment', 10);
function header_add_to_cart_fragment($fragments)
{

    ob_start();

    WC()->cart->calculate_totals();
    $quantity =  WC()->cart->cart_contents_count;
    $cart =  WC()->cart->get_cart();
    $_recentAddedProductTitle = isset($_SESSION['recentProduct'])?$_SESSION['recentProduct']['title']:__('Item', 'storefront');

    ?>

    <span class="cart" style="position: relative; display: inline-block;">
    <?php
    if ($quantity > 0) {
        ?>
        <style>
            .new-cart:before {
                content: "";
                position: absolute;
                bottom: 100%;
                right: 20px;
                width: 0;
                height: 0;
                border: 10px solid;
                border-color: transparent transparent #eee;
            }

            .new-cart, .new-cart-mobile {
                position: absolute;
                right: 0;
                top: 100%;
                margin-top: 10px;
                width: 290px;
                max-height: 440px;
                background: #eee;
                border: 1px solid #ccc;
                border-radius: 5px;
                z-index: 100000;
            }
            .new-cart-mobile {
                margin: 9px -15px;
                border: 1px solid #666;
                border-radius: 0;
                background-color: #666;
                color: #fff;
                text-align: center;
                font-size: 1.6rem;
                opacity: 0.9;
            }

            .new-cart-header {
                background-color: #eee;
                height: 50px;
            }

            .new-cart-header h4 {
                padding: 18px 0 0 20px;
                font-size: 18px;
            }

            .new-cart-header h4.item-added {
                color: #9ac31c;
                text-transform: uppercase;
            }

            .new-cart-items-list {
                max-height: 150px;
                width: 288px;
                background-color: #fff;
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                position: relative;
                padding: 0;
                overflow: hidden;
            }

            .new-cart-footer {
                margin-bottom: 0 !important;
            }

            .new-cart-total {
                border-bottom: 1px solid #ccc;
                margin-bottom: 20px;
                font-weight: 400;
            }

            .total, .title {
                color: #222;
                font-size: 16px;
                text-align: left;
            }

            .amount {
                color: #9ac31c;
                font-size: 19px;
                text-align: right;
            }

            .view_cart_button, .checkout_button {
                font-size: 14px;
                line-height: 1.4;
                font-weight: bold;
                border-radius: 5px;
                margin-bottom: 10px;
                text-transform: uppercase;
                padding: 7px;
            }

            .view_cart_button {
                color: #fff;
                background-color: #999;
                border-color: #999;
            }

            .checkout_button {
                font-size: 16px;
                line-height: 1.8;
            }

            .cart_xs_block .checkout_button{
                font-size: 14px;
                line-height: 1.4;
            }

            .new-cart-item {
                padding: 10px 0 10px 0;
                display: block;
                height: 85px;
            }

            .new-cart-item .title {
                font-size: 12px;
            }

            .new-cart-item .quantity {
                font-size: 10px;
                margin-left: 5px;
            }

            .price-line, .price-line .amount {
                color: #727272;
                font-size: 12px;
                font-weight: bold;
            }

            .new-cart-item .remove-product {
                font-size: 16px;
                font-weight: bold;
                color: #f00;
            }

            a.checkout_button span {
                margin: 0 15px;
            }

            .product_thumbnail img {
                border: 1px #eee solid;
            }

            .no_margin_left {
                margin-left: 0;
                padding-left: 0;
            }

            .no_margin_right {
                margin-right: 0;
                padding-right: 0;
            }
            .cart_xs_block .view_cart_button:hover {
                background-color: #aaa;
                border-color: #aaa;
            }
            .cart_xs_block .view_cart_button {
                color: #fff;
                background-color: #999;
                border-color: #999;
            }

            .xs-button-checkout {
                padding: 0 0 0 2px;
            }

            .xs-button-checkout a span {
                font-size: 16px;
            }

            .xs-button-view {
                padding: 0 2px 0 0;
            }

            .xs-button-view a {
                font-size: 16px;
            }

        </style>

        <a href="#" class="nav-buy-btn cart" id="cart-link" data-cart_shield="<?= rand(1, 65535); ?>">
            <i class="nav-control-link-icon fa fa-shopping-cart"></i>
            <span class="buy-counter" data-cart_total_count="<?= $quantity ?>"><?= $quantity ?></span>&nbsp;
            <span class="nav-control-link-label buy-items"><?= _n('item', 'items', $quantity, 'storefront') ?></span>
        </a>

        <div class="new-cart hidden" id="cart-popup">
            <div class="cart_xs_block hidden-sm hidden-md hidden-lg">
                <div class="container-fluid">
                    <div class="col-xs-12">
                        <p><?= $_recentAddedProductTitle . ' ' . __('has been added to cart', 'storefront'); ?></p>
                    </div>
                </div>
                <div class="container-fluid new-cart-buttons">
                    <div class="col-xs-4 xs-button-view">
                        <a href="<?= WC()->cart->get_cart_url(); ?>"
                           class="btn btn-block view_cart_button"><?= __('View cart', 'storefront'); ?></a>
                    </div>
                    <div class="col-xs-8 xs-button-checkout">
                        <a href="<?=  WC()->cart->get_checkout_url(); ?>"
                           class="btn btn-block btn-primary checkout_button"><i
                                class="nav-control-link-icon fa fa-lock"></i><span><?= __('Checkout',
                                    'storefront'); ?></span><i class="nav-control-link-icon fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="new-cart-header hidden-xs">
                <h4 id="cart-popup-header" class="item-added"><?= __('Item added to cart', 'storefront'); ?></h4>
            </div>
            <div id="new-cart-items-list" class="new-cart-items-list hidden-xs">
                <div class="new-cart-items hidden-xs">
                    <?php

                    if (isUsaShop()) {
                        $taxLine =  __('Exc. tax','storefront');
                    } else {
                        $taxLine =  __('Exc. VAT','storefront');
                    }

                    $subscriptionInCart = false;
                    $priceFloatTotalCounter = 0;
                    foreach ($cart as $cart_item_key => $cart_item) {
                        global $post;
                        global $product;
                        $post = get_post($cart_item['product_id']);
                        $product = wc_get_product($cart_item['product_id']);

                        $grouped = $product->is_type('grouped');
                        $parentProduct = wc_get_product($product->get_parent());
                        $Title = $grouped? $parentProduct->get_title()."&nbsp;&#x2192;&nbsp;".$product->get_title():$product->get_title();

                        $subscription = WC_Subscriptions_Product::is_subscription($product->id);
                        $subscriptionInCart = $subscriptionInCart || $subscription;

                        $priceSignUpFloat = WC_Subscriptions_Product::get_sign_up_fee($product->id);

                        $priceSubscriptionFloat = WC_Subscriptions_Product::get_price($product->id);
                        $priceSubscriptionFloat = empty($priceSubscriptionFloat)?0:$priceSubscriptionFloat;
                        $priceSubscriptionFloat = $priceSubscriptionFloat + $priceSignUpFloat;

                        $priceLineFloat = $subscription? $priceSubscriptionFloat : $product->get_price_excluding_tax($cart_item['quantity']);
                        $priceLineHtml = wc_price($priceLineFloat);

                        $priceFloatTotalCounter = $priceFloatTotalCounter + $priceLineFloat;
                        ?>

                        <div class='new-cart-item hidden-xs'>

                            <div class='hidden-xs col-sm-4 product_thumbnail'><?= woocommerce_get_product_thumbnail([66, 66]); ?></div>
                            <div class='hidden-xs col-sm-6 no_margin_right no_margin_left'>
                                <p class='title'><?= $Title; ?></p>
                                <p class='price-line'>
                                    <?php if (!$subscription) : ?>
                                    <span class='quantity'><?= $cart_item['quantity'];?>&nbsp;x&nbsp;</span>
                                    <?php endif; ?>
                                    <?= $priceLineHtml; ?>
                                    <small class="price__suffix"><?= $taxLine; ?></small>
                                </p>
                            </div>

                            <div class='hidden-xs col-sm-2'>
                                <?= apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                    '<a href="#" class="remove-product" title="%s" data-product_key="%s" data-product_id="%s" data-product_sku="%s" data-product_count="%s" data-product_price="%s">&times;</a>',
                                    __('Remove this item', 'storefront'),
                                    esc_attr($cart_item_key),
                                    esc_attr($product->id),
                                    esc_attr($product->get_sku()),
                                    esc_attr($cart_item['quantity']),
                                    esc_attr($priceLineFloat)
                                ), $cart_item_key);
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="new-cart-footer well hidden-xs">
                <div class="inner-row new-cart-total hidden-xs">
                    <div class="row hidden-xs">
                        <div class="hidden-xs col-sm-4 no_margin_right">
                            <span class="total"><?= __('Subtotal', 'storefront'); ?></span>
                        </div>
                        <div class="hidden-xs col-sm-8 no_margin_left">
                            <p id="cart-total" class="amount" data-cart_shield="<?= rand(1, 65535); ?>"
                               data-cart_price_decimals="<?= wc_get_price_decimals(); ?>"
                               data-cart_price_dc_separator="<?= wc_get_price_decimal_separator(); ?>"
                               data-cart_price_th_separator="<?= wc_get_price_thousand_separator(); ?>"
                               data-cart_currency="<?= get_woocommerce_currency_symbol(); ?>"
                               data-cart_total="<?= $priceFloatTotalCounter; ?>"><?=  wc_price($priceFloatTotalCounter); ?>
                                <small class="price__suffix"><?= $taxLine; ?></small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="hidden-xs inner-row new-cart-buttons">
                    <a href="<?=  WC()->cart->get_cart_url(); ?>"
                       class="btn btn-block btn-tertiary view_cart_button"><?= __('View your cart',
                            'storefront'); ?></a>
                    <a href="<?=  WC()->cart->get_checkout_url(); ?>"
                       class="btn btn-block btn-primary checkout_button"><i
                            class="nav-control-link-icon fa fa-lock"></i><span><?= __('Checkout securely',
                                'storefront'); ?></span><i class="nav-control-link-icon fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>

        <?php
    }
    ?>
    </span>
    <?php

    $fragments['span.cart'] = ob_get_clean();

    return $fragments;

}


function collect_currency_info()
{
    global $current_user;

    global $selected_currency;
    global $selected_currency_sign;
    global $selected_currency_sign_icon;

    if (null !== Aelia_SessionManager::get_value(AELIA_CS_USER_CURRENCY)) {
        $selected_currency = Aelia_SessionManager::get_value(AELIA_CS_USER_CURRENCY);
        echo('1 - ' . $selected_currency);
    } elseif (null !== Aelia_SessionManager::get_cookie(AELIA_CS_USER_CURRENCY)) {
        $selected_currency = Aelia_SessionManager::get_cookie(AELIA_CS_USER_CURRENCY);
        echo('2 - ' . $selected_currency);
    } else {
        $selected_currency = esc_attr(get_user_meta($current_user->ID, 'default_currency', true));
        echo('3 - ' . $selected_currency);
    }

    $selected_currency = ($selected_currency) ? $selected_currency : __("Not set");

    switch ($selected_currency) {
        case 'EUR':
            $selected_currency_sign = '&euro;';
            $selected_currency_sign_icon = 'fa-euro';
            break;
        case 'USD':
            $selected_currency_sign = '$';
            $selected_currency_sign_icon = 'fa-usd';
            break;
        default:
            $selected_currency_sign = '&pound;';
            $selected_currency_sign_icon = 'fa-gbp';
            break;
    }

    Aelia_SessionManager::set_value(AELIA_CS_USER_CURRENCY, $selected_currency);

}

// Add action for integration contact form 7 with salesforce
add_action('wpcf7_before_send_mail', 'send_to_salseforce');
function send_to_salseforce($cf7)
{
    $targetEnquiry = ['deferred to r9, just uncomment to enable back'];//['I\'m interested in distributing/reselling, let\'s talk', 'I\'m interested in partnering'];
    $targetLink = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
    $lead_source = sanitize_text_field($cf7->title);
    if ($lead_source == 'Contact Widget') {
        $enquiry = sanitize_text_field($cf7->posted_data["your-recipient"]);
        if (in_array($enquiry, $targetEnquiry)) {
            $email = sanitize_text_field($cf7->posted_data["your-email"]);
            $name = explode(" ", sanitize_text_field($cf7->posted_data["your-name"]), 2);
            $first_name = $name[0] ? $name[0] : 'N/A';
            $last_name = $name[1] ? $name[1] : 'N/A';
            $customer = $cf7->posted_data["your-customer"] == 'Yes' ? 'Existing customer' : 'New customer';
            $message = sanitize_text_field($cf7->posted_data["your-message"]);

            $post_items[] = 'oid=00D20000000CmLc';
            $post_items[] = 'first_name=' . $first_name;
            $post_items[] = 'last_name=' . $last_name;
            $post_items[] = 'email=' . $email;
            $post_items[] = 'lead_source=' . $lead_source;
            $post_items[] = 'description=' . $customer;
            $post_items[] = '00N20000003PCp0=' . $enquiry;
            $post_items[] = '00N20000003PCp5=' . $message;
            if (!empty($name) && !empty($email) && !empty($message)) {
                $post_string = implode('&', $post_items);
                // Create a new cURL resource
                $ch = curl_init();
                if (curl_error($ch) == "") {
                    curl_setopt($ch, CURLOPT_URL, $targetLink);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
                    $response = curl_exec($ch); // Post to Salesforce
                    curl_close($ch); // close cURL resource
                }
            }
        }
    }

    return $cf7;
}

/**
 * Returns an encrypted utf8-encoded & base64_encoded
 *
 * @param string $pure_string    String to encrypt
 * @param string $encryption_key Encryption key
 *
 * @return string                   Encrypted string
 */
function encrypt($pure_string, $encryption_key)
{
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string),
        MCRYPT_MODE_ECB, $iv));

    return $encrypted_string;
}

/**
 * Returns decrypted original string
 *
 * @param string $encrypted_string Encrypted string
 * @param string $encryption_key   Encryption key
 *
 * @return string                   Decrypted string
 */
function decrypt($encrypted_string, $encryption_key)
{
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, base64_decode($encrypted_string),
        MCRYPT_MODE_ECB, $iv);

    return $decrypted_string;
}

add_filter('add_signup_meta', 'do_xor_password');
function do_xor_password($meta)
{
    if (isset($meta['password'])) {
        $meta['password'] = encrypt($meta['password'], ENCRYPTION_KEY);
    }

    return $meta;
}

add_filter('send_password_change_email', 'check_for_user_notice', 100, 3);
function check_for_user_notice($send, $user, $userdata)
{
    return (isset ($userdata['do_not_send_notice']) && ($userdata['do_not_send_notice'] === true)) ? false : $send;
}

add_filter('wpmu_welcome_user_notification', 'set_user_it_own_password', 100, 3);
function set_user_it_own_password($user_id, $password, $meta)
{
    $new_pass = isset($meta['password']) ? decrypt($meta['password'], ENCRYPTION_KEY) : $password;
    $notified_new_user = wp_user_notification_with_its_own_password($user_id, $new_pass, $meta);

    return !$notified_new_user; // prevent system notification
}

if (!function_exists('wp_user_notification_with_its_own_password')) {
    function wp_user_notification_with_its_own_password($user_id, $password, $meta)
    {
        global $current_site;
        wp_update_user(['ID' => $user_id, 'user_pass' => $password, 'do_not_send_notice' => true]);
        $welcome_email = get_site_option('welcome_user_email');

        $user = get_userdata($user_id);

        $welcome_email = apply_filters('update_welcome_user_email', $welcome_email, $user_id, $password, $meta);
        $welcome_email = str_replace('SITE_NAME', $current_site->site_name, $welcome_email);
        $welcome_email = str_replace('USERNAME', $user->user_login, $welcome_email);
        $welcome_email = str_replace('PASSWORD', $password, $welcome_email);
        $welcome_email = str_replace('LOGINLINK', wp_login_url(), $welcome_email);

        $admin_email = get_site_option('admin_email');

        if ($admin_email == '') {
            $admin_email = 'support@' . $_SERVER['SERVER_NAME'];
        }

        $from_name = get_site_option('site_name') == '' ? 'WordPress' : esc_html(get_site_option('site_name'));
        $message_headers = "From: \"{$from_name}\" <{$admin_email}>\n" . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
        $message = $welcome_email;

        if (empty($current_site->site_name)) {
            $current_site->site_name = 'WordPress';
        }

        /**
         * Filter the subject of the welcome email after user activation.
         *
         * @since MU
         *
         * @param string $subject Subject of the email.
         */
        $subject = apply_filters('update_welcome_user_subject',
            sprintf(__('New %1$s User: %2$s'), $current_site->site_name, $user->user_login));

        return wp_mail($user->user_email, wp_specialchars_decode($subject), $message, $message_headers);
    }
}

add_action('wp_ajax_product_remove', 'product_remove');
add_action('wp_ajax_nopriv_product_remove', 'product_remove');
function product_remove()
{
    $cart = WC()->instance()->cart;

    $product_id = $_POST['product_id'];
    $cart_item_key = $_POST['product_key'];

    if (!$cart_item_key) {
        $cart_id = $cart->generate_cart_id($product_id);
        $cart_item_key = $cart->find_product_in_cart($cart_id);
    }

    if ($cart_item_key) {
        return $cart->set_quantity($cart_item_key, 0);
    }

    return false;
}

/**
 * Hook: Empty cart before adding a new product to cart WITHOUT throwing woocommerce_cart_is_empty
 */
add_action('woocommerce_add_to_cart', 'woocommerce_empty_cart_before_add_individually_product', 0);
function woocommerce_empty_cart_before_add_individually_product()
{

    // Get 'product_id' and 'quantity' for the current woocommerce_add_to_cart operation
    if (isset($_GET["add-to-cart"])) {
        $prodId = (int)$_GET["add-to-cart"];
    } else if (isset($_POST["add-to-cart"])) {
        $prodId = (int)$_POST["add-to-cart"];
    } else if (isset($_GET["add_to_cart"])) {
        $prodId = (int)$_GET["add_to_cart"];
    } else if (isset($_POST["add_to_cart"])) {
        $prodId = (int)$_POST["add_to_cart"];
    } else if (isset($_GET["product_id"])) {
        $prodId = (int)$_GET["product_id"];
    } else if (isset($_POST["product_id"])) {
        $prodId = (int)$_POST["product_id"];
    } else if (isset($_GET["quick-buy"])) {
        $prodId = (int)$_GET["quick-buy"];
    } else if (isset($_POST["quick-buy"])) {
        $prodId = (int)$_POST["quick-buy"];
    } else if (isset($_GET["quick_buy"])) {
        $prodId = (int)$_GET["quick_buy"];
    } else if (isset($_POST["quick_buy"])) {
        $prodId = (int)$_POST["quick_buy"];
    } else {
        $prodId = null;
    }
    if (!empty($prodId)) {
        if (isset($_GET["quantity"])) {
            $prodQty = (int)$_GET["quantity"];
        } else if (isset($_POST["quantity"])) {
            $prodQty = (int)$_POST["quantity"];
        } else {
            $prodQty = 1;
        }

        // Check if the new product has ISI type or has not
        $_recentProduct = new WC_Product($prodId);
        $_isi = $_recentProduct->is_sold_individually();

        $_SESSION['recentProduct'] = ['id'=>$_recentProduct->id, 'title'=>$_recentProduct->get_title()];

        // If cart is not empty check the conditions and clear the cart if it needs
        if (WC()->cart->get_cart_contents_count() > 0) {

            $cartItems = WC()->cart->cart_contents;

            // if we add ISI product we must check if the cart contains another ISI and delete them
            if ($_isi) {
                foreach ($cartItems as $k => $v) {
                    if (($cartItems[$k]['data']->is_sold_individually())&&($cartItems[$k]['product_id'] != $prodId)) {
                        WC()->cart->set_quantity($k, '0');
                    }
                }
            }
        }
    }
}


add_filter('woocommerce_locate_template', 'ss_woocommerce_locate_template', 10, 3);
function ss_woocommerce_locate_template($template, $template_name, $plugin_path)
{

    $tempaltes_path = untrailingslashit(get_theme_root() . '/' . get_template()) . '/woocommerce/';

    if (file_exists($tempaltes_path . $template_name)) {
        $template = $tempaltes_path . $template_name;

        return $template;
    }

    return $template;
}


add_filter("woocommerce_checkout_fields", "order_fields_for_checkout", 100, 1);
function order_fields_for_checkout($fields)
{
    $billing_ordered_fields = [];
    $shipping_ordered_fields = [];

    $removeFields = ['shipping_email', 'billing_email'];

    $order_shipping = array(
        "shipping_first_name",
        "shipping_last_name",
        "shipping_company",
        "shipping_email",
        "shipping_country",
        "shipping_address_1",
        "shipping_address_2",
        "shipping_city",
        "shipping_state",
        "shipping_postcode",
    );

    $order_billing = array(
        "billing_first_name",
        "billing_last_name",
        "billing_company",
        "billing_email",
        "billing_country",
        "billing_address_1",
        "billing_address_2",
        "billing_city",
        "billing_state",
        "billing_postcode",
    );

    // we don't need the email fields for logged users
    if (is_user_logged_in()) {
        $order_shipping = array_diff($order_shipping, $removeFields);
        $order_billing = array_diff($order_billing, $removeFields);
    }

    foreach ($order_billing as $field) {
        $billing_ordered_fields[$field] = $fields["billing"][$field];
    }

    foreach ($order_shipping as $field) {
        $shipping_ordered_fields[$field] = $fields["shipping"][$field];
    }

    $fields["billing"] = $billing_ordered_fields;
    $fields["shipping"] = $shipping_ordered_fields;

    return $fields;
}

add_filter('woocommerce_form_field_country', 'swap_required_mark_for_field', 150, 4);
add_filter('woocommerce_form_field_select', 'swap_required_mark_for_field', 150, 4);
add_filter('woocommerce_form_field_text', 'swap_required_mark_for_field', 150, 4);
add_filter('woocommerce_form_field_email', 'swap_required_mark_for_field', 150, 4);
add_filter('woocommerce_form_field_postcode', 'swap_required_mark_for_field', 150, 4);
add_filter('woocommerce_form_field_city', 'swap_required_mark_for_field', 150, 4);
add_filter('woocommerce_form_field_state', 'swap_required_mark_for_field', 150, 4);
add_filter('woocommerce_form_field_radio', 'swap_required_mark_for_field', 150, 4);
add_filter('woocommerce_form_field_textarea', 'swap_required_mark_for_field', 150, 4);
function swap_required_mark_for_field($field, $key, $args, $value)
{
    $replaced = 0;
    $fieldWrapper = '<div class="form-control-wrapper">%1$s</div>';
    $requiredMark = '<abbr class="required" title="' . esc_attr__('required', 'storefront') . '">*</abbr>';
    $fieldLabel = $args['label'];
    $outField = $field;

    $required = (isset($args['required']) && $args['required'] == true) || (isset($args['custom_attributes']['required']) && $args['custom_attributes']['required'] == true);

    if ($required) {
        $outField = str_replace($requiredMark, ' ', $outField, $replaced);
        $outField = str_replace('>'.$fieldLabel, '>'.$requiredMark . ' ' . trim($fieldLabel), $outField, $replaced);
    } else {
        $outField = $field;
    }

    $outField = str_replace('<p', '<div', $outField, $founded);


    if ($replaced == 1){
        $outField = str_replace('</p>', '</div>', $outField, $founded);

        switch ($args['type']) {
            case 'text' :
            case 'postcode' :
            case 'email' :
            case 'tel' :
            case 'city' :
            case 'radio' :
                $openTag = '<' . 'input';
                $closeTag = '/>';
                $doWrap = true;
                break;
            case 'select' :
            case 'state' :
                $openTag = '<' . 'select';
                $closeTag = '</select>';
                $openTagReserve = '<' . 'input';
                $closeTagReserve = '/>';
                $doWrap = true;
                break;
            case 'textarea' :
                $openTag = '<' . $args['type'];
                $closeTag = '</' . $args['type'] . '>';
                $doWrap = true;
                break;
            default:
                $doWrap = true;
                $openTag = null;
                $closeTag = null;
                break;
        }

        if ($doWrap && isset($openTag) && isset($closeTag)) {

            $tagStartPosition = strpos($outField, $openTag);

            if ($tagStartPosition == 0) {
                $tagStartPosition = strpos($outField, $openTagReserve);
                if ($tagStartPosition != 0) {
                    $closeTag = $closeTagReserve;
                }
            }

            $tagEndPosition = strpos($outField, $closeTag, $tagStartPosition);
            $tagContent = substr($outField, $tagStartPosition, $tagEndPosition - $tagStartPosition + strlen($closeTag));
            $wrappedTagContent = sprintf($fieldWrapper, $tagContent);

            $outField = str_replace($tagContent, $wrappedTagContent, $outField);

        }

    } else {
        $outField = $field;
    }

    return $outField;
}


remove_action('woocommerce_multistep_checkout_before', 'add_login_to_wizard');
add_action('woocommerce_multistep_checkout_before', 'add_your_account_to_wizard');
function add_your_account_to_wizard()
{
    if (is_user_logged_in() || 'no' === get_option('woocommerce_enable_checkout_login_reminder')) {
        return;
    }
    ?>
    <script>
        jQuery(function () {
            jQuery(".woocommerce-info a.showlogin").parent().detach();
            jQuery("form.login").appendTo('.login-step');
            jQuery(".login-step form.login").show();
        });</script>
    <h1 class="title-login-wizard"><?php _e('Your account', 'storefront') ?></h1>
    <div class="login-step"></div>
    <?php
}

add_action('woocommerce_review_order_coupon_insert', 'woocommerce_checkout_coupon_form');

remove_action('woocommerce_checkout_order_review', 'update_shipping_info');
add_action('woocommerce_checkout_order_review', 'show_addresses_information', 9);
function show_addresses_information()
{
    wc_get_template('checkout/review-addresses.php', array('checkout' => WC()->checkout()));

}


add_action('woocommerce_checkout_order_review_vat_info', 'show_order_review_additional_info');
function show_order_review_additional_info()
{
    if (class_exists('WC_EU_VAT_Number')) { ?>
        <div class="container-fluid vat_info_block">
            <?php
            remove_action('woocommerce_after_checkout_billing_form', ['WC_EU_VAT_Number', 'vat_number_field']);
            add_action('woocommerce_show_eu_vat', ['WC_EU_VAT_Number', 'vat_number_field']);
            do_action('woocommerce_show_eu_vat');
            ?>
        </div>
        <?php
    }
}

add_action('woocommerce_checkout_order_completed', 'show_thank_you_content');
function show_thank_you_content()
{
    //wc_get_template( 'checkout/thankyou.php', array(  ) );
}

add_action('woocommerce_multistep_checkout_after_order_info', 'show_legal_notices');
function show_legal_notices()
{
    wc_get_template('checkout/legalnotices.php', array());
}

add_filter('woocommerce_change_payment_button_html', 'hide_place_order_main_button', 100, 1);
function hide_place_order_main_button($inputHtml)
{
    return str_replace('type="submit"', 'type="submit" style="display:none;"', $inputHtml);
}

add_action('wp_ajax_save_user_prefs', 'save_user_prefs');
function save_user_prefs()
{
    $pattern = '/^(?=.{10,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).*/';
    $opts = array('options' => array('regexp' => $pattern));

    if (empty($_REQUEST) || !isset($_REQUEST)) {
        $response = json_encode(array(
            'error' => __('Something goes wrong. Unable to set your password. Please try again.', 'storefront')
        ));
    } else {
        $user = wp_get_current_user();
        $incomingId = filter_var($_REQUEST['user_id'], FILTER_SANITIZE_NUMBER_INT);
        $incomingPass = filter_var(trim($_REQUEST['password']), FILTER_SANITIZE_STRING);

        if ($incomingId == $user->ID) {
            if (filter_var($_REQUEST['password'], FILTER_VALIDATE_REGEXP, $opts)) {
                update_user_meta($user->ID, 'user_pass', $incomingPass);
                wp_update_user(array('ID' => $user->ID, 'user_pass' => $incomingPass));
                $response = json_encode(array('success' => __('Your password has been set.', 'storefront')));

            } else {
                $response = json_encode(array(
                    'error' => __('The password you entered doesn\'t meet our security requirements. It must be at least 10 characters long, including both lower and upper case letters and a number',
                        'storefront')
                ));
            }
        } else {
            $response = json_encode(array(
                'error' => __('Something goes wrong. Unable to set your password for the current user.', 'storefroint')
            ));
        }
    }

    header("Content-Type: application/json");
    echo $response;
    die();

}

add_action('wp_head', 'main_ajaxurl');
function main_ajaxurl() {

    $CID = get_current_user_id();

    echo '<script type="text/javascript">
            var globalFuel = {
                xhrRequest: false,
                CID:' . $CID . ',
                homeUrl : "' . get_home_url() . '",
                ajaxUrl : "' . admin_url('admin-ajax.php') . '"
            };
         </script>
    ';
}

add_action('wp_ajax_nopriv_set_country_unregister_user', 'set_country_unregister_user');
function set_country_unregister_user()
{
    if (isset($_REQUEST['country'])) {
        WC()->customer->set_shipping_country($_REQUEST['country']);
        echo json_encode(array('state' => 'success'));
        die;
    }
    echo json_encode(array('state' => 'error'));
    die;
}

add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');
function ajax_check_user_logged_in() {
    echo is_user_logged_in()?'yes':'no';
    die();
}

add_action('wp_ajax_is_email_registered', 'ajax_check_email_registered');
add_action('wp_ajax_nopriv_is_email_registered', 'ajax_check_email_registered');
function ajax_check_email_registered() {
    $email = $_REQUEST['email'];
    if (is_email($email)){
        echo json_encode(array('state' => email_exists($email)?'yes':'no', 'error'=>''));
    } else {
        echo json_encode(array('state' => 'error', 'message'=>__('Invalid email. Please check email entered', 'storefront')));
    }
    die();
}

add_action('wp_ajax_is_email_valid', 'ajax_check_email_valid');
add_action('wp_ajax_nopriv_is_email_valid', 'ajax_check_email_valid');
function ajax_check_email_valid() {
    $email = $_REQUEST['email'];
    $incomingCID = $_REQUEST['CID'];
    if (is_email($email)){
        $gotCID = email_exists($email);
        if ($gotCID == false) {
            echo json_encode(array('state' => 'free', 'message'=>''));
        } elseif (($gotCID == $incomingCID)&&($incomingCID != 0)) {
            echo json_encode(array('state' => 'own', 'message'=>''));
        } else {
            echo json_encode(array('state' => 'error', 'message'=>__('This email already has been registered. You need to login first.', 'storefront')));
        }
    } else {
        echo json_encode(array('state' => 'error', 'message'=>__('You have entered an invalid email. Please check email you have entered.', 'storefront')));
    }
    die();
}

add_filter( 'woocommerce_localisation_address_formats', 'custom_address_formats', 20 );
function custom_address_formats( $formats )
{
    foreach ( $formats as $key => $format ) {
        $formats[ $key ] = str_replace("{name}\n", "{name}\n{email}\n{phone}\n", $formats[ $key ]);
        $formats[ $key ] = str_replace("{state}\n", "{state}\n{county}\n", $formats[ $key ]);
        $formats[ $key ] = str_replace("\n{postcode} {city}", "\n{postcode} {city}\n{state}\n{county}", $formats[ $key ]);
    }
    return $formats;
}


add_filter ('woocommerce_formatted_address_replacements', 'add_extrareplacements_in_address',100, 2 );
function add_extrareplacements_in_address ($replacements, $arg)
{

    $replacements['{email}'] = $arg['email'];
    $replacements['{phone}'] = $arg['phone'];
    $replacements['{county}'] = $arg['county'];
    $replacements['{state}'] = $arg['state'];

    return $replacements;
}

add_filter( 'woocommerce_order_formatted_billing_address', 'add_extra_formatted_billing_address', 100, 2 );
function add_extra_formatted_billing_address( $address, $order )
{

    $address['email'] = $order->billing_email;
    $address['phone'] = $order->billing_phone;
    $address['county'] = $order->billing_county;
    $address['state'] = $order->billing_state;

    return $address;
}

add_filter( 'woocommerce_order_formatted_shipping_address', 'add_extra_formatted_shipping_address', 100, 2 );
function add_extra_formatted_shipping_address( $address, $order )
{

    $address['email'] = $order->shipping_email;
    $address['phone'] = $order->shipping_phone;
    $address['county'] = $order->shipping_county;
    $address['state'] = $order->shipping_state;

    return $address;
}

add_filter( 'woocommerce_shipping_chosen_method', 'select_free_default', 100, 2 );
function select_free_default ($chosen_method, $available_methods)
{

    if (empty($available_methods)) {
        return $chosen_method;
    }

    $free_key = 'free_shipping';
    if (array_key_exists($free_key, $available_methods)) {
        $chosen_method = $free_key;
    }

    return $chosen_method;
}

add_filter('woocommerce_cart_needs_shipping', 'shipping_country_detect', 100, 1);
function shipping_country_detect($needs_shipping)
{

    $user = wp_get_current_user();
    $custom_shipping = false;

    if ($user->exists()) {
        $shippingCountry = get_user_meta($user->ID, 'shipping_country');
        WC()->customer->set_shipping_country(!empty($shippingCountry[0])?$shippingCountry[0]:WC()->customer->get_default_country());

        if (WC()->session && $user->has_cap('distributor')) {
            $custom_shipping = WC()->session->get('custom_shipping');
        }
    }

    return $custom_shipping?false:$needs_shipping;
}

add_filter('woocommerce_cart_needs_shipping_address', 'shipping_address_detect', 100, 1);
function shipping_address_detect ($needs_shipping_address)
{
    $cart = WC()->cart;

    if ( $cart->cart_contents ) {
        foreach ( $cart->cart_contents as $cart_item_key => $values ) {
            $_product = $values['data'];
            if ( $_product->needs_shipping() ) {
                $needs_shipping_address = true;
            }
        }
    }

    return $needs_shipping_address;
}

add_filter ('woocommerce_ship_to_different_address_checked', 'check_user_addresses');
function check_user_addresses($ship_to_different_address)
{
    $CID = get_current_user_id();

    if ($CID > 0){

        $first_name = get_user_meta( $CID, 'billing_first_name', true );
        $last_name = get_user_meta( $CID, 'billing_last_name', true );
        $company = get_user_meta( $CID, 'billing_company', true );
        $address_1 = get_user_meta( $CID, 'billing_address_1', true );
        $address_2 = get_user_meta( $CID, 'billing_address_2', true );
        $country = get_user_meta( $CID, 'billing_country', true );
        $city = get_user_meta( $CID, 'billing_city', true );
        $county = get_user_meta( $CID, 'billing_county', true );
        $postcode = get_user_meta( $CID, 'billing_postcode', true );

        $s_first_name = get_user_meta( $CID, 'shipping_first_name', true );
        $s_last_name = get_user_meta( $CID, 'shipping_last_name', true );
        $s_company = get_user_meta( $CID, 'shipping_company', true );
        $s_address_1 = get_user_meta( $CID, 'shipping_address_1', true );
        $s_address_2 = get_user_meta( $CID, 'shipping_address_2', true );
        $s_country = get_user_meta( $CID, 'shipping_country', true );
        $s_city = get_user_meta( $CID, 'shipping_city', true );
        $s_county = get_user_meta( $CID, 'shipping_county', true );
        $s_postcode = get_user_meta( $CID, 'shipping_postcode', true );


        $addresses_the_same = ((strcasecmp($address_1, $s_address_1) == 0) && (strcasecmp($address_2, $s_address_2) == 0) &&
                               (strcasecmp($country, $s_country) == 0) &&
                               (strcasecmp($city, $s_city) == 0) && (strcasecmp($county, $s_county) == 0) &&
                               (strcasecmp($postcode, $s_postcode) == 0) &&
                               (strcasecmp($first_name, $s_first_name) == 0) && (strcasecmp($last_name, $s_last_name) == 0) &&
                               (strcasecmp($company, $s_company) == 0));

        $ship_to_different_address = $addresses_the_same == false ? 1 : 0;

    } else {

        $ship_to_different_address = 0;

    }

    return $ship_to_different_address;

}

add_filter( 'woocommerce_package_rates', 'hide_disty_free_shipping', 100, 1 );
function hide_disty_free_shipping ($available_methods)
{

    $free_key = 'free_shipping';

    if (empty($available_methods)) {
        return;
    }

    $user = wp_get_current_user();

    if ( $user->exists() && $user->has_cap('distributor') && array_key_exists($free_key, $available_methods) ) {
        unset($available_methods[$free_key]);
    }

    return $available_methods;
}

add_filter( 'woocommerce_cart_contents_total', 'hide_recurring_text', 100, 1 );
function hide_recurring_text($cart_contents_total)
{
    return $cart_contents_total;

    $nBegin = strpos($cart_contents_total, __('now then', 'storefront'));
    if ($nBegin !== false){
        $cart_contents_total = substr($cart_contents_total, 0, $nBegin).'&nbsp;';
    }

    return $cart_contents_total;
}

add_filter ('woocommerce_get_tax_location',  'set_tax_location', 100, 2);
function set_tax_location($location, $tax_class)
{

    if ( is_array($location) && !empty($location) ) { //&& !empty( WC()->customer )
        $country = $location[0];
        $location[0] = set_customer_location($country);
    }

    return $location;
}

add_filter ('woocommerce_customer_default_location',  'set_customer_location', 100, 1);
//add_action ('woocommerce_before_cart', 'set_customer_location');
function set_customer_location($default) {

    $location = $default;

    if ( is_plugin_active('woocommerce-taxamo\woocommerce-taxamo.php') && class_exists('WC_TA_Checkout_Vat') ) {
        $checkout_vat = new WC_TA_Checkout_Vat();
        $checkout_vat->setup();

        $location = $checkout_vat->set_default_customer_location($default); 
    }
    
    if (empty ($location) && isset(WC()->customer)) {
        $location = WC()->customer->get_shipping_country();
    }

    return $location;

}


add_filter ('woocommerce_get_order_currency', 'get_selected_currency', 100, 1);
function get_selected_currency ($currency = null) {

    $selected = isset(WC()->session)?WC()->session->get('aelia_cs_selected_currency'):null;

    if (get_current_blog_id() == 4) {
        $selected = isset($selected)?$selected:'USD';
    } else {
        $selected = isset($selected)?$selected:'GBP';
    }

    $sel_currency = isset($currency)?$currency:$selected;

    if (isset(WC()->session)){
        WC()->session->set('aelia_cs_selected_currency', $sel_currency);
    }

    return $sel_currency;
}

function getPageBySlug($slug)
{
    $post = get_posts(
        array(
            'name'      => $slug,
            'post_type' => 'page'
        ));

    if (isset($post[0])) {
        $content = apply_filters('the_content', $post[0]->post_content);
        return $content;
    }

}

add_filter ('wp_nav_menu', 'add_shop_menu_item', 100, 2);
function add_shop_menu_item ($nav_menu, $args)
{

    //@todo SS Add shop menu item here instead of template
    return $nav_menu;
}

add_filter ('woocommerce_get_checkout_url', 'add_wizard_hash', 100, 1);
function add_wizard_hash($url)
{
    return $url . '#wizard-h-0';
}

add_filter ('woocommerce_form_field_args', 'ss_handle_field_args', 100 ,3);
function ss_handle_field_args ($args, $key, $value) {
    if (strpos($key, 'country' ) !== false) {
        $args['input_class'] = array('country_select', 'country_to_state');
    }
    if (strpos($key, 'state' ) !== false) {
        $args['input_class'] = array('state_select');
    }

    return $args;
}

add_action('wp_ajax_get_states', 'ajax_get_states');
add_action('wp_ajax_nopriv_get_states', 'ajax_get_states');
function ajax_get_states() {

    $country_key = $_REQUEST['country'];
    $current_cc  = WC()->checkout->get_value( $country_key );
    $states      = WC()->countries->get_states( $current_cc );

    echo json_encode(array('states' => $states));

    die();

}


add_action('init','model_blog', 1001);

if (!function_exists('model_blog')) {
    function model_blog(){
        $folder = get_template_directory_uri();
        $folderCSS = $folder . '/css';

        include_once( 'MVC/Models/Blog.php' );
        wp_register_style('blog', $folderCSS . '/blog.css');
        wp_enqueue_style('blog');
    }
}

add_action( 'post_submitbox_misc_actions', 'featured_post' );
function featured_post()
{
    global $post;

    /* check if this is a post, if not then we won't add the custom field */
    /* change this post type to any type you want to add the custom field to */
    if (get_post_type($post) != 'post') return false;

    /* get the value corrent value of the custom field */
    $value = get_post_meta($post->ID, 'featured_post', true);
    ?>
    <div class="misc-pub-section">
        <?php //if there is a value (1), check the checkbox ?>
        <label><input type="checkbox"<?php echo (!empty($value) ? ' checked="checked"' : null) ?> value="1" name="featured_post" /> Featured blog post</label>
    </div>
    <?php
}

add_action( 'save_post', 'mark_post_featured');
function mark_post_featured($postid)
{
    /* check if this is an autosave */
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;

    /* check if the user can edit this page */
    if ( !current_user_can( 'edit_page', $postid ) ) return false;

    /* check if there's a post id and check if this is a post */
    /* make sure this is the same post type as above */
    if(empty($postid) || $_POST['post_type'] != 'post' ) return false;

    /* if you are going to use text fields, then you should change the part below */
    /* use add_post_meta, update_post_meta and delete_post_meta, to control the stored value */

    /* check if the custom field is submitted (checkboxes that aren't marked, aren't submitted) */
    if(isset($_POST['featured_post'])){
        /* store the value in the database */
        $allposts = get_posts( 'numberposts=-1&post_type=post&post_status=any' );

        foreach( $allposts as $postinfo ) {
            delete_post_meta( $postinfo->ID, 'featured_post', 1 );
        }
        add_post_meta($postid, 'featured_post', 1, true );
    }
    else{
        /* not marked? delete the value in the database */
        delete_post_meta($postid, 'featured_post');
    }
}

add_action( 'admin_footer-post-new.php', 'wpse_98269_script' );
add_action( 'admin_footer-post.php', 'wpse_98269_script' );

function wpse_98269_script()
{
    if ( ! isset ( $GLOBALS['post'] ) )
        return;

    $post_type = get_post_type( $GLOBALS['post'] );

    if ( ! post_type_supports( $post_type, 'custom-fields' ) )
        return;
    ?>
    <script>
        if ( jQuery( "[value='blog_categories']" ).length < 1 ) // avoid duplication
            jQuery( "#metakeyselect").append( "<option value='blog_categories'>blog_categories</option>" );
    </script>
    <?php
}

add_action( 'admin_footer-post-new.php', 'wpse_982699_script' );
add_action( 'admin_footer-post.php', 'wpse_982699_script' );

function wpse_982699_script()
{
    if ( ! isset ( $GLOBALS['post'] ) )
        return;

    $post_type = get_post_type( $GLOBALS['post'] );

    if ( ! post_type_supports( $post_type, 'custom-fields' ) )
        return;
    ?>
    <script>
        if ( jQuery( "[value='blog_url']" ).length < 1 ) // avoid duplication
            jQuery( "#metakeyselect").append( "<option value='blog_url'>blog_url</option>" );
    </script>
    <?php
}

function get_custom_field($field_name){
    return get_post_meta(get_the_ID(),$field_name,'true');
}

function get_template_part_with_data($slug, array $data = array()){
    $slug .= '.php';
    
    extract($data);

    require locate_template($slug);
}

/**
 * Display the post content with a link to the single post
 * Also it add Author description below the post content
 *
 */
function storefront_post_content() {
    ?>

    <div id="articleBody" itemprop="articleBody">
        <div class="row">
            <div
                class="col-xs-4 pull-left date-author"><?php echo _e('by', 'storefront') ?> <?php the_author() ?> <?php echo the_time('M d, Y'); ?></div>
            <?php get_template_part_with_data('page-templates/partials/_social_sharing_top_post'); ?>
        </div>
        <div class="row">
            <div class="col-xs-12 wp-post-image-single">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('full', array('itemprop' => 'image'));
                }
                ?>
            </div>
        </div>
        <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'storefront')); ?>
        <?php
        wp_link_pages(array(
            'before' => '<div class="page-links">' . __('Pages:', 'storefront'),
            'after' => '</div>',
        ));
        ?>
    </div><!-- .entry-content -->

    <div class="clearfix"></div>

    <div class="blog-post-line"></div>
    <?php get_template_part_with_data('page-templates/partials/_newsletter_sign_up_bottom_post'); ?>
    <div class="blog-post-line"></div>
    <div class="row author-description">
        <div class="col-xs-5 col-sm-3" style="min-width: 128px;">
            <div class="avatar">
                <?php echo get_avatar( get_the_author_meta('user_email'), 128, __('No photo provided', 'storefront'), get_the_author_meta('nickname') ); ?>
            </div>
        </div>
        <div class="col-xs-7 col-sm-8">
            <div class="biography vertical-center">
                <?php the_author_meta( 'user_description' ); ?>
            </div>
        </div>
    </div><!-- .author-description -->

    <div class="blog-post-line"></div>
    <?php get_template_part_with_data('page-templates/partials/_social_sharing'); ?>
    <div class="blog-post-line"></div>

    <div class="row post-comment">
        <div class="col-xs-12">
            <?php echo do_shortcode('[wpdevart_facebook_comment order_type="social" title_text="" title_text_color="#000000" title_text_font_size="22" title_text_font_famely="Tahoma" title_text_position="left" width="100%" bg_color="#CCCCCC" animation_effect="random" count_of_comments="2" ]'); ?>
        </div>
    </div>
    <?php echo '<script>var ajaxurl = \'' . admin_url('admin-ajax.php') . '\';</script>'?>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/subscribe-form.js"></script>
    <script>
        jQuery(function ($) {
            $('.newsletter-subscribe-right').on('click', function () {
                newsletterSubscribe(this, $);
                return false;
            });
            $('.newsletter-subscribe-bottom').on('click', function () {
                newsletterSubscribe(this, $);
                return false;
            });
        });
    </script>

    <?php
}

add_action('wp_ajax_get_older_posts', 'get_older_posts');
add_action('wp_ajax_nopriv_get_older_posts', 'get_older_posts');
function get_older_posts()
{
    $getPostsCount = 4;
    $last = true;
    $page =  isset($_POST['page'])? (int)$_POST['page'] : 0;

    $posts = '<!-- older posts -->';
    $success = $page > 0;
    $status = __('Unknown page requested', 'strorefront');

    if ($success){

        $offset = 6 + ($page - 1)*$getPostsCount;

        $blog = new \MVC\Models\Blog();
        $blogPosts = $blog->getPosts($getPostsCount, $offset);
        $success = $blogPosts->have_posts();

        if ($success) {
            global $post;
            $column = 1;
            while ( have_posts() ) {

                the_post();
                if (($column % 2) == 1){
                    $posts .= '<div class="row">';
                }

                ob_start();
                get_template_part_with_data( 'page-templates/partials/_content-blog', [ 'categories' => $blog->getFormattedPostCategories(), 'ID'=> $post->ID ] );
                $posts .= ob_get_contents();
                ob_end_clean();

                if (($column % 2) == 0){
                    $posts .= '</div>';
                }
                $column++;
            } // end of the loop.
            $status = '';
        } else {
            $status = __('No posts found','storefront');
            $posts .= '<div class="row"><span>'.$status.'</span></div>';
        }

        $last = !$blog->getPosts(1, $offset + $getPostsCount )->have_posts();
        if ($last) {
            $status .= '&nbsp;'.__('Last page reached','storefront');
        }

    }

    echo json_encode(['posts'=>$posts, 'success'=>$success, 'status'=>$status, 'last'=>$last]);

    die;
}

add_filter('woocommerce_countries', 'ss_handle_countries_for_profile_correct', 100, 1);
add_filter('woocommerce_countries_allowed_countries', 'ss_handle_countries_for_profile_correct', 100, 1);
add_filter('woocommerce_countries_shipping_countries', 'ss_handle_countries_for_profile_correct', 100, 1);
function ss_handle_countries_for_profile_correct( $countries ) {

    $stateCountriesBlackList = ['US','CA','MX'];

    if ((is_cart() || is_checkout()) && !isUsaShop() ) {

        $countries = array_diff_key( $countries, array_flip($stateCountriesBlackList) );

        $countries['US'] = '';
        $countries['CA'] = '';
        $countries['MX'] = '';

    }

    return $countries;

}

if (!function_exists('getDateLastSoftwareDownloadByEmail')) {
    function getDateLastSoftwareDownloadByEmail($email){
        /**
         * @var \wpdb $wpdb
         */
        global $wpdb;

        $result = $wpdb->get_results("
        SELECT
          tm.umeta_id as meta_id,
          users.ID as user_id,
          user_email as email,
          user_login as login,
          tm.meta_value as `time`,
          fname.meta_value as fname,
          lname.meta_value as lname
        FROM {$wpdb->prefix}users as users
        RIGHT JOIN {$wpdb->prefix}usermeta as tm
        ON tm.user_id = users.ID
        LEFT JOIN {$wpdb->prefix}usermeta as fname
        ON fname.user_id = users.ID
        LEFT JOIN {$wpdb->prefix}usermeta as lname
        ON lname.user_id = users.ID
        WHERE tm.meta_key = '_download_time'
          AND fname.meta_key = 'first_name'
          AND lname.meta_key = 'last_name'
          AND user_email = '$email'
        ORDER BY tm.meta_value DESC
        LIMIT 1;
        ");

        if (isset($result[0]->time)) {
            return date('Y-m-d H:i:s', $result[0]->time);
        }
    }
}
/**
 *  Make the new action for the new Fuel 3D (storefront) emails header  
 */
add_action( 'fuel3d_email_header', 'fuel3d_email_header' );
function fuel3d_email_header ($email_heading) {
    wc_get_template( 'emails/email-header-f3d.php', array( 'email_heading' => $email_heading ) );
}

/**
 *  Make the new action for the new Fuel 3D (storefront) emails footer
 */
add_action( 'fuel3d_email_footer', 'fuel3d_email_footer' );
function fuel3d_email_footer ($email_heading) {
    wc_get_template( 'emails/email-footer-f3d.php', array( 'email_heading' => $email_heading ) );
}

/**
 * Calculate the check digit of the serial nu,ber provided
 * @param $sSerial The serial number without last check digit
 *
 * @return int The check digit or -1 if an input error
 */

function calculate_check_digit ($sSerial)
{
    if( !is_numeric($sSerial) )
        return -1;

    $aSerial = str_split($sSerial);
    $count = count($aSerial)-1;
    $sum = 0;
    $i = 0;
    while ($i <= $count) {
        $sum = $sum + ((($i % 2) == 1)? (int)$aSerial[$i] * 3 : (int)$aSerial[$i] * 1);
        $i++;
    }

    $i = 0;
    while ($i < $sum) {
        $i = $i + 10;
    }

    return $i - $sum;
}

add_filter('woocommerce_get_subscriptions_query', 'woocommerce_get_subscriptions_query_fuel', 10, 2);

function woocommerce_get_subscriptions_query_fuel($query, $args)
{
    global $wpdb;

    $args = wp_parse_args( $args, array(
            'subscriptions_per_page' => 10,
            'paged'                  => 1,
            'offset'                 => 0,
            'orderby'                => '_subscription_start_date',
            'order'                  => 'DESC',
            'customer_id'            => '',
            'product_id'             => '',
            'variation_id'           => '',
            'order_id'               => array(),
            'subscription_status'    => 'any',
        )
    );

    // Map human friendly order_by values to internal keys
    switch ( $args['orderby'] ) {
        case 'status' :
            $args['orderby'] = '_subscription_status';
            break;
        case 'start_date' :
            $args['orderby'] = '_subscription_start_date';
            break;
        case 'expiry_date' :
            $args['orderby'] = '_subscription_expiry_date';
            break;
        case 'trial_expiry_date' :
            $args['orderby'] = '_subscription_trial_expiry_date';
            break;
        case 'end_date' :
            $args['orderby'] = '_subscription_end_date';
            break;
    }

    $subscriptions = array();

    // First see if we're paging, so the limit can be applied to subqueries
    if ( -1 !== $args['subscriptions_per_page'] ) {

        $per_page   = absint( $args['subscriptions_per_page'] );
        $page_start = '';

        if ( 0 == $args['paged'] ) {
            $args['paged'] = 1;
        }

        if ( $args['paged'] ) {
            $page_start = absint( $args['paged'] - 1 ) * $per_page . ', ';
        }

        if ( $args['offset'] ) {
            $page_start = absint( $args['offset'] ) . ', ';
        }

        $limit_query = '
				LIMIT ' . $page_start . $per_page;

    } else {

        $limit_query = '';

    }

    if ( 'DESC' === $args['order'] ) {
        $order_query = ' DESC';
    } else {
        $order_query = ' ASC';
    }

    // Now start building the actual query
    $query = "
			SELECT meta.*, items.*, r.renewal_order_count, (CASE WHEN (r.renewal_order_count > 0) THEN l.last_payment_date ELSE o.order_date END) AS last_payment_date FROM `{$wpdb->prefix}woocommerce_order_itemmeta` AS meta
			LEFT JOIN `{$wpdb->prefix}woocommerce_order_items` AS items USING (order_item_id)";

    $query .= "
			LEFT JOIN (
				SELECT a.order_item_id FROM `{$wpdb->prefix}woocommerce_order_itemmeta` AS a";

    // To filter order items by a specific product ID, we need to do another join
    if ( ! empty( $args['product_id'] ) || ! empty( $args['variation_id'] ) ) {

        // Can only specify a product ID or a variation ID, no need to specify a product ID if you want a variation of that product
        $meta_key = ! empty( $args['variation_id'] ) ? '_variation_id' : '_product_id';

        $query .= sprintf( "
				LEFT JOIN (
					SELECT `{$wpdb->prefix}woocommerce_order_itemmeta`.order_item_id FROM `{$wpdb->prefix}woocommerce_order_itemmeta`
					WHERE `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_key = '%s'
					AND `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_value = %s
				) AS p
				USING (order_item_id)",
            $meta_key,
            $args['product_id']
        );

    }

    // To filter order items by a specific subscription status, we need to do another join (unless we're also ordering by subscription status)
    if ( ! empty( $args['subscription_status'] ) && 'any' !== $args['subscription_status'] && '_subscription_status' !== $args['orderby'] ) {

        if ( 'all' == $args['subscription_status'] ) { // get all but trashed subscriptions

            $query .= "
				LEFT JOIN (
					SELECT `{$wpdb->prefix}woocommerce_order_itemmeta`.order_item_id FROM `{$wpdb->prefix}woocommerce_order_itemmeta`
					WHERE `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_key = '_subscription_status'
					AND `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_value != 'trash' AND `{$wpdb->prefix}woocommerce_order_itemmeta`.`order_item_id` NOT IN (SELECT `{$wpdb->prefix}woocommerce_order_itemmeta`.order_item_id FROM `{$wpdb->prefix}woocommerce_order_itemmeta`WHERE `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_key = '_subscription'
					AND `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_value = 'perpetual')
				) AS s
				USING (order_item_id)";

        } else {

            $query .= sprintf( "
				LEFT JOIN (
					SELECT `{$wpdb->prefix}woocommerce_order_itemmeta`.order_item_id FROM `{$wpdb->prefix}woocommerce_order_itemmeta`
					WHERE `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_key = '_subscription_status'
					AND `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_value = '%s'
				) AS s
				USING (order_item_id)",
                $args['subscription_status']
            );

        }

    }

    // We need an additional join when ordering by certain attributes
    switch ( $args['orderby'] ) {
        case '_product_id': // Because all products have a product ID, but not all products are subscriptions
            if ( empty( $args['product_id'] ) ) {
                $query .= "
				LEFT JOIN (
					SELECT `{$wpdb->prefix}woocommerce_order_itemmeta`.order_item_id FROM `{$wpdb->prefix}woocommerce_order_itemmeta`
					WHERE `{$wpdb->prefix}woocommerce_order_itemmeta`.meta_key LIKE '_subscription_%s'
					GROUP BY `{$wpdb->prefix}woocommerce_order_itemmeta`.order_item_id
				) AS a2 USING (order_item_id)";
            }
            break;
        case '_order_item_name': // Because the order item name is found in the order_items tables
        case 'name':
            if ( empty( $args['product_id'] ) ) {
                $query .= "
				LEFT JOIN (
					SELECT `{$wpdb->prefix}woocommerce_order_items`.order_item_id, `{$wpdb->prefix}woocommerce_order_items`.order_item_name FROM `{$wpdb->prefix}woocommerce_order_items`
					WHERE `{$wpdb->prefix}woocommerce_order_items`.order_item_type = 'line_item'
				) AS names USING (order_item_id)";
            }
            break;
        case 'order_id': // Because the order ID is found in the order_items tables
            $query .= "
				LEFT JOIN (
					SELECT `{$wpdb->prefix}woocommerce_order_items`.order_item_id, `{$wpdb->prefix}woocommerce_order_items`.order_id FROM `{$wpdb->prefix}woocommerce_order_items`
					WHERE `{$wpdb->prefix}woocommerce_order_items`.order_item_type = 'line_item'
				) AS order_ids USING (order_item_id)";
            break;
        case 'renewal_order_count':
            $query .= "
				LEFT JOIN (
					SELECT items2.order_item_id, items2.order_id, r2.renewal_order_count FROM `{$wpdb->prefix}woocommerce_order_items` AS items2
					LEFT JOIN (
						SELECT posts.post_parent, COUNT(posts.ID) as renewal_order_count FROM `{$wpdb->prefix}posts` AS posts
						WHERE posts.post_parent != 0
						AND posts.post_type = 'shop_order'
						GROUP BY posts.post_parent
					) AS r2 ON r2.post_parent = items2.order_id
					WHERE items2.order_item_type = 'line_item'
				) AS renewals USING (order_item_id)";
            break;
        case 'user_display_name':
        case 'user':
            if ( empty( $args['customer_id'] ) ) {
                $query .= "
				LEFT JOIN (
					SELECT items2.order_item_id, items2.order_id, users.display_name FROM `{$wpdb->prefix}woocommerce_order_items` AS items2
					LEFT JOIN (
						SELECT postmeta.post_id, postmeta.meta_value, u.display_name FROM `{$wpdb->prefix}postmeta` AS postmeta
						LEFT JOIN (
							SELECT `{$wpdb->prefix}users`.ID, `{$wpdb->prefix}users`.display_name FROM `{$wpdb->prefix}users`
						) AS u ON u.ID = postmeta.meta_value
						WHERE postmeta.meta_key = '_customer_user'
					) AS users ON users.post_id = items2.order_id
					WHERE items2.order_item_type = 'line_item'
				) AS users_items USING (order_item_id)";
            }
        case 'last_payment_date': // Because we need the date of the last renewal order (or maybe the original order if there are no renewal orders)
            $query .= "
				LEFT JOIN (
					SELECT items2.order_item_id, (CASE WHEN (r2.renewal_order_count > 0) THEN l.last_payment_date ELSE o.order_date END) AS last_payment_date FROM `{$wpdb->prefix}woocommerce_order_items` AS items2
					LEFT JOIN (
						SELECT posts.post_parent, COUNT(posts.ID) as renewal_order_count FROM `{$wpdb->prefix}posts` AS posts
						WHERE posts.post_parent != 0
						AND posts.post_type = 'shop_order'
						GROUP BY posts.post_parent
					) AS r2 ON r2.post_parent = items2.order_id
					LEFT JOIN (
						SELECT o.ID, o.post_date_gmt AS order_date FROM `{$wpdb->prefix}posts` AS o
						WHERE o.post_type = 'shop_order'
						AND o.post_parent = 0
					) AS o ON o.ID = items2.order_id
					LEFT JOIN (
						SELECT p.ID, p.post_parent, MAX(p.post_date_gmt) AS last_payment_date FROM `{$wpdb->prefix}posts` AS p
						WHERE p.post_type = 'shop_order'
						AND p.post_parent != 0
						GROUP BY p.post_parent
					) AS l ON l.post_parent = items2.order_id
					WHERE items2.order_item_type = 'line_item'
				) AS payment_dates USING (order_item_id)";
            break;
    }

    // Start where query
    $query .= "
				WHERE 1=1";

    // We only want subscriptions from within the product filter subclause
    if ( ! empty( $args['product_id'] ) || ! empty( $args['variation_id'] ) ) {
        $query .= "
				AND a.order_item_id = p.order_item_id";
    }

    // We only want subscriptions from within the status filter subclause
    if ( ! empty( $args['subscription_status'] ) && 'any' !== $args['subscription_status'] && '_subscription_status' !== $args['orderby'] ) {
        $query .= "
				AND a.order_item_id = s.order_item_id";
    }

    // We only want items from a certain order
    if ( ! empty( $args['order_id'] ) ) {

        $order_ids = is_array( $args['order_id'] ) ? implode( ',', $args['order_id'] ) : $args['order_id'];

        $query .= sprintf( "
				AND a.order_item_id IN (
					SELECT o.order_item_id FROM `{$wpdb->prefix}woocommerce_order_items` AS o
					WHERE o.order_id IN (%s)
				)", $order_ids );
    }

    // If we only want subscriptions for a certain customer ID, we need to make sure items are from the customer's orders
    if ( ! empty( $args['customer_id'] ) ) {
        $query .= sprintf( "
				AND a.order_item_id IN (
					SELECT `{$wpdb->prefix}woocommerce_order_items`.order_item_id FROM `{$wpdb->prefix}woocommerce_order_items`
					WHERE `{$wpdb->prefix}woocommerce_order_items`.order_id IN (
						SELECT `{$wpdb->prefix}postmeta`.post_id FROM `{$wpdb->prefix}postmeta`
						WHERE `{$wpdb->prefix}postmeta`.meta_key = '_customer_user'
						AND `{$wpdb->prefix}postmeta`.meta_value = %s
					)
				)", $args['customer_id'] );
    }

    // Now we need to sort the subscriptions, which may mean selecting a specific bit of meta data
    switch ( $args['orderby'] ) {
        case '_subscription_start_date':
        case '_subscription_expiry_date':
        case '_subscription_trial_expiry_date':
        case '_subscription_end_date':
            $query .= sprintf( "
				AND a.meta_key = '%s'
				ORDER BY CASE WHEN CAST(a.meta_value AS DATETIME) IS NULL THEN 1 ELSE 0 END, CAST(a.meta_value AS DATETIME) %s", $args['orderby'], $order_query );
            break;
        case '_subscription_status':
            $query .= "
				AND a.meta_key = '_subscription_status'
				ORDER BY a.meta_value" . $order_query;
            break;
        case '_product_id':
            if ( empty( $args['product_id'] ) ) {
                $query .= "
				AND a2.order_item_id = a.order_item_id
				AND a.meta_key = '_product_id'
				ORDER BY a.meta_value" . $order_query;
            }
            break;
        case '_order_item_name':
        case 'name':
            if ( empty( $args['product_id'] ) ) {
                $query .= "
				AND a.meta_key = '_subscription_start_date'
				AND names.order_item_id = a.order_item_id
				ORDER BY names.order_item_name" . $order_query  . ", CASE WHEN CAST(a.meta_value AS DATETIME) IS NULL THEN 1 ELSE 0 END, CAST(a.meta_value AS DATETIME) DESC";
            }
            break;
        case 'order_id':
            $query .= "
				AND a.meta_key = '_subscription_start_date'
				AND order_ids.order_item_id = a.order_item_id
				ORDER BY order_ids.order_id" . $order_query;
            break;
        case 'renewal_order_count':
            $query .= "
				AND a.meta_key = '_subscription_start_date'
				AND renewals.order_item_id = a.order_item_id
				ORDER BY renewals.renewal_order_count" . $order_query;
            break;
        case 'user_display_name':
        case 'user':
            if ( empty( $args['customer_id'] ) ) {
                $query .= "
				AND a.meta_key = '_subscription_start_date'
				AND users_items.order_item_id = a.order_item_id
				ORDER BY users_items.display_name" . $order_query  . ", CASE WHEN CAST(a.meta_value AS DATETIME) IS NULL THEN 1 ELSE 0 END, CAST(a.meta_value AS DATETIME) DESC";
            }
            break;
        case 'last_payment_date':
            $query .= "
				AND a.meta_key = '_subscription_start_date'
				AND payment_dates.order_item_id = a.order_item_id
				ORDER BY payment_dates.last_payment_date" . $order_query;
            break;
    }

    // Paging
    if ( -1 !== $args['subscriptions_per_page'] ) {
        $query .= $limit_query;
    }

    $query .= "
			) AS a3 USING (order_item_id)";

    // Add renewal order count & last payment date (there is duplication here when ordering by renewal order count or last payment date, but it's an arbitrary performance hit)
    $query .= "
			LEFT JOIN (
				SELECT `{$wpdb->prefix}posts`.post_parent, COUNT(`{$wpdb->prefix}posts`.ID) as renewal_order_count FROM `{$wpdb->prefix}posts`
				WHERE `{$wpdb->prefix}posts`.post_parent != 0
				AND `{$wpdb->prefix}posts`.post_type = 'shop_order'
				GROUP BY `{$wpdb->prefix}posts`.post_parent
			) AS r ON r.post_parent = items.order_id
			LEFT JOIN (
				SELECT o.ID, o.post_date_gmt AS order_date FROM `{$wpdb->prefix}posts` AS o
				WHERE o.post_type = 'shop_order'
				AND o.post_parent = 0
			) AS o ON o.ID = items.order_id
			LEFT JOIN (
				SELECT p.ID, p.post_parent, MAX(p.post_date_gmt) AS last_payment_date FROM `{$wpdb->prefix}posts` AS p
				WHERE p.post_type = 'shop_order'
				AND p.post_parent != 0
				GROUP BY p.post_parent
			) AS l ON l.post_parent = items.order_id";

    $query .= "
			WHERE meta.meta_key REGEXP '_subscription_(.*)|_product_id|_variation_id|_license_perpetual'
			AND meta.order_item_id = a3.order_item_id";
    return $query;
}

add_filter('woocommerce_subscription_price_string', 'woocommerce_subscription_price_string_fuel', 10, 2);

function woocommerce_subscription_price_string_fuel($subscription_string, $subscription_details)
{
    if (class_exists('WC_TradeGecko_Init')) {

        global $wp_locale;

        $subscription_details = wp_parse_args($subscription_details, array(
                'currency' => '',
                'initial_amount' => '',
                'initial_description' => __('up front', 'woocommerce-subscriptions'),
                'recurring_amount' => '',

                // Schedule details
                'subscription_interval' => 1,
                'subscription_period' => '',
                'subscription_length' => 0,
                'trial_length' => 0,
                'trial_period' => '',

                // Syncing details
                'is_synced' => false,
                'synchronised_payment_day' => 0,
            )
        );

        $subscription_details['subscription_period'] = strtolower($subscription_details['subscription_period']);

        // Make sure prices have been through woocommerce_price()
        $initial_amount_string = (is_numeric($subscription_details['initial_amount'])) ? woocommerce_price($subscription_details['initial_amount'],
            array('currency' => $subscription_details['currency'])) : $subscription_details['initial_amount'];
        $recurring_amount_string = (is_numeric($subscription_details['recurring_amount'])) ? woocommerce_price($subscription_details['recurring_amount'],
            array('currency' => $subscription_details['currency'])) : $subscription_details['recurring_amount'];

        $subscription_period_string = get_subscription_period_strings_fuel3d($subscription_details['subscription_interval'],
            $subscription_details['subscription_period']);
        $subscription_ranges = WC_Subscriptions_Manager::get_subscription_ranges();

        if ($subscription_details['subscription_length'] > 0 && $subscription_details['subscription_length'] == $subscription_details['subscription_interval']) {
            if (!empty($subscription_details['initial_amount'])) {
                if ($subscription_details['subscription_length'] == $subscription_details['subscription_interval'] && $subscription_details['trial_length'] == 0) {
                    $subscription_string = $initial_amount_string;
                } else {
                    $subscription_string = sprintf(__('%s %s then %s', 'woocommerce-subscriptions'),
                        $initial_amount_string, $subscription_details['initial_description'], $recurring_amount_string);
                }
            } else {
                $subscription_string = $recurring_amount_string;
            }
        } elseif (true === $subscription_details['is_synced'] && in_array($subscription_details['subscription_period'],
                array('week', 'month', 'year'))
        ) {
            // Verbosity is important here to enable translation
            $payment_day = $subscription_details['synchronised_payment_day'];
            switch ($subscription_details['subscription_period']) {
                case 'week':
                    $payment_day_of_week = WC_Subscriptions_Synchroniser::get_weekday($payment_day);
                    if (1 == $subscription_details['subscription_interval']) {
                        // e.g. $5 every Wednesday
                        if (!empty($subscription_details['initial_amount'])) {
                            $subscription_string = sprintf(__('%s %s then %s every %s', 'woocommerce-subscriptions'),
                                $initial_amount_string, $subscription_details['initial_description'],
                                $recurring_amount_string, $payment_day_of_week);
                        } else {
                            $subscription_string = sprintf(__('%s every %s', 'woocommerce-subscriptions'),
                                $recurring_amount_string, $payment_day_of_week);
                        }
                    } else {
                        // e.g. $5 every 2 weeks on Wednesday
                        if (!empty($subscription_details['initial_amount'])) {
                            $subscription_string = sprintf(__('%s %s then %s every %s on %s',
                                'woocommerce-subscriptions'), $initial_amount_string,
                                $subscription_details['initial_description'], $recurring_amount_string,
                                WC_Subscriptions_Manager::get_subscription_period_strings($subscription_details['subscription_interval'],
                                    $subscription_details['subscription_period']), $payment_day_of_week);
                        } else {
                            $subscription_string = sprintf(__('%s every %s on %s', 'woocommerce-subscriptions'),
                                $recurring_amount_string,
                                WC_Subscriptions_Manager::get_subscription_period_strings($subscription_details['subscription_interval'],
                                    $subscription_details['subscription_period']), $payment_day_of_week);
                        }
                    }
                    break;
                case 'month':
                    if (1 == $subscription_details['subscription_interval']) {
                        // e.g. $15 on the 15th of each month
                        if (!empty($subscription_details['initial_amount'])) {
                            if ($payment_day > 27) {
                                $subscription_string = sprintf(__('%s %s then %s on the last day of each month',
                                    'woocommerce-subscriptions'), $initial_amount_string,
                                    $subscription_details['initial_description'], $recurring_amount_string);
                            } else {
                                $subscription_string = sprintf(__('%s %s then %s on the %s of each month',
                                    'woocommerce-subscriptions'), $initial_amount_string,
                                    $subscription_details['initial_description'], $recurring_amount_string,
                                    WC_Subscriptions::append_numeral_suffix($payment_day));
                            }
                        } else {
                            if ($payment_day > 27) {
                                $subscription_string = sprintf(__('%s on the last day of each month',
                                    'woocommerce-subscriptions'), $recurring_amount_string);
                            } else {
                                $subscription_string = sprintf(__('%s on the %s of each month',
                                    'woocommerce-subscriptions'), $recurring_amount_string,
                                    WC_Subscriptions::append_numeral_suffix($payment_day));
                            }
                        }
                    } else {
                        // e.g. $15 on the 15th of every 3rd month
                        if (!empty($subscription_details['initial_amount'])) {
                            if ($payment_day > 27) {
                                $subscription_string = sprintf(__('%s %s then %s on the last day of every %s month',
                                    'woocommerce-subscriptions'), $initial_amount_string,
                                    $subscription_details['initial_description'], $recurring_amount_string,
                                    WC_Subscriptions::append_numeral_suffix($subscription_details['subscription_interval']));
                            } else {
                                $subscription_string = sprintf(__('%s %s then %s on the %s day of every %s month',
                                    'woocommerce-subscriptions'), $initial_amount_string,
                                    $subscription_details['initial_description'], $recurring_amount_string,
                                    WC_Subscriptions::append_numeral_suffix($payment_day),
                                    WC_Subscriptions::append_numeral_suffix($subscription_details['subscription_interval']));
                            }
                        } else {
                            if ($payment_day > 27) {
                                $subscription_string = sprintf(__('%s on the last day of every %s month',
                                    'woocommerce-subscriptions'), $recurring_amount_string,
                                    WC_Subscriptions::append_numeral_suffix($subscription_details['subscription_interval']));
                            } else {
                                $subscription_string = sprintf(__('%s on the %s day of every %s month',
                                    'woocommerce-subscriptions'), $recurring_amount_string,
                                    WC_Subscriptions::append_numeral_suffix($payment_day),
                                    WC_Subscriptions::append_numeral_suffix($subscription_details['subscription_interval']));
                            }
                        }
                    }
                    break;
                case 'year':
                    if (1 == $subscription_details['subscription_interval']) {
                        // e.g. $15 on March 15th each year
                        if (!empty($subscription_details['initial_amount'])) {
                            $subscription_string = sprintf(__('%s %s then %s on %s %s each year',
                                'woocommerce-subscriptions'), $initial_amount_string,
                                $subscription_details['initial_description'], $recurring_amount_string,
                                $wp_locale->month[$payment_day['month']],
                                WC_Subscriptions::append_numeral_suffix($payment_day['day']));
                        } else {
                            $subscription_string = sprintf(__('%s on %s %s each year', 'woocommerce-subscriptions'),
                                $recurring_amount_string, $wp_locale->month[$payment_day['month']],
                                WC_Subscriptions::append_numeral_suffix($payment_day['day']));
                        }
                    } else {
                        // e.g. $15 on March 15th every 3rd year
                        if (!empty($subscription_details['initial_amount'])) {
                            $subscription_string = sprintf(__('%s %s then %s on %s %s every %s year',
                                'woocommerce-subscriptions'), $initial_amount_string,
                                $subscription_details['initial_description'], $recurring_amount_string,
                                $wp_locale->month[$payment_day['month']],
                                WC_Subscriptions::append_numeral_suffix($payment_day['day']),
                                WC_Subscriptions::append_numeral_suffix($subscription_details['subscription_interval']));
                        } else {
                            $subscription_string = sprintf(__('%s on %s %s every %s year', 'woocommerce-subscriptions'),
                                $recurring_amount_string, $wp_locale->month[$payment_day['month']],
                                WC_Subscriptions::append_numeral_suffix($payment_day['day']),
                                WC_Subscriptions::append_numeral_suffix($subscription_details['subscription_interval']));
                        }
                    }
                    break;
            }
        } elseif (!empty($subscription_details['initial_amount'])) {
            if(empty($subscription_period_string)) {
                $subscription_string = sprintf(_n('%s', '%s',
                    $subscription_details['subscription_interval'], 'woocommerce-subscriptions'), $initial_amount_string);
            } else {
            $subscription_string = sprintf(_n('%s %s then %s / %s', '%s %s then %s every %s',
                $subscription_details['subscription_interval'], 'woocommerce-subscriptions'), $initial_amount_string,
                $subscription_details['initial_description'], $recurring_amount_string, $subscription_period_string);
            }
        } elseif (!empty($subscription_details['recurring_amount']) || intval($subscription_details['recurring_amount']) === 0) {
            $subscription_string = sprintf(_n('%s / %s', ' %s every %s', $subscription_details['subscription_interval'],
                'woocommerce-subscriptions'), $recurring_amount_string, $subscription_period_string);
        } else {
            $subscription_string = '';
        }

        if ($subscription_details['subscription_length'] > 0) {
            $subscription_string = sprintf(__('%s for %s', 'woocommerce-subscriptions'), $subscription_string,
                $subscription_ranges[$subscription_details['subscription_period']][$subscription_details['subscription_length']]);
        }

        if ($subscription_details['trial_length'] > 0) {
            $trial_length = self::get_subscription_trial_period_strings($subscription_details['trial_length'],
                $subscription_details['trial_period']);
            if (!empty($subscription_details['initial_amount'])) {
                $subscription_string = sprintf(__('%s after %s free trial', 'woocommerce-subscriptions'),
                    $subscription_string, $trial_length);
            } else {
                $subscription_string = sprintf(__('%s free trial then %s', 'woocommerce-subscriptions'),
                    ucfirst($trial_length), $subscription_string);
            }
        }
    }
    return $subscription_string;
}

/**
 * @param int $number
 * @param string $period
 * @return string
 */
function get_subscription_period_strings_fuel3d( $number = 1, $period = '' ) {

    $translated_periods = apply_filters( 'woocommerce_subscription_periods',
        array(
            'day'   => sprintf( _n( 'day', '%s days', $number, 'woocommerce-subscriptions' ), $number ),
            'week'  => sprintf( _n( 'week', '%s weeks', $number, 'woocommerce-subscriptions' ), $number ),
            'month' => sprintf( _n( 'month', '%s months', $number, 'woocommerce-subscriptions' ), $number ),
            'year'  => sprintf( _n( 'year', '%s years', $number, 'woocommerce-subscriptions' ), $number )
        )
    );

    return ( ! empty( $period ) ) ? $translated_periods[ $period ] : '';
}

if ( ! wp_next_scheduled('task_email_orders_on_hold_row') ) {
    $dateCronTask = "9:00";
    $dateTime = new DateTime($dateCronTask,new DateTimeZone('Europe/London'));
    wp_schedule_event( $dateTime->getTimestamp(), 'daily', 'task_email_orders_on_hold_row' ); // hourly, daily and twicedaily
}

add_action( 'task_email_orders_on_hold_row', 'email_orders_on_hold_row_function' );
function email_orders_on_hold_row_function() {
    // Get the current date time
    $dateTime = new DateTime();

    // Check that the day is Monday
    if ($dateTime->format('N') != 6 && $dateTime->format('N') != 7) {

        $title = 'No orders on hold. [ROW shop]';
        $blogId = 1;
        $isStatusOnHold = false;
        $email = 'accounts@fuel-3d.com';

        switch_to_blog($blogId);
        $args = array(
            'numberposts' => -1,
            'post_type' => 'shop_order',
            'post_status' => 'wc-on-hold',
        );
        $orders = get_posts($args);
        $countOrders = count($orders);
        if ($countOrders > 0) {
            $isStatusOnHold = true;
        }
        if ($isStatusOnHold) {
            $title = "Check orders on hold (" . $countOrders . "). [ROW shop]";
        }

        $emailBody = "You have " . $countOrders . " orders with status 'on-hold'";
        wp_mail(
            $email,
            $title,
            $emailBody
        );
    }
}

if ( ! wp_next_scheduled('task_email_orders_on_hold_usa') ) {
    $dateCronTask = "9:00";
    $dateTime = new DateTime($dateCronTask,new DateTimeZone('America/New_York'));
    wp_schedule_event( $dateTime->getTimestamp(), 'daily', 'task_email_orders_on_hold_usa' ); // hourly, daily and twicedaily
}

add_action( 'task_email_orders_on_hold_usa', 'email_orders_on_hold_usa_function' );
function email_orders_on_hold_usa_function() {
    // Get the current date time
    $dateTime = new DateTime();

    // Check that the day is Monday
    if ($dateTime->format('N') != 6 && $dateTime->format('N') != 7) {

        $title = 'No orders on hold. [USA shop]';
        $blogId = 4;
        $isStatusOnHold = false;
        $email = 'marilyn@fuel-3d.com';

        switch_to_blog($blogId);
        $args = array(
            'numberposts' => -1,
            'post_type' => 'shop_order',
            'post_status' => 'wc-on-hold',
        );
        $orders = get_posts($args);
        $countOrders = count($orders);
        if ($countOrders > 0) {
            $isStatusOnHold = true;
        }
        if ($isStatusOnHold) {
            $title = "Check orders on hold (" . $countOrders . "). [USA shop]";
        }

        $emailBody = "You have " . $countOrders . " orders with status 'on-hold'";
        wp_mail(
            $email,
            $title,
            $emailBody
        );
    }
}

/**
 * @param $user
 * @param $serialNumber
 */
function saveNewDateSerialNumber($userId, $serialNumber)
{
    $nameField = 'dates_create_serial_numbers';
    $dateTime = new DateTime();
    $datesCreateSerialNumbers = unserialize(get_user_meta($userId, $nameField, true));

    if (empty($datesCreateSerialNumbers)) {
        $datesCreateSerialNumbers = array();
        $datesCreateSerialNumbers[$serialNumber] = $dateTime->getTimestamp();
        update_user_meta($userId, $nameField, serialize($datesCreateSerialNumbers));
    } else {
        $datesCreateSerialNumbers[$serialNumber] = $dateTime->getTimestamp();
        update_user_meta($userId, $nameField, serialize($datesCreateSerialNumbers));
    }
}

/**
 * @param $currentUser
 * @param $serialNumber
 */
function removeNewDateSerialNumber($userId, $serialNumber)
{
    $nameField = 'dates_create_serial_numbers';
    $datesCreateSerialNumbers = unserialize(get_user_meta($userId, $nameField, true));
    if (!empty($datesCreateSerialNumbers)) {
        unset($datesCreateSerialNumbers[$serialNumber]);
        update_user_meta($userId, $nameField, serialize($datesCreateSerialNumbers));
    }
}

function mysite_woocommerce_order_status_completed($order_id)
{
    $order = new WC_Order($order_id);
    $items = $order->get_items();

    if (isset($items)) {
        foreach ($items as $item) {
            $productId = isset($item['item_meta']['_product_id'][0]) ? $item['item_meta']['_product_id'][0] : false;

            if (!empty($productId)) {
                $product = wc_get_product($productId);
                $nalpeironPerpetual = $product->get_attribute('nalpeiron_perpetual');

                if (!empty($nalpeironPerpetual) && $nalpeironPerpetual == 'perpetual') {
                    WC_Subscriptions_Manager::put_subscription_on_hold_for_order($order);
                }

            }
        }
    }

}

add_action('woocommerce_order_status_completed',
    'mysite_woocommerce_order_status_completed');
