<?php
namespace MVC\Models;

use \MVC\Singleton;
use \TimelineEvents;
use \WP_Query;

class Portal
{
    use Singleton;

    /**
     * @return array
     */
    public function quickBuyProducts()
    {
        return [get_scanner_product(), get_target_product()];
    }

    /**
     * @return string
     */
    public function quickBuy()
    {
        $quickBuy = plugins_url('fuel3d-distributors/quick-buy.php');
        if (isUsaCustomer()) {
            $quickBuy = parse_url($quickBuy);
            $quickBuy['path'] = "/usa{$quickBuy['path']}";
            $quickBuy = "{$quickBuy['scheme']}://{$quickBuy['host']}{$quickBuy['path']}";
        }

        return $quickBuy;
    }

    /**
     * @return array
     */
    public function quickBuyIDs()
    {
        if (isUsaCustomer()) {
            return [USA_SCANNER_PID, USA_TARGET_PID];
        } else {
            return [SCANNER_PID, TARGET_PID];
        }
    }


    /**
     * Returns basic customer info for logged in user
     *
     * @return array
     */
    public function customer_info()
    {
        global $current_user;

        $since = substr($current_user->user_registered, 0, 10);
        $since = explode('-', $since);
        $since = array_reverse($since);
        $since = implode('/', $since);

        $events = new TimelineEvents();
        $orders = f3d_get_all_user_orders($current_user->ID);
        $events->add('Registered account', $since, 'handshake');

        foreach ($orders as $order) {
            $viewDetailsUrl = $order['shop'] == 4
                ? "/usa/portal/view-order/{$order['id']}/"
                : "/portal/view-order/{$order['id']}/";

            $downloadInvoiceUrl = get_admin_url($order['shop'], 'admin-ajax.php?action=generate_wpo_wcpdf&template_type=invoice&my-account&order_ids=' . $order['id']);
            $downloadInvoiceUrl = wp_nonce_url($downloadInvoiceUrl, 'generate_wpo_wcpdf');

            $events->add(
                "Placed order #{$order['id']}",
                $order['date'],
                'mini-cart',
                [
                    [
                        'name' => 'View Details',
                        'url' => $viewDetailsUrl,
                    ],
                    [
                        'name' => 'Download Invoice',
                        'url' => $downloadInvoiceUrl,
                    ]
                ]
            );
        }

        $addresses = [];

        foreach (['shipping', 'billing'] as $type) {
            $addresses[$type] = [
                get_user_meta($current_user->ID, "{$type}_address_1", true),
                get_user_meta($current_user->ID, "{$type}_address_2", true),
                get_user_meta($current_user->ID, "{$type}_city", true),
                get_user_meta($current_user->ID, "{$type}_state", true),
                get_user_meta($current_user->ID, "{$type}_postcode", true) . ', ' . get_user_meta($current_user->ID, "{$type}_country", true),
            ];

            foreach ($addresses[$type] as $key => $value) {
                $addresses[$type][$key] = trim($value, ', ');

                if (!$addresses[$type][$key]) {
                    unset($addresses[$type][$key]);
                }
            }

            $addresses[$type] = empty($addresses[$type])
                ? '<p>' . _("No {$type} address specified") . '</p>'
                : '<p>' . implode('</p><p>', $addresses[$type]) . '</p>';
        }

        return [
            'events' => $events,
            'since' => $since,
            'shipping_address' => $addresses['shipping'],
            'billing_address' => $addresses['billing'],
        ];
    }

    /**
     * Returns studio download link text
     *
     * @return string|null
     */
    public function studio_download_link()
    {
        $data = '';
        $query = new WP_Query([
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-templates/customer-portal.php'
        ]);

        while ($query->have_posts()) {
            $query->the_post();

            $data = get_field('studio_download_info');
        }

        wp_reset_postdata();

        return $data;
    }

}