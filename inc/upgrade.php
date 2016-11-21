<?php

add_filter('woocommerce_add_cart_item_data', 'upgrade_add_cart_item_data', 10, 3);
function upgrade_add_cart_item_data($cart_item_data, $product_id, $variation_id)
{
    if (isset($_POST[NALPEIRON_HIDDEN_LICENSE_CODE])) {
        $cart_item_data[NALPEIRON_HIDDEN_LICENSE_CODE] = $_POST[NALPEIRON_HIDDEN_LICENSE_CODE];
    }

    return $cart_item_data;
}

add_filter('woocommerce_get_cart_item_from_session', 'upgrade_get_cart_item_from_session', 10, 3);
function upgrade_get_cart_item_from_session($cart_content, $values, $key)
{
    $key = NALPEIRON_HIDDEN_LICENSE_CODE;
    if (isset($values[$key])) {
        $cart_content[$key] = $values[$key];
    }

    return $cart_content;
}

add_filter('woocommerce_get_item_data', 'woocommerce_get_item_data', 10, 2);
function woocommerce_get_item_data($other_data, $cart_item)
{
    $key = NALPEIRON_HIDDEN_LICENSE_CODE;
    if (isset($cart_item[$key])) {
        $other_data[$key] = [
            'name' => NALPEIRON_DISPLAY_LICENSE_CODE,
            'hidden' => null,
            'display' => Nalpeiron::licenseEncode($cart_item[$key]),
            'value' => $cart_item[$key],
        ];
    }

    return $other_data;
}

add_action('woocommerce_add_order_item_meta', 'upgrade_add_order_item_meta', 10, 3);
function upgrade_add_order_item_meta($item_id, $values, $cart_item_key)
{
    $key = NALPEIRON_HIDDEN_LICENSE_CODE;
    if (isset($values[$key])) {
        wc_add_order_item_meta($item_id, $key, $values[$key]);
        wc_add_order_item_meta($item_id, NALPEIRON_DISPLAY_LICENSE_CODE, Nalpeiron::licenseEncode($values[$key]));
    }
}

/**
 * @unused
 */
add_filter('woocommerce_order_again_cart_item_data', 'upgrade_order_again_cart_item_data', 10, 3);
function upgrade_order_again_cart_item_data($cart_item_data, $item, $order)
{
    $key = NALPEIRON_HIDDEN_LICENSE_CODE;
    if (isset($item[$key])) {
        $cart_item_data[$key] = $item[$key];
    }

    return $cart_item_data;
}

/**
 * @todo
 */
add_filter('woocommerce_cart_product_price', 'upgrade_cart_product_price', 15, 2);
function upgrade_cart_product_price($price, $product)
{
    return $price;
}