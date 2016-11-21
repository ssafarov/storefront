<?php

/** portal section **/

add_filter('woocommerce_add_to_cart_redirect', 'portal_add_to_cart_redirect');
function portal_add_to_cart_redirect($url)
{
    // wc_add_notice( apply_filters( 'wc_add_to_cart_message', $message, $product_id ) );
    if ($url == '/portal/' && isset($_POST['add-to-cart'])) {
        $get_checkout_url = apply_filters('woocommerce_get_checkout_url', WC()->cart->get_checkout_url());
        wp_redirect($get_checkout_url);
        exit;
    }

    return $url;
}

/** maps section **/

add_shortcode('DistributorMap', 'shortcodeDistributorMap');
function shortcodeDistributorMap()
{
    return \MVC\Controller::factory('Maps')->render('distributorMap');
}

if (is_admin()) {
    if (!trait_exists('MVC\Singleton')) {
        echo "Please activate MVC-plugin\n";
    } else {
        MVC\Init\AdminAddPins::instance()->init();
        MVC\Init\AdminUsersDownload::instance()->init();
        MVC\Init\InactiveUsers::instance()->init();
    }

    if (!trait_exists('Nalpeiron\Singleton')) {
        echo "Please activate Nalpeiron-plugin\n";
    }
}

add_image_size('video_thumbnails', 200, 110, true);

global $WooCommerce_Cart_Reports;
if (!empty($WooCommerce_Cart_Reports) && (!defined('DOING_CRON') || !DOING_CRON)) {
    remove_action('woocommerce_cart_reset', [$WooCommerce_Cart_Reports, 'woocommerce_scheduled_cart_reset']);
}

add_filter('icegram_valid_messages', 'fix_translate_icegram');
function fix_translate_icegram($messages)
{
    foreach ($messages as $i => &$message) {
        if ($message['post_title' == 'Investor popup'] && !isShowInvestorPopup()) {
            unset($messages [$i]);
        }
        $message['message'] = QTX_Translator::get_translator()->translate_text($message['message']);
    }

    return $messages;
}

function isShowInvestorPopup()
{
    if (!isset($_SERVER['HTTP_REFERER'])) {
        return true;
    }
    $referer = $_SERVER['HTTP_REFERER'];

    // Direct (identified by http referrer in browser/cookie) – only show modal on first visit
    if (!$referer) {
        return true;
    }

    $url = parse_url($referer);
    $host = $url['host'];
    $host = explode('.', $host);
    if ($host[0] == 'www') {
        unset($host[0]);
    }
    $maybeSecondLevelDomain = reset($host);
    $host = implode('.', $host);

    // Google/PPC (identified by gclid which is appended to the end of URLs) – don’t show modal
    if ($host == 'googleadservices.com') {
        return false;
    }

    // Google/Search (identified by http referrer in browser/cookie)– only show modal on first visit
    if ($maybeSecondLevelDomain == 'google') {
        return true;
    }

    // Bing/Search (identified by http referrer in browser/cookie) – only show modal on first visit
    if ($host == 'bing.com') {
        return true;
    }

    // Referral from another site – don’t show modal at all
    return false;
}

/**
 * @see wc_get_page_permalink
 */
add_filter('woocommerce_get_checkout_page_permalink', 'fix_checkout_page_permalink');
function fix_checkout_page_permalink($permalink)
{
    /**
     * @bug if guest checkout
     *
     * \MVC\Helpers\Logs::error('debug', ['fix_checkout_page_permalink' => $permalink,]);
     * on live: [fix_checkout_page_permalink] => /
     * on local: [fix_checkout_page_permalink] => null
     */

    /**
     * @hack
     */
    $permalink = get_site_url() . '/checkout/';

    return $permalink;
}

/**
 * include smoke unit tests
 */
if (explode('?', $_SERVER['REQUEST_URI'])[0] == '/smoke-unit') {
    include __DIR__ . '/smoke.php';
}
