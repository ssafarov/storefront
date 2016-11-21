<?php
/*
// clear cron // todo remove
if (isset($_GET['cron_clear_secret_7818168'])) {
    update_option('cron', '');
    echo 'clear';
    exit;
}
*/

if (WP_DEBUG) {
    add_filter('wc_aelia_visitor_ip', 'debug_wc_aelia_visitor_ip');
    function debug_wc_aelia_visitor_ip($visitor_ip)
    {
        if ($visitor_ip == '127.0.0.1') {
            $visitor_ip = '8.8.8.8'; // US
            // $visitor_ip = '5.9.37.172'; // de
        }
        if (isset($_GET['ip'])) {
            $visitor_ip = $_GET['ip'];
        }

        return $visitor_ip;
    }
}


/** geoip **/
function getVisitorCountry()
{
    /* without Aelia
    require_once('woocommerce/includes/class-wc-geolocation.php');
    $wcGeoLocation = new WC_Geolocation();
    $wcGeoLocation->init();
    $data = $wcGeoLocation->geolocate_ip($wcGeoLocation->get_ip_address());
    $country = $data['country'];
    */

    $user = wp_get_current_user();
    $userShippingCountry = [];
    $userBillingCountry = [];

    if ( $user->exists() ) {
        $userShippingCountry = get_user_meta($user->ID, 'shipping_country');
        $userBillingCountry = get_user_meta($user->ID, 'billing_country');
    }

    if ( isset($userShippingCountry[0]) && isset($userBillingCountry[0]) && ( strcmp($userBillingCountry[0], $userShippingCountry[0]) === 0) ) {
        $country = $userShippingCountry[0];
    } else {
        $aeliaIP2Location = new WC_Aelia_IP2Location();
        $country = $aeliaIP2Location->get_visitor_country();
    }

    return $country;
}

add_filter('qtranslate_language', 'geoip_qtranslate_language', 10, 2);
function geoip_qtranslate_language($lang, $url_info)
{

    if (isset($url_info['lang_cookie_front']) && $url_info['lang_cookie_front']) {
        return $lang;
    }

    //    if (!apply_filters('get_custom_option', 'yes', 'is_geoip_detect_lang')) {
    if (!get_option('is_geoip_detect_lang')) {
        return $lang;
    }

    if (!session_id()) {
        @session_start();
    }

    // first visit
    if (!isset($_SESSION['geoip_country'])) {
        $_SESSION['geoip_country'] = getVisitorCountry();
        $geoip_lang = strtolower($_SESSION['geoip_country']);

        if (in_array($geoip_lang, ['fr', 'de', 'nl', 'es', 'it', 'ja'])) {
            $lang = $geoip_lang;
        }
    }

    return $lang;
}

add_filter('add_custom_options', 'add_geoip_detect_lang_option');
function add_geoip_detect_lang_option($options)
{
    $options['is_geoip_detect_lang'] = [
        'title' => 'The language detection in place',
        'default' => 'yes',
        'autoload' => 'yes',
        'type' => 'checkbox', // text
    ];

    return $options;
}

function getBlogIdByIp()
{

    $current_user = wp_get_current_user();
    $redirectCountries = ['US', 'CA', 'MX'];
    $visitorCountry = getVisitorCountry();
    $shippingCountry   = $visitorCountry;
    $billingCountry    = $visitorCountry;

    if ($current_user->exists()){
        $shippingCountry   = get_user_meta($current_user->ID, 'shipping_country', true);
        $billingCountry    = get_user_meta($current_user->ID, 'billing_country', true);
    }

    $countries = [$visitorCountry, $shippingCountry, $billingCountry];
    $country = array_intersect($redirectCountries, $countries);

    $blogId = (!empty($country) && in_array($country[0], $redirectCountries)) ? 4 : 1;

    return $blogId;
}

/**
 * @HACK
 * hotfix
 */
global $original_current_blog_id;
$original_current_blog_id = get_current_blog_id();
// return apply_filters( 'woocommerce_get_price', $this->price, $this );
add_filter('woocommerce_get_price', 'fix_us_product_price_on_us_site', 1000000, 2);
add_filter('woocommerce_get_price', 'fix_us_product_price_on_us_site', 0, 2);
function fix_us_product_price_on_us_site($price, $product)
{
    global $original_current_blog_id;
    if ($original_current_blog_id == 1 && getBlogIdByIp() == 4 && get_current_blog_id() == 4) {
        $price = get_post_meta($product->id, '_price', true);

        return $price;
    }

    return $price;
}

// return apply_filters( 'woocommerce_currency_symbol', $currency_symbol, $currency );
add_filter('woocommerce_currency_symbol', 'fix_us_currency_symbol_on_us_site', 1000000, 2);
add_filter('woocommerce_currency_symbol', 'fix_us_currency_symbol_on_us_site', 0, 2);
function fix_us_currency_symbol_on_us_site($currency_symbol, $currency)
{
    global $original_current_blog_id;
    if ($original_current_blog_id == 1 && getBlogIdByIp() == 4 && get_current_blog_id() == 4) {
        return '$';
    }

    return $currency_symbol;
}

/**
 * Terms & Conditions error validation
 */
add_filter('woocommerce_add_error', 'woocommerce_add_error');
function woocommerce_add_error($message)
{
    if ($message == __('You must accept our Terms &amp; Conditions.', 'woocommerce')) {
        $message = __('You must check the box to accept our Terms &amp; Conditions at the <span
                        style="font-weight: bold; cursor: pointer;" id="toBottom">bottom of the page</span>', 'storefront');
    }

    return $message;
}


/**
 * Returns generated form to be displayed
 *
 * @param array $form
 * @param array $opt
 * @param string $context
 * @param mixed $widget_args
 * @return string
 */
if (!function_exists('woochimp_prepare_form')) {
    function woochimp_prepare_form($opt, $context, $widget_args = null)
    {

        // Define form styles
        $form_styles = array(
            '2' => 'woochimp_skin_general',
        );

        $form = '';

        // Set ajax url
        $form .= '<script>var ajaxurl = \'' . admin_url('admin-ajax.php') . '\';</script>';

        // Display custom css
        $form .= '<style>.woochimp_sc{padding: 5px;}
                    #woochimp_shortcode_content{padding: 15px;}
                    #woochimp_registration_form_shortcode>div.label{text-align: right; margin-top: 10px;     }
                    #woochimp_shortcode_subscription_email{height: 35px!important;}
                    #woochimp_shortcode_subscription_submit{height: 35px!important;line-height: 0.329;}</style>';

        // Override styles if needed
        if ($opt['woochimp_shortcode_skin'] != '1') {
            $form .= '<div class="woochimp-reset ' . ($opt['woochimp_shortcode_skin'] > 1 ? $form_styles[$opt['woochimp_shortcode_skin']] : '') . ' woochimp_sc">';
        } else {
            $form .= '<div class="woochimp_sc row">';
        }

        // Make sure we now this is a shortcode
        $form .= '<div class="woochimp_shortcode_content">';

        // Begin form
        $form .= '<form id="woochimp_registration_form_shortcode">';

        // Email address
        $form .= '<div class="col-md-4 col-sm-4 col-xs-12 label"><nobr><p>' . $opt['woochimp_label_subscribe_shortcode'] . '</p></nobr></div>';

        $form .= '<div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="woochimp_shortcode_subscription[email]" id="woochimp_shortcode_subscription_email" class="woochimp_shortcode_field" placeholder="' . ($opt['woochimp_shortcode_show_labels_inline'] ? $opt['woochimp_label_email'] : '') . '" title="' . $opt['woochimp_label_email'] . '" /></div>';

        // Submit button
        $form .= '<div class="col-md-2 col-sm-2 col-xs-12"><button class="btn btn-tertiary" type="button" id="woochimp_shortcode_subscription_submit" value="' . $opt['woochimp_label_button'] . '">' . $opt['woochimp_label_button'] . '</button></div>';

        // End form
        $form .= '</form></div></div>';

        return $form;
    }
}

// save language
add_filter('qtranslate_language', 'user_save_language', 11, 2);
function user_save_language($lang, $url_info)
{
    $user_id = get_current_user_id();
    if ($user_id) {
        update_user_meta($user_id, '_language', $lang);
    }

    return $lang;
}

/**
 * reload translates
 * @see load_theme_textdomain
 * @param $domain
 * @param bool|false $path
 * @param $locale
 * @return bool
 */
function reload_theme_textdomain($domain, $path, $locale)
{
    $locale = apply_filters('theme_locale', $locale, $domain);
    if (!$path) {
        $path = get_template_directory();
    }
    $mofile = untrailingslashit($path) . "/{$locale}.mo";
    if ($loaded = load_textdomain($domain, $mofile)) {
        return $loaded;
    }
    $mofile = WP_LANG_DIR . "/themes/{$domain}-{$locale}.mo";

    return load_textdomain($domain, $mofile);
}

/**
 * @see load_muplugin_textdomain
 * @param $domain
 * @param $deprecated
 * @param $plugin_rel_path
 * @param $locale
 * @return bool
 */
function reload_plugin_textdomain($domain, $deprecated, $plugin_rel_path, $locale)
{
    if (false !== $plugin_rel_path) {
        $path = WP_PLUGIN_DIR . '/' . trim($plugin_rel_path, '/');
    } elseif (false !== $deprecated) {
        _deprecated_argument(__FUNCTION__, '2.7');
        $path = ABSPATH . trim($deprecated, '/');
    } else {
        $path = WP_PLUGIN_DIR;
    }

    // Load the textdomain according to the plugin first
    $mofile = $domain . '-' . $locale . '.mo';
    if ($loaded = load_textdomain($domain, $path . '/' . $mofile)) {
        return $loaded;
    }

    // Otherwise, load from the languages directory
    $mofile = WP_LANG_DIR . '/plugins/' . $mofile;

    return load_textdomain($domain, $mofile);
}

/**
 * @see load_theme_textdomain
 * @see load_muplugin_textdomain
 * @param $lang
 */
function setLanguage($lang)
{
    // qtranslate:
    global $q_config;
    $q_config['language'] = $lang;

    // reload:
    if ($lang != 'ja') {
        $lang = $lang . '_' . strtoupper($lang);
    }
    reload_theme_textdomain('storefront', get_stylesheet_directory() . '/languages', $lang);
    // reload_plugin_textdomain('woocommerce', '', 'woocommerce/i18n/languages', $lang);
    // reload_plugin_textdomain('woocommerce-subscriptions', '', 'woocommerce-subscriptions/languages', $lang);

    // todo reload other domains
}

add_filter('woocommerce_email_format_string_find', '__woocommerce_email_format_string_find', 10, 2);
function __woocommerce_email_format_string_find($find, $wc_mail_object)
{
    global $_global_wc_mail_object;
    $_global_wc_mail_object = $wc_mail_object;

    return $find;
}

add_filter('wp_mail', 'translate_mail');
function translate_mail($atts)
{
    /**
     * @var WC_Email $_global_wc_mail_object
     */
    global $_global_wc_mail_object;
    if (!$_global_wc_mail_object) {
        return $atts;
    }

    $to = $atts['to'];
    $user = get_user_by('email', $to);
    // todo billing_email
    if (!$user) {
        return $atts;
    }


    global $q_config;
    $restore_lang = $q_config['language'];
    $lang = get_user_meta($user->ID, '_language', true); // test

    setLanguage($lang);

    $message = $_global_wc_mail_object->get_content();
    $message = apply_filters('woocommerce_mail_content', $_global_wc_mail_object->style_inline($message));
    $atts['message'] = $message;
    $atts['subject'] = $_global_wc_mail_object->get_subject();

    // todo restore ?

    return $atts;
}

// fix q-translate + sso discourse
add_filter('qtranslate_language_detect_redirect', 'fix_qtranslate_language_detect_redirect', 10, 3);
function fix_qtranslate_language_detect_redirect($url_lang, $url_orig, $url_info)
{
    if ($url_lang != $url_orig && strpos($url_orig, '?sso=') && strpos($url_orig, '&sig=')) {
        return $url_orig;
    }

    return $url_lang;
}

/**
 * @see https://mintsoft.atlassian.net/wiki/display/OMS/Custom+Snippet
 */
// Register new status
function register_picking_order_status()
{
    register_post_status('wc-picking-shipment', array(
        'label' => 'Picking',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Picking <span class="count">(%s)</span>', 'Picking <span class="count">(%s)</span>')
    ));

    // Fixing taxjar settings for the correct tax calculations on the US store.
    if ( ( get_option( 'woocommerce_tax_based_on' ) != "shipping" ) && ( isUsaShop() ) ) {
        update_option( 'woocommerce_tax_based_on', "shipping" );
    }

}

add_action('init', 'register_picking_order_status');

// Add to list of WC Order statuses
add_filter('wc_order_statuses', 'add_picking_to_order_statuses');
function add_picking_to_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    // add new order status after processing
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-processing' === $key) {
            $new_order_statuses['wc-picking-shipment'] = 'Picking';
        }
    }

    return $new_order_statuses;
}

// Add to list of WC Order statuses
add_filter('wc_order_statuses', 'add_kwickpack_processing_to_order_statuses');
function add_kwickpack_processing_to_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    // add new order status after processing
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-pending' === $key) {
            $new_order_statuses['wc-kwickpackprocess'] = 'Kwickpack Processing';
        }
    }

    return $new_order_statuses;
}

// Register new status
function register_kwickpack_processing_order_status()
{

    register_post_status('wc-kwickpackprocess', array(
        'label' => 'Whitehouse Processing',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Kwickpack Processing <span class="count">(%s)</span>', 'Kwickpack Processing <span class="count">(%s)</span>')
    ));

    // Fixing taxjar settings for the correct tax calculations on the US store.
    if ( ( get_option( 'woocommerce_tax_based_on' ) != "shipping" ) && ( isUsaShop() ) ) {
        update_option( 'woocommerce_tax_based_on', "shipping" );
    }
}

add_action('init', 'register_kwickpack_processing_order_status');

// Add to list of WC Order statuses
add_filter('wc_order_statuses', 'add_wynit_process_to_order_statuses');
function add_wynit_process_to_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    // add new order status after processing
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-pending' === $key) {
            $new_order_statuses['wc-wynit'] = 'Wynit Process';
        }
    }

    return $new_order_statuses;
}

// Register new status
function register_wynit_process_order_status()
{

    register_post_status('wc-wynit', array(
        'label' => 'Wynit Process',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Wynit Process <span class="count">(%s)</span>', 'Wynit Process <span class="count">(%s)</span>')
    ));

    // Fixing taxjar settings for the correct tax calculations on the US store.
    if ( ( get_option( 'woocommerce_tax_based_on' ) != "shipping" ) && ( isUsaShop() ) ) {
        update_option( 'woocommerce_tax_based_on', "shipping" );
    }
}

add_action('init', 'register_wynit_process_order_status');