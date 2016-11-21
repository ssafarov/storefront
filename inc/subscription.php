<?php
/**
 * @link http://docs.woothemes.com/document/subscriptions/renewal-process/
 * @see WC_Gateway_Stripe_Subscriptions::scheduled_subscription_payment();
 * @see WC_Gateway_Stripe_Subscriptions::process_subscription_payment();
 */
add_action('scheduled_subscription_payment_stripe', 'before_scheduled_subscription_payment_stripe', 5, 3);
function before_scheduled_subscription_payment_stripe($amount_to_charge, $order, $subscription_product_id)
{
    global $subscription_order;
    $subscription_order = $order;
    add_filter('woocommerce_currency', 'fix_subscription_woocommerce_currency');
}

// add_action( 'scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 3 );

add_action('scheduled_subscription_payment_stripe', 'after_scheduled_subscription_payment_stripe', 15, 3);
function after_scheduled_subscription_payment_stripe($amount_to_charge, $order, $subscription_product_id)
{
    global $subscription_order;
    unset($subscription_order);
    remove_filter('woocommerce_currency', 'fix_subscription_woocommerce_currency');
}

function fix_subscription_woocommerce_currency($currency)
{
    global $subscription_order;

    return $subscription_order->get_order_currency();
}