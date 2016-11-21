<?php
if (!wp_next_scheduled('schedule_custom_woo_orders')) {
    wp_schedule_event(time(), 'daily', 'schedule_custom_woo_orders');
}

add_action('schedule_custom_woo_orders', 'send_emails_with_extra_materials');
add_action('schedule_custom_woo_orders', 'send_emails_no_register_trigger');
add_action('schedule_custom_woo_orders', 'send_emails_no_download_trigger');
add_action('schedule_custom_woo_orders', 'send_emails_follow_up_trigger');
add_action('schedule_custom_woo_orders', 'send_emails_follow_up_2_trigger');


/**
 * @param $days - shift days from today -5 - five days back
 * @param $order_status - designated order status i.e. completed, processing and so on
 *
 * @return array - filtered orders
 */
function get_orders_w_conditions($days, $order_status)
{
    global $wpdb;
    $days = empty($days) ? -5 : $days; // we MUST limit orders amount
    $order_status = empty($order_status) ? __('processing', 'storefront') : __($order_status);
    $order_status = 'wc-' . $order_status;
    $shift_days = $days . ' ' . __('days', 'storefront');
    $before_date = date('Y-m-d', strtotime($shift_days));
    $query = "
    SELECT * FROM fu_posts WHERE post_type='shop_order' AND post_status ='$order_status' AND post_modified_gmt <= '$before_date'";

    return $wpdb->get_results($query);

}


/**
 * @param array $orders - filtered order to send emails
 * @param       $action - kind of email send
 * @param bool $mark_sended - mark orders to prevent resend mails twice or more
 *
 * @return bool true - if any emails send, false - if not
 */
function send_emails_to($orders, $action, $mark_sended = true)
{
    if ((empty($action)) || (empty($orders))) {
        return false;
    }

    $mailer = WC()->mailer();
    $mails = $mailer->get_emails();

    if (empty($mails)) {
        return false;
    }

    // Ensure gateways are loaded in case they need to insert data into the emails
    WC()->payment_gateways();
    WC()->shipping();

    foreach ($orders as $order) {
        $wc_order = new WC_Order($order->ID);
        do_action('woocommerce_before_resend_order_emails', $wc_order);
        foreach ($mails as $mail) {
            if ($mail->id == $action) {

                if (!$mark_sended) {
                    $mail->trigger($wc_order->id);
                    add_post_meta($wc_order->id, '_' . $action . '_sended', 'yes');
                } elseif ($mark_sended && (get_post_meta($wc_order->id, '_' . $action . '_sended', true) != 'yes')) {
                    $mail->trigger($wc_order->id);
                    update_post_meta($wc_order->id, '_' . $action . '_sended', 'yes');
                }

            }
        }
        do_action('woocommerce_after_resend_order_email', $order, $action);
    }

    return true;
}


function send_emails_with_extra_materials()
{
    $days = -5;
    $action = 'customer_order_delivered_register_reminder';
    $order_status = 'completed';

    $orders = get_orders_w_conditions($days, $order_status);

    if (empty($orders)) {
        return false;
    }

    return send_emails_to($orders, $action);
}

function send_emails_no_register_trigger()
{
    $days = -14;
    $action = 'customer_order_dispatched_register_reminder';
    $order_status = 'completed';

    $orders = get_orders_w_conditions($days, $order_status);

    if (empty($orders)) {
        return false;
    }

    return send_emails_to($orders, $action);
}

function send_emails_no_download_trigger()
{
    $days = -21;
    $action = 'customer_order_dispatched_download_reminder';
    $order_status = 'completed';

    $orders = get_orders_w_conditions($days, $order_status);

    if (empty($orders)) {
        return false;
    }

    return send_emails_to($orders, $action);
}

function send_emails_follow_up_trigger()
{
    $days = -7;
    $action = 'customer_order_download_follow_reminder';
    $order_status = 'completed';

    $orders = get_orders_w_conditions($days, $order_status);

    if (empty($orders)) {
        return false;
    }

    return send_emails_to($orders, $action);
}

function send_emails_follow_up_2_trigger()
{
    $days = -30;
    $action = 'customer_order_download_follow_reminder';
    $order_status = 'completed';

    $orders = get_orders_w_conditions($days, $order_status);

    if (empty($orders)) {
        return false;
    }

    return send_emails_to($orders, $action);
}